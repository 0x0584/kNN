<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *                        AUTHOR: ANAS RCHID                           *
 *                       CREATED: 11 Dec 2017                          *
 *                        UPDATE: 18 Dec 2017                            *
 *                                                                     * 
 *      DESCRIPTION: Implementation de kNN avec plusieurs facteurs     *
 *                   et chaque facteur a un poids different.           *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

require_once 'funcs.php';

/* le nombre des elements proche */
$k = 3;
/* les donnees */
$dataset = getdata();
/* l'element */
$mystery_element = new DataPiece('?', array(11, 2));
/* les resultat des calcules */
$results = compute_results($mystery_element, $dataset);
/* list des elements les plus proches dans la base de donnee */
$neighbors = list_neighbors($results, $k);
/* on deduit le type */
$type = find_nn($mystery_element, $dataset, $k);

/* testing */
echo "liste des elements".BR.BR.implode(BR, $dataset).BR;
echo HR;
echo "l'element que nous voulons connaitre son type $mystery_element".BR;

echo HR."les results des calcules".BR.BR;
foreach ($results as $r) {
    echo $r['type']." | ".$r['distance'].BR;
}
echo HR;
echo "liste des $k-neighbors les plus proche: ".implode(', ', $neighbors);
echo HR;
echo "son type est fort probable: ".$type;
echo HR;
?>
