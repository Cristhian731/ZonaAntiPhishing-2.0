<?php

class UrlAnalyzer
{
    private const SHORTENER_DOMAINS = [
        'bit.ly',
        'tinyurl.com',
        't.co',
        'goo.gl',
    ];

    public function analyze(string $url): array
    {
        $url = trim($url);
        $findings = [];
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
        }

        $host = strtolower(rtrim((string) ($parts['host'] ?? ''), '.'));

        if ($host !== '' && filter_var(trim($host, '[]'), FILTER_VALIDATE_IP) !== false) {
            $findings[] = 'The URL uses an IP address instead of a domain name.';
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

        foreach (self::SHORTENER_DOMAINS as $shortenerDomain) {
            if ($host === $shortenerDomain || str_ends_with($host, '.' . $shortenerDomain)) {
                $findings[] = 'The URL uses a shortened link domain (' . $shortenerDomain . ').';
                break;
            }
        }

        $highRiskFinding = false;

        foreach ($findings as $finding) {
            if (
                str_contains($finding, 'IP address')
                || str_contains($finding, '@ symbol')
                || str_contains($finding, 'shortened link domain')
            ) {
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