<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *                		  AUTHOR: ANAS RCHID                         *
 *                       CREATED: 11 Dec 2017                        *
 *                                                                   *
 *      DESCRIPTION: Implementation de kNN avec plusieurs facteurs   *
 *                   et chaque facteur a un poids different.         *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

/* all the functions */
require_once 'funcs.php';

echo findNearestNeighbor(new DataPiece('?', array(11, 45, 0.5, 12)),
                         getdata(), 3);
?>
