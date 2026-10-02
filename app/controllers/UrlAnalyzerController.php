<?php

require_once __DIR__ . '/../models/UrlAnalyzer.php';

class UrlAnalyzerController
{
    private UrlAnalyzer $urlAnalyzer;

    public function __construct()
    {
        $this->urlAnalyzer = new UrlAnalyzer();
    }

    public function analyze(string $url): array
    {
        return $this->urlAnalyzer->analyze($url);
    }
}