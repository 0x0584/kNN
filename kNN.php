<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *                        AUTHOR: ANAS RCHID                         *
 *                       CREATED: 11 Dec 2017                        *
 *                                                                   *
 *      DESCRIPTION: Implementation de kNN avec plusieurs facteurs   *
 *                   et chaque facteur a un poids different.         *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

/* all the functions */
require_once 'funcs.php';

$a = array(4,5,3,2,6,3,1);
$b = array(4,5,3,2,6,3,1);

echo implode(",", $a).HR
    .implode(",",quicksort($a, false)).HR
    .implode(",",quicksort($b, true));
echo HR;

echo find_nn(new DataPiece('?', array(11, 45, 0.5, 12)),
             getdata(), 3);
?>
