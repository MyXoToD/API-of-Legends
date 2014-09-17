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

```
&lt;?php
require_once('/your-path/api-for-legends.php');
$api = new apiOfLegends();
?&gt;
```

### Documentation

API of Legends provides a lot of functions to access League of Legends data. You can find all attributes and methods explained here:

**Get Summoner Icon**<br />
@params: Summoner ID<br />
@return: Summoner Icon URL

More comming soon...

### Changelog

- v0.1.0 (17. September 2014)
  - Created API of Legends

### Contribution

Feel free to contribute! If you have any requests or bug reports you can simply send me a pull-request or open an issue. Every contributor will be listed on this page.