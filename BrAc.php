<?php
/*
Plugin Name: BrAC
Plugin URI: https://github.com/alexrdg/BrAc
Description: Plugin for Soccer results in wordpress
Version: 0.1
Author: alexRdg
Author URI: http://www.alexrdg.com/
License:
*/
require_once 'Team.php';
require_once 'FootballData.php';

$api = new FootballData();
// fetch and dump summary data for premier league' season 2015/16
$soccerseason = $api->getSoccerseasonById(398);

foreach ($soccerseason->getFixturesByMatchday(1) as $fixture) { ?>
    <tr>
        <td><?php echo $fixture->homeTeamName; ?></td>
        <td>-</td>
        <td><?php echo $fixture->awayTeamName; ?></td>
        <td><?php echo $fixture->result->goalsHomeTeam; ?></td>
        <td>:</td>
        <td><?php echo $fixture->result->goalsAwayTeam; ?></td>
    </tr>
<?php }

