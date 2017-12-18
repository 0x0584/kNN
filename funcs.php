<?php
require_once 'global.php';
    
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
     * $__types is an array that contains all the types
     *
     * @description each time a new data type is created, if it has a new
     *              type, we append its type to this array.
     * @author      0x0584 <rchid.anas@gmail.com>
     */
    public static $__types = null;

    /**
     * $thisisdumb is a hack to solve an array problem i have faced
     *
     * @description this is a dumb hack, because you can't, really, 
     *              declare a variable in php and i don't know why
     *              when you initialize an array the first the first
     *              element is always null. i've searched a lot.
     *              i would figure this out later! now, let's stick
     *              with this dumb solution. Oh Denis! look what they
     *              have done! 
     * @author      0x0584 <rchid.anas@gmail.com>
     */
    private static $thisisdumb = null;
    
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
     * @staticvar   $__types array of data types 
     * @param       string $type the type of the datapiece
     * @param       array $f_list list of factors 
     */
    public function __construct($type, $f_list) {
        /* add new types to the list */
        if (self::$__types === null) {
            self::$__types = array($type);
            self::$thisisdumb = true;
        } else {
            foreach (self::$__types as $t) {
                if (strcmp($t, $type)) {
                    self::$__types[] = $type;
                    break;
                }
            }
            
            self::$__types = array_unique(self::$__types);
            uasort(self::$__types, 'strcmp');

            /* remove the first element of the array */
            if (self::$thisisdumb === true) {
                self::$__types = array_splice(self::$__types, 1);
                self::$thisisdumb = false;
            }
        }

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

    public function getTypesList() {
        return self::$__types;
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
 * Compute the distance between the $element and all the other 
 * elements in the $data set
 *
 * @description this function calls `calcdistance` to compute 
 *              the distance between elements
 * @author      0x0584 <rchid.anas@gmail.com>
 * @param       $element the target Datapiece
 * @param       $data array of all the Datapieces
 * @return      $results array of types and thier distances 
 */
function compute_results($element, $data) {
    /* 1. find the distance between the element and everything else */
    $results = null;

    foreach ($data as $item) {
        $results[] = array("type" => $item->type,
                           "distance" => calcdistance($item->f_list,
                                                      $element->f_list));
    }

    /* 2. sort the results */
    usort($results, 'descendant');

    return $results;
}

/** 
 * List neighbors based on the $results
 *
 * @description get the top $k-elements from the results
 * @author      0x0584 <rchid.anas@gmail.com>
 * @param       $results array of distances 
 * @param       $k the target number of elements to pick from $results
 * @return      $list of neighbors
 */
function list_neighbors($results, $k) {
    $list = null;

    for ($i = 0; $i < $k; $i++) {
        $list[] = $results[$i]['type'];
        # echo $results[$i]['type']." "
        #     .$results[$i]['distance'].BR;
    }

    return $list;
}

/** 
 * Find the $k-th Nearest Neighbor to the passed $element in $data
 * 
 * @description compute the distances using `calcdistance`. then 
 *              take the closest $k-th elements and find the $type
 *              match. return the $type.
 * @author      0x0584 <rchid.anas@gmail.com>
 * @staticvar   $__types array of types from class DataPiece 
 * @param       $element is DataPiece
 * @param       $data is an array of DataPieces 
 * @param       $k is the number of the nearest neighbors to take
 *              from $data
 * @return      the guessed $type of the element 
 */
function find_nn($element, $data, $k = 3) {
    /* 1. calculate the distance between the element 
     *    and the dataset */
    $results = compute_results($element, $data);

    /* 2. select the k-th nearest-neighbors */
    $list = list_neighbors($results, $k);
    
    /* 2.1 count the elements occurrence */
    $type_count = array_fill(0, count(DataPiece::$__types), 0);

    /* 2.2 compare elements and found types */
    foreach ($list as $element) {
        $index_type = 0; /* index of type */
        /* loop through types */
        foreach (DataPiece::$__types as $t) {
            if (!strcmp($element, $t)) {
                /* update type's count */
                $type_count[$type_index]++;
                /* take a */ break;
            }
            $index_type++;
        }
    }
    
    /* 3. select the biggest element's index */
    $index_max = 0;
    for ($i = 0; $i < count($list); $i++) {
        if (type_count[$i] > type_count[$index_max]) {
            $index_max = $i;
        }
    }

    /* 4. we found it! */
    $type = DataPiece::$__types[$index_max];
    
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
