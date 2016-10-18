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
require_once 'SoccerSeason.php';
require_once 'Team.php';
require_once 'FootballData.php';
require_once 'MenuBar.php';

add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );

add_filter('wp_head','add_view');
function add_view() {
    $api = new FootballData();
    ?>
    <div style="margin-top: 50px;">
        <?php
            $competition = get_option('league_id');
            $competitionSelect = $api->getSoccerseasonById($competition);
            $dayOfGame = $competitionSelect->payload->currentMatchday;
        $fixtureMatch = $api->getFixturesForLeagueAndMatch($dayOfGame);
        ?>
    </div>
    <link rel="stylesheet" href="style/style.css">
    <div class="container">
        <table style="margin-top: 20px;">
            <tr>
                <td style="text-align: center;">Home</td>
                <td style="text-align: center;">Date</td>
                <td style="text-align: center;">Visitors</td>
            </tr>
            <?php foreach ($fixtureMatch->fixtures as $fixture) {
                $dateFormated = explode('T', $fixture->date);?>
                <tr>
                    <td style="text-align: center;"><?= $fixture->homeTeamName; ?><br>
                        <?php echo $fixture->result->goalsHomeTeam; ?>
                    </td>
                    <td style="text-align: center;"><?= $dateFormated[0]; ?></td>
                    <td style="text-align: center;"><?= $fixture->awayTeamName; ?><br>
                        <?php echo $fixture->result->goalsAwayTeam; ?>
                    </td>
                </tr>
            <?php }?>
        </table>
    </div>
    <?php
}

