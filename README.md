# API of Legends

API of Legends is a simple PHP class to easily access Leagues of Legends data via the Riot API. You might use it to calculate specific statistics or to simply look up your favorite Summoners.

### To Do

- Add Methods for LoL Static Data
- Add Methods for Leagues
- Add Methods for Teams

### Installation

- Download the `api-for-legends.php` file and include it to your web-application.
- Insert your Riot API key to `api-of-legends.php`
- Insert the following lines into your application:

```php
<?php
require_once('/your-path/api-for-legends.php');
$api = new apiOfLegends();
?>
```

### Documentation

API of Legends provides a lot of functions to access League of Legends data. You can find all attributes and methods explained here:

- [Get Summoner Icon](#get-summoner-icon)
- [Get Summoner By Name](#get-summoner-by-name)
- [Get Summoner By ID](#get-summoner-by-id)
- [Get Summoner Masteries](#get-summoner-masteries)
- [Get Match History](#get-match-history)
- [Get Summoner Stats](#get-summoner-stats)

##### Get Summoner Icon
```php
// @params: string $summoner_name
// @return: Summoner Icon URL (String)
$api->getSummonerIcon("MyXoToD");
```

##### Get Summoner By Name
```php
// @params: string $summoner_name
// @return: Summoner data (array)
$api->getSummonerByName("MyXoToD");
```

##### Get Summoner By ID
```php
// @params: int $summoner_id
// @return: Summoner data (array)
$api->getSummonerById(21695378);
```

##### Get Summoner Masteries
```php
// @params: int $summoner_id
// @return: Masteries by given Summoner (array)
$api->getSummonerMasteries(21695378);
```

##### Get Match History
```php
// @params: int $summoner_id
// @return: Match History by given Summoner (array)
$api->getMatchHistory(21695378);
```

##### Get Summoner Stats
```php
// @params: int $summoner_id, [string $option (summary/ranked)]
// @return: Summoner Stats summary or ranked
$api->getSummonerStats(21695378, "summary");
```

### Changelog

- v0.1.0 (17. September 2014)
  - Created API of Legends

### Contribution

Feel free to contribute! If you have any requests or bug reports you can simply send me a pull-request or open an issue. Every contributor will be listed on this page.