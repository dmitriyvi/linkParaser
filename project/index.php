<?php

use Classes\HtmlParser;
use Classes\OutputData;
use Classes\RecursiveClass;

/**
 * checking if exist argument with domain name
 */
if (isset($argv[1])) {
    /**
     * set domain name to variable
     */
    $url = $argv[1];

    /**
     * creating HtmlParser object for parsing data
     */
    $htmlParser = new HtmlParser($url);

    /**
     * checking if url from argument is available
     */
    if ($htmlParser->checkUrl()) {
        /**
         * creating RecursiveClass object with injecting HtmlParser object
         * for recursive getting data from html content
         */
        $recursiveClass = new RecursiveClass($htmlParser);

        /**
         * get data array from each unique link recursive
         */
        $outputDataArr = $recursiveClass->getData();

        /**
         * creating OutputData object for outputting data
         */
        $outputData = new OutputData($outputDataArr);

        /**
         * sorting data by img tags count before outputting
         */
        $outputData->sortingByImgCount();

        /**
         * get data as html table for outputting
         */
        $table = $outputData->getTable();

        $fileName = parse_url($url)['host'] . '_' . date('d.m.y') . ".html";

        /**
         * save to file data
         */
        $outputData->saveToFile($fileName, $table);
    } else {
        throw new Exception('Sorry! Page not available!');
    }
} else {
    throw new Exception('Please set url in first argument! For example: http://example.com/');
}

function __autoload($class)
{
    $class = str_replace('\\', '/', $class) . '.php';
    require_once($class);
}