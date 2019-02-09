<?php

namespace Classes;

/**
 * Class OutputData
 * @package Classes
 */
class OutputData
{
    /**
     * @var array
     */
    protected $outputData = [];

    /**
     * HtmlParser constructor.
     * @param string $link
     */
    public function __construct(array $outputData)
    {
        $this->outputData = $outputData;
    }

    /**
     * @return string
     */
    public function getTable()
    {
        $table = "<table border='1'>";

        $table .= "<tr>
            <th>URL adress</th>
            <th>img tags count</th> 
            <th>Depth</th>
            <th>Execution time</th>
        </tr>";

        foreach ($this->outputData as $data) {
            $table .= "<tr>
                <td>" . $data['url'] . "</td>
                <td>" . $data['countImgs'] . "</td>
                <td>" . $data['depth'] . "</td>
                <td>" . $data['time'] . "</td>
            </tr>";
        }

        $table .= "</table>";

        return $table;
    }

    /**
     * @param string $fileName
     * @param string $content
     */
    public function saveToFile(string $fileName, string $content): void
    {
        $myfile = fopen($fileName, "w");
        fwrite($myfile, $content);
        fclose($myfile);
    }

}