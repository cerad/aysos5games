<?php
/* =================================================================
 * This was copied from the national games 2012 site
 * But somewhat to my dismay, as of S2.3.RC1 adding the php engine breaks namespaced paths
 */
// Start with a virtual file
$fp = fopen('php://temp','r+');

// Header
$row = array(
    "Game","Date","DOW","Time","Field",
    "Pool","Home Team","Away Team",
    "Referee","Asst Referee 1","Asst Referee 2",
);
fputcsv($fp,$row);

// Games is passed in
foreach($games as $game)
{
    // Date/Time
    $dt   = $game->getDtBeg();
    $dow  = $dt->format('D');
    $date = $dt->format('M d');
    $time = $dt->format('g:i A');
            
    // Build up row
    $row = array();
    $row[] = $game->getNum();
    $row[] = $date;
    $row[] = $dow;
    $row[] = $time;
    $row[] = $game->getField()->getName();
    
    $row[] = $game->getPool();
    $row[] = $game->getHomeTeam()->getTeam()->getDesc();
    $row[] = $game->getAwayTeam()->getTeam()->getDesc();
    
    foreach($game->getEventPersonsSorted() as $gamePersonRel)
    {
        $row[] = $gamePersonRel->getPersonz()->getPersonName();
    }
    fputcsv($fp,$row);
}
// Return the content
rewind($fp);
$csv = stream_get_contents($fp);
fclose($fp);
echo $csv;
return;

?>