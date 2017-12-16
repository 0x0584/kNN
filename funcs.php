<?php
define("BR", "<br />");
define("HR", "<hr />");

/**
 * @author Anas Rchid (0x0584)
 * @class this class represents the data. each piece consists a type
 *  and a list of factors.
 *
 */
class DataPiece {
    public $type = null; /* its type*/
    public $f_list = null; /* factors list */
    
    public function __construct($type, $f_list) {
        $this->type = $type;
        $this->f_list = $f_list;
    }
    
    public function __toString() {
        $str = $this->type." | ";

        foreach ($this->f_list as $f) {
            $str = $str.$f." ";
        }

        return $str;
    }
    
}

/** 
 * @author Anas Rchid (0x0584)
 * @description get data from a file into an array of data pieces
 * @return array of data pieces
 */
function getdata($filename = "data.txt") {
    $fh = fopen($filename, 'r');
    $data[] = null; /* initialize the first element as null 
                     * because you can't declare variables 
                     * without initializing them. how dumb! 
                     * */

    while ($line = fgets($fh)) {
        $line = explode(':', $line);
        $data[] = new DataPiece($line[0],
                                array_slice($line, 1));
    } // 
    
    fclose($fh);

    /* remove the first, null, element in the array */ 
    return array_splice($data, 1);
}

/** 
 * @author Anas Rchid (0x0584)
 * @description get data from a file into an array of data pieces
 * @return array of data pieces
 */
function calcdistance($x, $y) {
    if (count($x) != count($y)) {
        echo "this would not work properly";
    }
    
    $squrs = 0;

    for ($i = 0; $i < count($x); $i++) {
        $squrs += pow($x[$i] - $y[$i], 2); 
    }
    
    return sqrt($squrs);
}

/** 
 * @author Anas Rchid (0x0584)
 * @description get data from a file into an array of data pieces
 * @return array of data pieces
 */
function findNearestNeighbor($element, $data, $k) {
    $results = null;
    
    /* 1. find the distance between the element and everything else */
    foreach ($data as $item) {
        $results[] = calcdistance($item->f_list,
                                  $element->f_list);
    }

    /* 2. sort the results */
    quicksort($results);    
}

/** 
 * @author Anas Rchid (0x0584)
 * @description the implementation of the famous quick sort algorithm
 * (source Wikipedia)
 *
 * algorithm quicksort(A, lo, hi) is
 *   if lo < hi then
 *       p := partition(A, lo, hi)
 *       quicksort(A, lo, p - 1 )
 *       quicksort(A, p + 1, hi)
 *
 * algorithm partition(A, lo, hi) is
 *   pivot := A[hi]
 *   i := lo - 1    
 *   for j := lo to hi - 1 do
 *       if A[j] < pivot then
 *           i := i + 1
 *           swap A[i] with A[j]
 *   if A[hi] < A[i + 1] then
 *       swap A[i + 1] with A[hi]
 *   return i + 1
 *
 * @return array of data pieces
 */
function quicksort(&$array) {
    __quicksort($array, 0, count($array) - 1);
}

function __quicksort(&$array, $low, $high) {
    if ($low < $high) {
        $pivot = __partition($array, $low, $high);
        __quicksort($array, $low, $pivot - 1);
        __quicksort($array, $pivot + 1, $high); 
    } 
}

function __partition(&$array, $low, $high) {
    $pivot = $array[$high];
    $index = ($low - 1);

    for ($i = $low; $i < ($high - 1); $i++) {
        if ($array[$i] < $pivot) {
            $i++;
            /* swapping elements at i and ind*/ 
            list($array[$i], $array[$index]) = array($array[$index],
                                                     $array[$i]);
        }
    }

    if ($array[$high] < $array[$index + 1]) {
        list($array[$high], $array[$index]) = array($array[$index],
                                                     $array[$high]);
    }
    
    return ($index + 1);
}
?>
