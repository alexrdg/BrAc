<?php
/**
 * Created by PhpStorm.
 * User: alexRdg
 * Date: 17/10/2016
 * Time: 17:39
 */
if (is_admin()) {
    function wpdocs_register_my_custom_menu_page(){
        add_menu_page(
            __( 'Custom Menu Title', 'textdomain' ),
            'BrAc',
            'manage_options',
            'custompage',
            'my_custom_menu_page',
            plugins_url( 'BrAc/img/picture.png' )
        );
    }
    add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );

    /**
     * Display a custom menu page
     */
    function my_custom_menu_page(){ ?>
            <h1>FIRST, ENTER YOUR TOKEN</h1>
            <i>can be generated <a href="http://api.football-data.org/register">here</a></i><br>
            <form method="post" name="" action="" onsubmit="return confirm('Do you really want to update the Token ?');">
                <input type="text" name="Token" placeholder="Enter Token here"></input>
                <button type="submit" name="action">Submit</button>
                <?php if (!empty(get_option('token_id'))){
                echo '<br>Your Token is: '.get_option('token_id');
        }?>
        </form>
        <?php if (!empty(get_option('token_id'))) {?>
            <h1>Choose your league</h1>
            <form method="post" name="leagueForm" action="">
                <select name="selectChoice">
                    <?php
                    $api = new FootballData();
                    // fetch and dump summary data for premier league' season 2015/16
                    $soccerseason = $api->get_soccer_season();
                    foreach ($soccerseason->payload as $fixture) { ?>
                        <option name="id" value="<? echo $fixture->id;?>"><?php echo $fixture->caption; ?></option>
                    <?php } ;?>
                </select>
                <button type="submit" name="action">Submit</button>
            </form>
            <h1>Export results as CSV</h1>
            <form method="post" name="leagueForm" action="">
            <select name="export_league">
                <?php
                $api = new FootballData();
                $soccerseason = $api->get_soccer_season();

                foreach ($soccerseason->payload as $fixture) { ?>
                    <option name="id" value="<? echo $fixture->id;?>"><?php echo $fixture->caption; ?></option>
                <?php } ;?>
            </select>
            <button type="submit" name="action">Export</button>

        <?php
        }
        if(isset($_POST['selectChoice'])) {
            update_option('league_id',$_POST['selectChoice']);
            echo '<h3>League registered !</h3>';
        }
        if(isset($_POST['Token'])) {
            update_option('token_id',$_POST['Token']);
            echo '<h3>Token successfully sent</h3>'.get_option('token_id');
        }
        if (isset($_POST['export_league'])) {;
            $api = new FootballData();
            $fp = fopen('file.csv', 'w');
            $competition = $_POST['export_league'];
            var_dump($competition);
            $competition_select = $api->get_soccerseason_by_id($competition);
            $day_of_game = $competition_select->payload->currentMatchday;
            $fixture_match = $api->get_fixtures_for_export($_POST['export_league'],$day_of_game);
            foreach ($fixture_match->fixtures as $fixture) {
                $date_formated = explode('T', $fixture->date);
                $list = array($fixture->homeTeamName,
                    $fixture->result->goalsHomeTeam,
                    $date_formated[0],
                    $fixture->awayTeamName,
                    $fixture->result->goalsAwayTeam
                    );
                fputcsv($fp, $list);
            }
            fclose($fp);
        }
    }
}

