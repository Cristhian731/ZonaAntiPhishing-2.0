<?php

class UrlAnalyzer
{
    private const SHORTENER_DOMAINS = [
        'bit.ly',
        'tinyurl.com',
        't.co',
        'goo.gl',
    ];

    private const SUSPICIOUS_KEYWORDS = [
        'verify',
        'login',
        'secure',
        'update',
        'account',
        'password',
    ];

    private const BRAND_DOMAINS = [
        'paypal' => ['paypal.com'],
        'google' => ['google.com', 'google.co.uk', 'google.ca', 'google.com.au', 'google.co.in'],
        'microsoft' => ['microsoft.com', 'microsoftonline.com', 'office.com', 'live.com', 'outlook.com'],
        'apple' => ['apple.com', 'icloud.com'],
        'amazon' => ['amazon.com', 'amazon.co.uk', 'amazon.ca', 'amazon.com.au', 'amazon.in'],
        'facebook' => ['facebook.com', 'fb.com'],
        'instagram' => ['instagram.com'],
        'netflix' => ['netflix.com'],
    ];

    public function analyze(string $url): array
    {
        $url = trim($url);
        $findings = [];
        $highRiskFinding = false;
        $hasScheme = preg_match('/^[a-z][a-z0-9+.-]*:\/\//i', $url) === 1;
        $urlToParse = $hasScheme ? $url : 'http://' . $url;
        $parts = parse_url($urlToParse);

        if (!$hasScheme || strtolower((string) ($parts['scheme'] ?? '')) !== 'https') {
            $findings[] = 'The URL does not use HTTPS.';
        }

        $hasAuthorityCredentials = is_array($parts)
            && (array_key_exists('user', $parts) || array_key_exists('pass', $parts));

        if ($hasAuthorityCredentials) {
            $findings[] = 'The URL contains an @ symbol, which can disguise the destination host.';
            $highRiskFinding = true;
        }

        $host = strtolower(rtrim((string) ($parts['host'] ?? ''), '.'));

        if ($host !== '' && filter_var(trim($host, '[]'), FILTER_VALIDATE_IP) !== false) {
            $findings[] = 'The URL uses an IP address instead of a domain name.';
            $highRiskFinding = true;
        }

        $hostLabels = array_values(array_filter(explode('.', $host), 'strlen'));
        $subdomainLabels = array_slice($hostLabels, 0, max(0, count($hostLabels) - 2));

        foreach ($hostLabels as $label) {
            if (str_starts_with($label, 'xn--')) {
                $findings[] = 'The domain contains punycode, which can represent lookalike characters in a web address.';
                $highRiskFinding = true;
                break;
            }
        }

        if (count($subdomainLabels) > 3) {
            $findings[] = 'The hostname has more than 3 subdomain levels, making its true destination harder to identify.';
            $highRiskFinding = true;
        }

        $normalizedHost = strtr($host, ['0' => 'o', '1' => 'l', '3' => 'e', '5' => 's']);

        foreach (self::BRAND_DOMAINS as $brand => $officialDomains) {
            if (!str_contains($normalizedHost, $brand)) {
                continue;
            }

            $usesOfficialBrandDomain = false;

            foreach ($officialDomains as $officialDomain) {
                if ($host === $officialDomain || str_ends_with($host, '.' . $officialDomain)) {
                    $usesOfficialBrandDomain = true;
                    break;
                }
            }

            if (!$usesOfficialBrandDomain) {
                $findings[] = 'The hostname uses the name of '
                    . ucfirst($brand)
                    . ' outside its known domain, which may indicate brand impersonation.';
                $highRiskFinding = true;
            } else {
                foreach ($subdomainLabels as $label) {
                    if (str_contains(strtr($label, ['0' => 'o', '1' => 'l', '3' => 'e', '5' => 's']), $brand)) {
                        $findings[] = 'A '
                            . ucfirst($brand)
                            . ' name appears in a subdomain; check that the hostname still ends in '
                            . implode(' or ', $officialDomains)
                            . '.';
                        break;
                    }
                }
            }
        }

        $urlLength = function_exists('mb_strlen') ? mb_strlen($url, 'UTF-8') : strlen($url);

        if ($urlLength > 100) {
            $findings[] = 'The URL is longer than 100 characters.';
        }

        $path = (string) ($parts['path'] ?? '');
        $segments = array_values(array_filter(explode('/', trim($path, '/')), 'strlen'));

        if (count($segments) > 5) {
            $findings[] = 'The URL contains more than 5 path segments.';
        }

        $pathAndQuery = strtolower(rawurldecode($path . ' ' . (string) ($parts['query'] ?? '')));
        $normalizedPathAndQuery = strtr($pathAndQuery, ['0' => 'o', '1' => 'l', '3' => 'e', '5' => 's']);

        foreach (self::BRAND_DOMAINS as $brand => $officialDomains) {
            $usesOfficialBrandDomain = false;

            foreach ($officialDomains as $officialDomain) {
                if ($host === $officialDomain || str_ends_with($host, '.' . $officialDomain)) {
                    $usesOfficialBrandDomain = true;
                    break;
                }
            }

            if (
                !$usesOfficialBrandDomain
                && preg_match(
                    '/(?<![a-z0-9])' . preg_quote($brand, '/') . '(?![a-z0-9])/i',
                    $normalizedPathAndQuery
                ) === 1
            ) {
                $findings[] = 'The URL path or query names '
                    . ucfirst($brand)
                    . ', but the hostname is not one of its known domains. Verify the actual destination before continuing.';
            }
        }

        $keywordText = implode('/', [
            $host,
            rawurldecode($path),
            rawurldecode((string) ($parts['query'] ?? '')),
        ]);
        $keywordParts = preg_split('/[^a-z0-9]+/i', strtolower($keywordText), -1, PREG_SPLIT_NO_EMPTY);
        $matchedKeywords = array_values(array_intersect(self::SUSPICIOUS_KEYWORDS, $keywordParts ?: []));
        $subdomainText = implode('.', $subdomainLabels);
        $subdomainParts = preg_split('/[^a-z0-9]+/i', $subdomainText, -1, PREG_SPLIT_NO_EMPTY);
        $subdomainKeywords = array_values(array_intersect(self::SUSPICIOUS_KEYWORDS, $subdomainParts ?: []));

        if ($subdomainKeywords !== []) {
            $findings[] = 'Sensitive-action terms appear in subdomain labels ('
                . implode(', ', $subdomainKeywords)
                . '); read the hostname from right to left to identify the actual domain.';
        }

        if ($matchedKeywords !== []) {
            $findings[] = 'The URL contains sensitive-action terms ('
                . implode(', ', $matchedKeywords)
                . '); unexpected links using these terms deserve extra scrutiny.';
        }

        foreach (self::SHORTENER_DOMAINS as $shortenerDomain) {
            if ($host === $shortenerDomain || str_ends_with($host, '.' . $shortenerDomain)) {
                $findings[] = 'The URL uses a shortened link domain (' . $shortenerDomain . ').';
                $highRiskFinding = true;
                break;
            }
        }

        if ($highRiskFinding) {
            $riskLevel = 'High';
            $recommendation = 'Avoid opening this URL. Verify the destination through a trusted source before continuing.';
        } elseif ($findings !== []) {
            $riskLevel = 'Medium';
            $recommendation = 'Review the URL and its sender carefully. Confirm the destination before opening it.';
        } else {
            $riskLevel = 'Low';
            $recommendation = 'No common warning signs were detected. This analysis cannot guarantee that the URL is safe.';
        }

        return [
            'risk_level' => $riskLevel,
            'findings' => $findings,
            'recommendation' => $recommendation,
        ];
    }
}