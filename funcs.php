<?php
define("BR", "<br />");
define("HR", "<hr />");

include 'quicksort.php';

/** 
 * DataPiece Class
 *
 * @package     kNN
 * @class       DataPiece
 * @desciption  this is the fundamental class, is consists of a $type
 *              and a factor list, $f_list
 * @author      0x0584 <rchid.anas@gmail.com>
 */
class DataPiece {
    /**
     * $type the datapiece's `Type`
     * 
     * @description this could be `AA` or `Romance`
     * @author      0x0584 <rchid.anas@gmail.com>
     */
    public $type = null; 
    
    /** 
     * $f_list the datapiece's `Factors list` 
     *
     * @description the number of the factors could be anything, but it 
     *              must be the same for all other datapieces. 
     * @author      0x0584 <rchid.anas@gmail.com>
     */
    public $f_list = null;

    /**
     * DataPiece constructor
     * 
     * @description initialize the object's 
     * @author      0x0584 <rchid.anas@gmail.com> 
     * @param       string $type the type of the datapiece
     * @param       array $f_list list of factors 
     */
    public function __construct($type, $f_list) {
        $this->type = $type;
        $this->f_list = $f_list;
    }

    /**
     * DataPiece toString 
     * 
     * @description this return the object as the following form 
     *              `AA | 52.10 56.23 11.00 2.37`
     * @author 0x0584 <rchid.anas@gmail.com>
     * @return      string $str the object information as a String
     */
    public function __toString() {
        $str = $this->type." | ";

        foreach ($this->f_list as $f) {
            $str = $str.$f." ";
        }

        return $str;
    }   
}

/** 
 * Get data from the $filename 
 * @description get data from a file, named $filename, and put it into
 *              an array of DataPieces
 * @author      0x0584 <rchid.anas@gmail.com>
 * @param       $filename as a string, default is `data.txt`
 * @return      array of DataPiece's
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
    }
    
    fclose($fh);

    /* remove the first, null, element in the array */ 
    return array_splice($data, 1);
}

/** 
 * Calculate the distance between $x and $y, which are array of factors,
 * using the Ecludian methode
 *
 * @description get data from a file into an array of data pieces
 * @author      0x0584 <rchid.anas@gmail.com>
 * @param       $x first array of factors list
 * @param       $y second array of factors list
 * @return      $squrs somme of the square root of all the 
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
 * Find the $k-th Nearest Neighbor to the passed $element in $data
 * 
 * @description compute the distances using `calcdistance`. then 
 *              take the closest $k-th elements and find the $type
 *              match. return the $type.
 * @author      0x0584 <rchid.anas@gmail.com>
 * @param       $element is DataPiece
 * @param       $data is an array of DataPieces 
 * @param       $k is the number of the nearest neighbors to take
 *              from $data
 * @return      the guessed $type of the element 
 */
function find_nn($element, $data, $k = 3) {
    $results = null;
    $type = "";
    
    /* 1. find the distance between the element and everything else */
    foreach ($data as $item) {
        $results[] = array("type" => $item->type,
                           "distance" => calcdistance($item->f_list,
                                              $element->f_list));
    }

    /* 2. sort the results */
    # $results = quicksort($results, true); /* this is working right now */

    usort($results, 'descendant');

    foreach ($results as $key => $value) {
        echo BR.$key." <+ ".$value['distance']." - ".$value['type'];
    }

    $list = null;
    
    /* TODO: 3. select the k-th nearest-neighbors */
    for ($i = 0; $i < $k; $i++) {
        $list[] = $results[$i]['type'];
    }

    foreach ($list as $foo) {
        echo BR.$foo;
    }
    
    return $type;
}

/** 
 * This function is used with PHP's built-in sort function `usort` 
 * 
 * @description compare $a and $b to achieve descendant order, which 
 *              both are associative array, members are string $type
 *              and a float $distance 
 * @author      0x0584 <rchid.anas@gmail.com>
 * @param       $a first element to compare
 * @param       $b second element to compare 
 * @return      true if $b's distance is bigger than $a's distance
 */
function descendant($a, $b) {
    return $a['distance'] < $b['distance'];
}
?>
