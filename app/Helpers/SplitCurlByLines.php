<?php
namespace App\Helpers;

class SplitCurlByLines{

    public function curlCallback($curl, $data) {

        $this->currentLine .= $data;
        $lines = explode("\n", $this->currentLine);
        // The last line could be unfinished. We should not
        // proccess it yet.
        $numLines = count($lines) - 1;
        $this->currentLine = $lines[$numLines]; // Save for the next callback.

        for ($i = 0; $i < $numLines; ++$i) {
            $this->processLine($lines[$i]); // Do whatever you want
            ++$this->totalLineCount; // Statistics.
            $this->totalLength += strlen($lines[$i]) + 1;
        }
        return strlen($data); // Ask curl for more data (!= value will stop).

    }

    public function processLine($str) {
        // Do what ever you want (split CSV, ...).
        echo $str . "\n";
    }

    public $currentLine = '';
    public $totalLineCount = 0;
    public $totalLength = 0;

}