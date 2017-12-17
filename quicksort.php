<?php
/** 
 * QuickSort Algorithm
 * @todo        deal with any associative array
 * @description the implementation of the quick sort algorithm. which
 *              looks like the following: 
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
 * source: Wikipedia
 *
 * @author      0x0584 <rchid.anas@gmail.com>
 * @param       $array the array to sort
 * @param       $desc boolean to indicate the sort order. default is false
 * @return      array of data pieces
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