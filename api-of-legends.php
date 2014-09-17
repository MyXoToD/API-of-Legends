<?php
/*

@name: API of Legends
@author: Max Boll (http://myxotod.com)
@twitter: @MyXoToD
@sourcecode: http://github.com/myxotod/api-of-legends
@version: 0.1.0 (17. September 2014)

@description:
This PHP class is made to easily access data from League Of Legends. You can 
use it to generate statistics or to simply look up for your favorite summoners. 
If you have any questions, feel free to contact me via my website or twitter.

@license:
The MIT License (MIT)

Copyright (c) 2014 Max Boll

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*/
class apiOfLegends {
  /* **************** **
  ** ** ATTRIBUTES ** **
  ** **************** */

  // Your Riot API Key
  private $api_key = "";

  // API URLs
  private $api_url_status = "http://status.leagueoflegends.com/";
  private $api_url_static = "https://global.api.pvp.net/api/lol/static-data/{region}/v1.2/";
  private $api_url_1_2 = "https://{region}.api.pvp.net/api/lol/{region}/v1.2/";
  private $api_url_1_3 = "https://{region}.api.pvp.net/api/lol/{region}/v1.3/";
  private $api_url_1_4 = "https://{region}.api.pvp.net/api/lol/{region}/v1.4/";
  private $api_url_2_2 = "https://{region}.api.pvp.net/api/lol/{region}/v2.2/";

  // Options
  private $return_json = false;

  // Regions
  public $regions = array("br", "eune", "euw", "kr", "lan", "las", "na", "oce", "ru", "tr");
  private $region = "na";

  /* ************* **
  ** ** METHODS ** **
  ** ************* */
  public function __construct() {

  }

  public function setRegion($region) {
    $region = strtolower($region);
    if (in_array($region, $this->regions)) {
      $this->region = $region;
    } else {
      $this->region = "na";
    }
  }

  // Get Summoner Icon
  // @params: Summoner name
  // @return: Summoner icon
  public function getSummonerIcon($name) {
    return "http://avatar.leagueoflegends.com/".$this->region."/".$name.".png";
  }

  // Get Summoner By Name
  // @params: Summoner Name (or comma separated list of summoner names)
  // @return: Summoner data
  public function getSummonerByName($name) {
    $call = $this->api_url_1_4."summoner/by-name/".$name;
    return $this->request($call);
  }

  // Get Summoner By ID
  // @params: Summoner ID
  // @return: Summoner data
  public function getSummonerById($id) {
    $call = $this->api_url_1_4."summoner/".$id;
    return $this->request($call);
  }

  // Get Summoner Masteries
  // @params: Summoner ID
  // @return: Summoner Masteries
  public function getSummonerMasteries($id) {
    $call = $this->api_url_1_4."summoner/".$id."/masteries";
    return $this->request($call);
  }

  // Get Match History By Summoner
  // @params: Summoner ID
  // @return: Match history by summoner
  public function getMatchHistory($id) {
    $call = $this->api_url_2_2."matchhistory/".$id;
    return $this->request($call);
  }

  // Get Summoner Stats
  // @params: Summoner ID, [option (summary/ranked)]
  // @return: Summoner statistics
  public function getSummonerStats($id, $option = "summary") {
    $call = $this->api_url_1_3."stats/by-summoner/".$id."/".$option;
    return $this->request($call);
  }

  // Get Recent Games By Summoner
  // @params: Summoner ID
  // @return: Data of recent games by summoner
  public function getRecentGames($id) {
    $call = $this->api_url_1_3."game/by-summoner/".$id."/recent";
    return $this->request($call);
  }

  // Get Champions
  // @params: [ID of champion]
  // @return: A list of all champions (optional: Data of specific champion given his ID)
  public function getChampion($id = null) {
    $opt = "";
    if ($id) {
      $opt = "/".$id;
    }
    $call = $this->api_url_1_2."champion".$opt;
    return $this->request($call);
  }

  // Get Match By ID
  // @params: Match ID
  // @return: Match data
  public function getMatch($id) {
    $call = $this->api_url_2_2."match/".$id;
    return $this->request($call);
  }

  // Get Current LoL Status
  // @params: [region]
  // @return: Current Status of LoL (option: Specified for region)
  public function getStatus($region = false) {
    $opt = "";
    if ($region) {
      $opt = "/".$this->region;
    }
    $call = $this->api_url_status."shards".$opt;
    return $this->request($call);
  }

  // Format API url
  // @params: URL
  // @return: Formatted URL
  private function format_url($url) {
    return str_replace("{region}", $this->region, $url)."?api_key=".$this->api_key;
  }

  // Execute API url and returns result
  // @params: API call url
  // @return: Result of API request
  private function request($call) {
    $call = $this->format_url($call);

    $ch = curl_init($call);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);

    if (!$this->return_json) {
      $result = json_decode($result, true);
    }

    return $result;
  }
}
?>