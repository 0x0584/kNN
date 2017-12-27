<?php
/**
 * Functions to handle pattern recognition using k-NN algorithm 
 *
 * @package     kNN
 * @description this file has functions like `getdata`, `list_neighbors` 
 *              up to `find_nn` which returns the guessed type.
 * @author      0x0584 <rchid.anas@gmail.com>
 */

/** 
 * Global variables and definitions  
 *
 * @package     globals
 * @desciption  this contains things BR and HR
 * @author      0x0584 <rchid.anas@gmail.com>
 */
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
     * An array that contains all the types
     *
     * @package     kNN
     * @description each time a new data type is created, if it has a new
     *              type, we append its type to this array.
     * @author      0x0584 <rchid.anas@gmail.com>
     * @type        array of string
     */
    public static $__types = null;

    /**
     * A hack to solve an array problem i have faced
     *
     * @package     kNN
     * @description Oh Denis! look what they
     *              have done! 
     * @author      0x0584 <rchid.anas@gmail.com>
     */
    private static $thisisdumb = null;

    /**
     * Is, as its name indicates, what it is.
     *
     * @package     kNN
     * @description reference to unknown types
     * @author      0x0584 <rchid.anas@gmail.com>
     */
    private static $unknowntype = '?';
    
    /**
     * The DataPiece's type
     * 
     * @package     kNN
     * @description this could be `AA` or `Romance`
     * @author      0x0584 <rchid.anas@gmail.com>
     */
    public $type = null;
    
    /** 
     * The DataPiece's factors list 
     *
     * @package     kNN
     * @description the number of the factors could be anything, but it 
     *              must be the same for all other datapieces. 
     * @author      0x0584 <rchid.anas@gmail.com>
     */
    public $f_list = null;

    /**
     * DataPiece constructor taking type and array of factors
     * 
     * @package     kNN
     * @description initialize the object's 
     * @author      0x0584 <rchid.anas@gmail.com> 
     * @staticvar   array $__types of all data types 
     * @param       string $type the type of the datapiece
     * @param       array $f_list list of factors 
     */
    public function __construct($type, $f_list) {
        /* add new types to the list */
        if (!strcmp($type, self::$unknowntype)) {
            goto THERE;
        }
        
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

        THERE:
        
        $this->type = $type;
        $this->f_list = $f_list;
    }

    /**
     * DataPiece toString 
     * 
     * @package     kNN
     * @description this return the object as the following form 
     *              `AA | 52.10 56.23 11.00 2.37`
     * @author 0x0584 <rchid.anas@gmail.com>
     * @return      string DataPiece's information as a String
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
 * Get data from the a certain file 
 *
 * @package     kNN
 * @description get data from a file, and put it into
 *              an array of DataPieces
 * @author      0x0584 <rchid.anas@gmail.com>
 * @param       string $filename, indeed its path
 * @return      array $data list of DataPieces in $filename
 */
function getdata($filename = 'data.txt') {
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
 * Calculate the distance between $facts0 and $facts1, which are
 * array of factors, using the Ecludian methode
 *
 * @package     kNN
 * @description get data from a file into an array of data pieces
 * @author      0x0584 <rchid.anas@gmail.com>
 * @param       array $facts0 as a factors list
 * @param       array $facts1 as a factors list
 * @return      float $squrs distance between $facts0 and $facts1
 */
function calcdistance($facts0, $facts1) {
    /* factors count */
    $cf0 = count($facts0);
    $cf1 = count($facts1);

    /* take the lowest factor count as the limit */
    $limit = (($cf0 != $cf1) ? ($cf0 > $cf1 ? $cf1 : $cf0) : $cf0);

    $squrs = 0; /* some of the squares */

    for ($i = 0; $i < $limit; $i++) {
        $squrs += pow($facts0[$i] - $facts1[$i], 2); 
    }
    
    return sqrt($squrs);
}

/** 
 * Compute the distance between the $element and all the other 
 * elements in the $data set
 *
 * @package     kNN
 * @description this function calls `calcdistance` to compute 
 *              the distance between elements
 * @author      0x0584 <rchid.anas@gmail.com>
 * @param       DataPiece $element which is the target Datapiece
 * @param       array $data of all the Datapieces
 * @return      array $results of types and thier distances 
 */
function compute_results($element, $data) {
    /* 1. find the distance between the element and everything else */
    $results = null;

    foreach ($data as $item) {
        $ifacts = $item->f_list;
        $efacts = $element->f_list;
        $results[] = array(
            "type" => $item->type,
            "distance" => calcdistance($ifacts, $efacts)
        );
    }

    /* 2. sort the results */
    usort($results, 'descendant');

    return $results;
}

/** 
 * List neighbors based on the $results
 *
 * @package     kNN
 * @description get the top $k-elements from the results
 * @author      0x0584 <rchid.anas@gmail.com>
 * @param       array $results of distances 
 * @param       array $k the target number of elements to pick from $results
 * @return      array $list of neighbors
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
 * @package     kNN
 * @description compute the distances using `calcdistance`. then 
 *              take the closest $k-th elements and find the $type
 *              match. return the $type.
 * @author      0x0584 <rchid.anas@gmail.com>
 * @staticvar   array $__types from the DataPiece class 
 * @param       DataPiece $element which is the target
 * @param       array $data of DataPieces 
 * @param       number $k of the nearest neighbors to take from $data
 * @return      string $type which is the guessed of the $element 
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
                $type_count[$index_type]++;
                break;
            } else $index_type++;
        }
    }

    /* 3.1. select the biggest element's index */
    $index_max = 0;
    for ($i = 0; $i < count($type_count); $i++) {
        if ($type_count[$i] > $type_count[$index_max]) {
            $index_max = $i;
        }
    }

    /* 3.2 select elements with equal value */
    $max_value = $type_count[$index_max];
    $index_probable = [];
    
    for ($i = 0; $i < count($type_count); $i++) {
        if ($type_count[$i] === $max_value) {
            $index_probable[] = $i;
        }
    }
    
    /* 4. we found it! */
    $type = [];
    $i = 0;
    foreach (Datapiece::$__types as $key => $value) {
        foreach ($index_probable as $pb) {
            if ($i === $pb) {
                $type[] = $value;
                break;              // !?!
            }
        }
        
        $i++;
    }

    return $type;
}

/** 
 * This function is used with PHP's built-in sort function `usort` 
 * 
 * @package     kNN
 * @description compare $a and $b to achieve descendant order, which 
 *              both are associative array, members are string $type
 *              and a float $distance 
 * @author      0x0584 <rchid.anas@gmail.com>
 * @param       array $a containing type and distance
 * @param       array $b containing type and distance
 * @return      boolean; true if $b's distance is bigger than $a's distance
 */
function descendant($a, $b) {
    return $a['distance'] < $b['distance'];
}
?>
