<?php

namespace Classes;

/**
 * Class HtmlParser
 * @package Classes
 */
class HtmlParser
{
    /**
     * @var string
     */
    protected $rootUrl;
    /**
     * @var string
     */
    protected $parseUrl;
    /**
     * @var
     */
    protected $content;
    /**
     * @var
     */
    public $pageLoadingTime;

    /**
     * HtmlParser constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->rootUrl = $url;
        $this->parseUrl = $url;
    }

    /**
     *
     */
    public function startHtmlParsing(): void
    {
        $executionStartTime = microtime(true);

        $ch = curl_init($this->parseUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        $this->content = curl_exec($ch);
        curl_close($ch);

        $this->pageLoadingTime = $this->getExecutionTime($executionStartTime);
    }

    /**
     * @return bool
     */
    public function checkUrl(): bool
    {
        $handle = curl_init($this->rootUrl);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        curl_close($handle);

        if ($httpCode != 200) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getHtmlContent(): string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getPageLoadingTime(): string
    {
        return $this->pageLoadingTime;
    }

    /**
     * @param string $parseUrl
     */
    public function setParseUrl(string $parseUrl): void
    {
        $this->parseUrl = $parseUrl;
    }

    /**
     * @return string
     */
    public function getParseUrl(): string
    {
        return $this->parseUrl;
    }

    /**
     * @return array
     */
    public function getImgsArr(): array
    {
        $imgsArr = [];

        if (!empty($this->content)) {
            preg_match_all("#<img.+>#U", $this->content, $matches);
            $imgsArr = isset($matches[0]) ? $matches[0] : [];
        }

        return $imgsArr;
    }

    /**
     * @return array
     */
    public function getAllLinks(): array
    {
        $links = [];

        if (!empty($this->content)) {
            preg_match_all('#<a href="(' . $this->rootUrl . '.*?)"#', $this->content, $matches);
//            preg_match_all('#<a href="(http://robotstxt.org.ru/.*?)"#', $this->content, $matches);

            $links = isset($matches[1]) ? $matches[1] : [];
        }

        return $links;
    }

    /**
     * @param $start
     * @return string
     */
    protected function getExecutionTime($start): string
    {
        return (microtime(true) - $start);
    }

}