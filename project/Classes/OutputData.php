<?php

namespace Classes;

/**
 * Class OutputData
 *
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
     * create html table for output data
     *
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
     * save output data to file
     *
     * @param string $fileName
     * @param string $content
     */
    public function saveToFile(string $fileName, string $content): void
    {
        $myfile = fopen($fileName, "w");
        fwrite($myfile, $content);
        fclose($myfile);
    }

    public function sortingByImgCount(): void
    {
        usort($this->outputData, ['Classes\OutputData', 'sorting']);
    }

    /**
     * @param $a
     * @param $b
     * @return int
     */
    protected function sorting($a, $b): int
    {
        if ($a['countImgs'] == $b['countImgs']) {
            return 0;
        }
        return ($a['countImgs'] < $b['countImgs']) ? -1 : 1;
    }
}