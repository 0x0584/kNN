<?php
define("BR", "<br />");
define("HR", "<hr />");

class DataPiece {
    public $type; /* its type*/
    public $f_list; /* factors list */

    public function __construct($type, $f_list) {
        $this->$type = $type;
        $this->$f_list = $f_list;
    }
}

function getdata($filename = "data.txt") {
    $data[] = array(); /* as form of array of arrays */
    $fh = fopen($filename, 'r');
  
    $n_factors = (int) fgets($fh);
    
    while ($line = fgets($fh)) {
        $line = explode(':', $line);
        $data[] = $line;
    }

    fclose($fh);
  
    foreach($data as $d) {
        for($i = 0; $i < $n_factors; $i++) {
            print "factor_$i: ".$d[$i]." ";
        }
      
        echo BR;
    }
  
    return $data;
}

function calcdistance() {
      
}

function getNN() {
      
}
?>
