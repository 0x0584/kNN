<?php
define("BR", "<br />");
define("HR", "<hr />");

class DataPiece {
    public $type = null; /* its type*/
    public $f_list = null; /* factors list */
    
    public function __construct($f_str) {
        $this->type = $f_str[0];
        $this->f_list = array_slice($f_str, 1);
    }
    
    public function __toString() {
        $str = $this->type." | ";

        foreach($this->f_list as $f) {
            $str = $str.$f." ";
        }

        return $str;
    }
}

function getdata($filename = "data.txt") {
    $fh = fopen($filename, 'r');
    $data[] = array(); /* as form of array of arrays */

    while ($line = fgets($fh)) {
        $data[] = $dp = new DataPiece(explode(':', $line));
        echo $dp.BR;
    }
    
    fclose($fh);

    return $data;
}

function calcdistance() {
      
}

function getNN() {
      
}
?>
