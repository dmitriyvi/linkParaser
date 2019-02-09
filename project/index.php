<?php

use Classes\HtmlParser;
use Classes\OutputData;
use Classes\RecursiveClass;

if (isset($argv[1])) {
    $url = $argv[1];

    $htmlParser = new HtmlParser($url);

    if ($htmlParser->checkUrl()) {
        $recursiveClass = new RecursiveClass($htmlParser);

        $outputDataArr = $recursiveClass->getData();
        $outputData = new OutputData($outputDataArr);

        $table = $outputData->getTable();

        $fileName = parse_url($url)['host'] . '_' . date('d.m.y') . ".html";
        $outputData->saveToFile($fileName, $table);
    } else {
        throw new Exception('Sorry! Page not available!');
    }
} else {
    throw new Exception('Please set url in first argument!');
}

function __autoload($class)
{
    $class = str_replace('\\', '/', $class) . '.php';
    require_once($class);
}