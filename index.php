<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>API of Legends Test</title>
  </head>
  <body>
    <?php
    require_once('api-of-legends.php');
    $api = new apiOfLegends();

    // foreach ($api->regions as $region) {
    //   $api->region = $region;
    //   echo $region;
    //   echo $api->getSummonerIcon("MyXoToD");
    //   echo "<pre>";
    //   print_r($api->getSummonerByName("MyXoToD"));
    //   echo "</pre>";
    //   echo "<hr />";
    // }

    $api->setRegion("euw");
    echo "<pre>";
    print_r($api->getMatch(1690892005));
    // print_r($api->getRecentGames(21695378));
    // print_r($api->getChampion(266));
    // print_r($api->getStatus());
    // print_r($api->getRankedStats(21695378));
    // print_r($api->getSummaryStats(21695378));
    // print_r($api->getSummonerByName("MyXoToD"));
    // print_r($api->getMatchHistory(21695378));
    // print_r($api->getSummonerById(21695378));
    // print_r($api->getSummonerMasteries(21695378));
    echo "</pre>";
    ?>
  </body>
</html>