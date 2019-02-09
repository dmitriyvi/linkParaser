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
     * beginning html parsing by $parseUrl
     */
    public function startHtmlParsing(): void
    {
        /**
         * begin for counting execution time
         */
        $executionStartTime = microtime(true);

        $ch = curl_init($this->parseUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        $this->content = curl_exec($ch);
        curl_close($ch);

        /**
         * set execution time
         */
        $this->pageLoadingTime = $this->getExecutionTime($executionStartTime);
    }

    /**
     * check whether the url is available
     * default check the $rootUrl
     *
     * @param bool $url
     * @return bool
     */
    public function checkUrl($url = false): bool
    {
        $checkUrl = $this->rootUrl;

        if ($url) {
            $checkUrl = $url;
        }

        $handle = curl_init($checkUrl);
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
     * get html content after parsing
     *
     * @return string
     */
    public function getHtmlContent(): string
    {
        return $this->content;
    }

    /**
     * get page loading time when it parsing
     *
     * @return string
     */
    public function getPageLoadingTime(): string
    {
        return $this->pageLoadingTime;
    }

    /**
     * set url for parsing
     *
     * @param string $parseUrl
     */
    public function setParseUrl(string $parseUrl): void
    {
        $this->parseUrl = $parseUrl;
    }

    /**
     * get url for parsing
     *
     * @return string
     */
    public function getParseUrl(): string
    {
        return $this->parseUrl;
    }

    /**
     * get founded img tags from parsed url content as array
     *
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
     * get links from parsed url content as array
     *
     * @return array
     */
    public function getAllLinks(): array
    {
        $links = [];

        if (!empty($this->content)) {
            preg_match_all('#href="(' . $this->rootUrl . '.*?)"#', $this->content, $matches);

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