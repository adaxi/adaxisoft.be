<?php

class FootballDataScraper {
    private $rawData;
    function __construct($file) {
        $headers = array();
        
        if (($handle = fopen ( $file, "r" )) !== FALSE) {
            if (($data = fgetcsv ( $handle )) !== FALSE) {
                $numberOfFields = count ( $data );
                for($c = 0; $c < $numberOfFields; $c ++) {
                    $headers[$c] = $data[$c];
                }
            }
            while ( ($data = fgetcsv ( $handle )) !== FALSE ) {
                $record = array();
                for($c = 0; $c < $numberOfFields; $c ++) {
                    $record[$headers[$c]] = $data[$c];
                }
                $this->rawData[] = $record;
            }
        }
        
        fclose ( $handle );
    }
    function getRawData() {
        return $this->rawData;
    }
}
