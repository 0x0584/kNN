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
    $results = quicksort($results, true); /* this is working right now */
    echo implode(BR, $results);
    
    /* TODO: 3. select the k-th nearest-neighbors */
    for ($i = 0; $i < $k; $i++) {
        
    }
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
function quicksort(&$array, $desc = false) {
    /* return if only one element is left */
    if (count($array) <= 1) return  $array;

    $pivot = $array[0];		/* select pivot point at index 0 */
    $left = array();
    $right = array();
    
    /* loop and compare set value to partition */
    for ($i = 1; $i < count($array); $i++) {
        if ($desc ? $array[$i] > $pivot : $array[$i] < $pivot) {
            $left[] = $array[$i];
        } else $right[] = $array[$i];
    }

    # echo HR."left: ".implode(", ", $left)
    #        .BR."pivot: $pivot"
    #        .BR."right: ".implode(", ", $right).HR;

    
    /* merge array left ,pivot, right */
    return array_merge(quicksort($left, $desc),
                       array($pivot),
                       quicksort($right, $desc));
}
?>
