<?php

namespace Classes;


/**
 * Class RecursiveClass
 * @package Classes
 */
class RecursiveClass
{
    /**
     * @var HtmlParser
     */
    protected $htmlParser;
    /**
     * @var int
     */
    protected $depth = 0;
    /**
     * @var array
     */
    protected $outputDataArr = [];
    /**
     * @var array
     */
    protected $linksArr = [];

    /**
     * RecursiveClass constructor.
     * @param HtmlParser $htmlParser
     */
    public function __construct(HtmlParser $htmlParser)
    {
        $this->htmlParser = $htmlParser;
        /**
         * set first url for recursive parsing
         */
        $this->linksArr[] = $htmlParser->getParseUrl();
    }

    /**
     * get data recursive parsing by HtmlParser as array
     *
     * @return array
     */
    public function getData(): array
    {
        /**
         * iteration of the recursion depth
         */
        $this->depth++;

        /**
         * detecting no parsed url flag for run recursion
         * if TRUE that mean that appeared no parsed url
         */
        $new = false;

        foreach ($this->linksArr as $link) {
            if (!isset($this->outputDataArr[$link])) {

                /**
                 * set next new link for parsing
                 */
                $this->htmlParser->setParseUrl($link);

                /**
                 * checking if url is available
                 */
                if ($htmlParser->checkUrl()) {
                    /**
                     * beginning html parsing
                     */
                    $this->htmlParser->startHtmlParsing();

                    /**
                     * get img tags array
                     */
                    $imgsArr = $this->htmlParser->getImgsArr();

                    /**
                     * counting img tags from parsing url
                     */
                    $countImgs = count($imgsArr);

                    /**
                     * get page loading time from parsing url
                     */
                    $pageLoadingTime = $this->htmlParser->getPageLoadingTime();

                    /**
                     * get all links from parsing url
                     */
                    $this->linksArr = $this->htmlParser->getAllLinks();

                    $this->outputDataArr[$link] = [
                        'url'       => $link,
                        'countImgs' => $countImgs,
                        'depth'     => $this->depth,
                        'time'      => $pageLoadingTime
                    ];

                    $new = true;
                }
            }
        }

        /**
         * if flag $new equal true that mean that found new urls
         * and will be call getData() recursiving
         */
        if ($new) {
            $this->getData();
        }

        return $this->outputDataArr;
    }
}