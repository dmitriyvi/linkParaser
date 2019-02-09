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
        $this->linksArr[] = $htmlParser->getParseUrl();
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $this->depth++;

        $new = false;

        foreach ($this->linksArr as $link) {
            if (!isset($this->outputDataArr[$link])) {

                $this->htmlParser->setParseUrl($link);
                $this->htmlParser->startHtmlParsing();

                $imgsArr = $this->htmlParser->getImgsArr();
                $countImgs = count($imgsArr);
                $pageLoadingTime = $this->htmlParser->getPageLoadingTime();

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

        if ($new) {
            $this->getData();
        }

        return $this->outputDataArr;
    }
}