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
require_once 'MenuBar.php';

add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );

add_filter('wp_head','add_view');
function add_view() {
    $api = new FootballData();
    ?>
    <div style="margin-top: 50px;">
        <?php
        $soccerseason = $api->getFixturesForDateRange('2016-10-01',get_the_date('Y-m-d'));
        ?>
    </div>
    <link rel="stylesheet" href="style/style.css">
    <div class="container">
        <table style="margin-top: 20px;">
            <tr>
                <td style="text-align: center;">A DOMICILE</td>
                <td style="text-align: center;">A L'EXTERIEUR</td>
            </tr>
            <?php foreach ($soccerseason->fixtures as $fixture) { ?>
                <tr>
                    <td style="text-align: center;"><?php echo $fixture->homeTeamName; ?><br>
                        <?php echo $fixture->result->goalsHomeTeam; ?>
                    </td>
                    <td style="text-align: center;"><?php echo $fixture->awayTeamName; ?><br>
                        <?php echo $fixture->result->goalsAwayTeam; ?>
                    </td>
                </tr>
            <?php }?>
        </table>
    </div>
    <?php
}
