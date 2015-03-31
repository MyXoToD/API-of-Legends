<?php
/*

@name: API of Legends
@author: Max Boll (http://myxotod.com)
@twitter: @MyXoToD
@sourcecode: http://github.com/myxotod/api-of-legends
@version: 0.2.0 (31. March 2015)

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
  private $api_key = "YOUR-API-KEY-HERE"; // Insert your API key here

  // API URLs
  private $api_url_status = "http://status.leagueoflegends.com/";
  private $api_url_static = "https://global.api.pvp.net/api/lol/static-data/{region}/v1.2/";
  private $api_url_1_2 = "https://{region}.api.pvp.net/api/lol/{region}/v1.2/";
  private $api_url_1_3 = "https://{region}.api.pvp.net/api/lol/{region}/v1.3/";
  private $api_url_1_4 = "https://{region}.api.pvp.net/api/lol/{region}/v1.4/";
  private $api_url_2_2 = "https://{region}.api.pvp.net/api/lol/{region}/v2.2/";

  // Options
  private $return_json = false; // Return either JSON or decode it into a PHP array
  private $rate_limit_calls = 3000; // Max. number of calls possible
  private $rate_limit_reset = 10; // within this time period in seconds
  private $default_region = "na"; // Default region

  // Regions
  public $regions = array("br", "eune", "euw", "kr", "lan", "las", "na", "oce", "ru", "tr");
  private $region;

  // Other
  private $current_request_count = 0;
  private $total_request_count = 0;
  private $response_code;

  /* ************* **
  ** ** METHODS ** **
  ** ************* */
  public function __construct() {
    $this->setRegion($this->default_region);
  }

  // Get number of all performed requests
  // @params: -
  // @return: Number of requests
  public function getRequestCount() {
    return $this->total_request_count;
  }

  // Set Region for following requests
  // @params: string $region
  // @return: -
  public function setRegion($region) {
    $region = strtolower($region);
    if (in_array($region, $this->regions)) {
      $this->region = $region;
    } else {
      $this->region = "na";
    }
  }

  // Get current region
  // @params: -
  // @return: string $region
  public function getRegion() {
    return $this->region;
  }

  // Get Summoner Icon
  // @params: string $summonerName
  // @return: string $summonerIconUrl
  public function getSummonerIcon($name) {
    return "http://avatar.leagueoflegends.com/".$this->region."/".$name.".png";
  }

  // Get Summoner By Name
  // @params: string $summonerName
  // @return: array $summonerData
  public function getSummonerByName($name) {
    $call = $this->api_url_1_4."summoner/by-name/".$name;
    $output = $this->request($call);
    if (is_array($output)) {
      return $output[key($output)];
    } else {
      return $output;
    }
  }

  // Get Summoner By ID
  // @params: int $summonerId
  // @return: array $summonerData
  public function getSummonerById($id) {
    $call = $this->api_url_1_4."summoner/".$id;
    return $this->request($call);
  }

  // Get Summoner Masteries
  // @params: int $summonerId
  // @return: array $summonerMateries
  public function getSummonerMasteries($id) {
    $call = $this->api_url_1_4."summoner/".$id."/masteries";
    return $this->request($call);
  }

  // Get Match History By Summoner
  // @params: int $summonerId
  // @return: array $summonerMatchHistory
  public function getMatchHistory($id) {
    $call = $this->api_url_2_2."matchhistory/".$id;
    return $this->request($call);
  }

  // Get Summoner Stats
  // @params: int $summonerId, [string $option (summary/ranked)]
  // @return: array $summonerStatistics
  public function getSummonerStats($id, $option = "summary") {
    $call = $this->api_url_1_3."stats/by-summoner/".$id."/".$option;
    return $this->request($call);
  }

  // Get Recent Games By Summoner
  // @params: int $summonerId
  // @return: array $summonerRecentGames
  public function getRecentGames($id) {
    $call = $this->api_url_1_3."game/by-summoner/".$id."/recent";
    $output = $this->request($call);
    if (is_array($output)) {
      return $output['games'];
    } else {
      return $output;
    }
  }

  // Get Champions
  // @params: [int $championId]
  // @return: array $allChampionsData / array $championData
  public function getChampion($id = null) {
    $opt = "";
    if ($id) {
      $opt = "/".$id;
    }
    $call = $this->api_url_1_2."champion".$opt;
    return $this->request($call);
  }

  // Get champion data
  // @params: int $championId, [string $option]
  // @return: array $championInfos
  public function getChampionData($id, $opt = "all") {
    $opt = strtolower($opt);
    switch ($opt) {
      case "all":
      case "allytips":
      case "altimages":
      case "blurb":
      case "enemytips":
      case "image":
      case "info":
      case "lore":
      case "partype":
      case "passive":
      case "recommended":
      case "skins":
      case "spells":
      case "stats":
      case "tags":
        $opt = $opt;
      break;
      default:
        $opt = "all";
      break;
    }
    $call = $this->api_url_static."champion/".$id;
    return $this->request($call, "&champData=".$opt, true);
  }

  // Get Champion Image
  // @params: int $championId
  // @return: string $championIconUrl
  public function getChampionImage($id, $opt = "icon") {
    $image = $this->getChampionData($id, "image");
    switch ($opt) {
      case "splash":
        return "http://ddragon.leagueoflegends.com/cdn/img/champion/splash/".$image['key']."_0.jpg";
      break;
      case "loading":
        return "http://ddragon.leagueoflegends.com/cdn/img/champion/loading/".$image['key']."_0.jpg";
      break;
      case "icon":
      default:
        return "http://ddragon.leagueoflegends.com/cdn/4.16.1/img/champion/".$image['key'].".png";
      break;
    }
  }

  // Get Match By ID
  // @params: int $matchId
  // @return: array $matchData
  public function getMatch($id) {
    $call = $this->api_url_2_2."match/".$id;
    return $this->request($call);
  }

  // Calculate KDA
  // @params: int $kills, int $deaths, int $assists
  // @return: string $kda (0/0/0 + Ratio)
  public function getKDA($stats) {
    $kills = 0;
    $deaths = 0;
    $assists = 0;
    if (isset($stats['championsKilled'])) {
      $kills = $stats['championsKilled'];
    }
    if (isset($stats['numDeaths'])) {
      $deaths = $stats['numDeaths'];
    }
    if (isset($stats['assists'])) {
      $assists = $stats['assists'];
    }
    $kda = $kills."/".$deaths."/".$assists;
    if ($deaths == 0) {
      return $kda." (PERFECT!)";
    } else {
      $ratio = round(($kills + $assists) / $deaths, 2);
      return $kda." (Ratio: ".$ratio.":1)";
    }
  }

  // Format Match Time
  // @params: int $matchTime
  // @return: string $formattedMatchTime (D.M.Y, H:M:S)
  public function formatMatchTime($time) {
    return date("d.m.Y, H:i:s", $time / 1000);
  }

  // Format Match Duration
  // @params: int $duration (seconds)
  // @return: string $formattedTime (H:M:S or M:S)
  public function formatMatchDuration($duration) {
    $minutes = floor($duration / 60);
    $seconds = floor($duration - ($minutes * 60));
    if (strlen($seconds) == 1) {
      $seconds = "0".$seconds;
    }
    return $minutes.":".$seconds;
  }

  // Get Match Duration in Minutes
  // @params: int $duration (seconds)
  // @return: int $minutes
  public function getMatchDurationInMinutes($duration) {
    return floor($duration / 60);
  }

  // Get Current LoL Status
  // @params: [string $region]
  // @return: Current Status of LoL (option: Specified for region)
  public function getStatus($region = false) {
    $opt = "";
    if ($region) {
      $opt = "/".$this->region;
    }
    $call = $this->api_url_status."shards".$opt;
    return $this->request($call, "", true);
  }

  // Get the response code of the most recent request
  // @params: -
  // @return: int $responseCode
  public function getLastResponseCode() {
    return $this->response_code;
  }

  // Format API url
  // @params: string $url
  // @return: Formatted URL
  private function format_url($url) {
    return str_replace("{region}", $this->region, $url)."?api_key=".$this->api_key;
  }

  // Check rate limit
  // @params: -
  // @return: -
  private function checkRateLimit() {
    if ($this->current_request_count >= $this->rate_limit_calls) {
      sleep($this->rate_limit_reset + 1);
      $this->current_request_count = 0;
    }
  }

  // Execute API url and returns result
  // @params: string $callUrl, [string $url_parameters]
  // @return: array $result / json $result
  private function request($call, $params = "", $static = false) {
    if (!$static) {
      $this->checkRateLimit();
      $this->current_request_count++;
    }
    $this->total_request_count++;

    $call = $this->format_url($call).$params;

    $ch = curl_init($call);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    $this->response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (!$this->return_json) {
      $result = json_decode($result, true);
    }

    if ($this->getLastResponseCode() != 200) {
      // echo "The API seems to have some headache at the moment. Please try again later.";
      echo "";
    } else {
      return $result;
    }
  }

}
?>