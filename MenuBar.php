<?php
/**
 * Created by PhpStorm.
 * User: alexRdg
 * Date: 17/10/2016
 * Time: 17:39
 */
add_action('init','init_translation');

function init_translation() {
    load_plugin_textdomain('BrAc',false,basename(dirname(__FILE__)).'/languages');
}

if (is_admin()) {
    add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );
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

    if(isset($_POST['selectChoice'])) {
        update_option('league_id',$_POST['selectChoice']);
        _e("<h1>"."League registered !"."</h1>","BrAc");
    }

    if(isset($_POST['Token'])) {
        update_option('token_id',$_POST['Token']);
        _e('Token registered !','BrAc');
    }



    if (isset($_POST['export_league'])) {
        $i=0;
        $post_leagues = $_POST['export_league'];
        $api = new FootballData();

        $files_to_zip = array();

        foreach ( $post_leagues as $post_league ) {
            $fp = fopen($i.'file.csv', 'w');
            $files_to_zip[] = $i.'file.csv';
            $competition_select = $api->get_soccerseason_by_id($post_league);
            $day_of_game = $competition_select->payload->currentMatchday;
            $fixture_match = $api->get_fixtures_for_export($_POST['export_league'][$i], $day_of_game);

            foreach ($fixture_match->fixtures as $fixture) {
                $date_formated = explode('T', $fixture->date);
                $list = array(
                    $fixture->homeTeamName,
                    $fixture->result->goalsHomeTeam,
                    $date_formated[0],
                    $fixture->awayTeamName,
                    $fixture->result->goalsAwayTeam
                );
                fputcsv($fp, $list);
            }
            fclose($fp);
            $i ++;
        }
        $zip = new ZipArchive;
        $filename = 'export.zip';

        $res = $zip->open($filename,ZipArchive::CREATE);
        if ($res === TRUE) {
            foreach ($files_to_zip as $f){
                $zip->addFile($f);
            }
            $zip->close();
            header("Content-Type: application/zip");
            header("Content-Disposition: attachment; filename=BrAc_Leagues_Export.zip");
            header("Content-length: ".filesize($filename));
            print readfile($filename);

            foreach ($files_to_zip as $f){
                unlink($f);
            }
            unlink($filename);
        }

    }

    /**
     * Display a custom menu page
     */
    function my_custom_menu_page(){ ?>
            <h1><?php _e('FIRST ENTER YOUR TOKEN','BrAc'); ?></h1>
            <i><?php _e('can be generated','BrAc'); ?> <a href="http://api.football-data.org/register"><?php _e('here','BrAc'); ?></a></i><br>
            <form method="post" name="" action="" onsubmit="return confirm(<?php _e('Do you really want to update the Token ?','BrAc')?>);">
                <input type="text" name="Token" placeholder="<?php _e('Enter Token here','BrAc'); ?>"></input>
                <button type="submit" name="action"><?php _e('Submit','BrAc'); ?></button>
                <?php if (!empty(get_option('token_id'))){ ?> <br><?php
                echo _e('Your Token is: ','BrAc').get_option('token_id');
        ?>
        </form>
            <?php if (!empty(get_option('token_id'))) {?>
                <h1 style="margin-top: 150px;"><?php _e('Choose your league','BrAc');?></h1>
                <form method="post" name="leagueForm" action="">
                    <select name="selectChoice">
                        <?php
                        $api = new FootballData();
                        // fetch and dump summary data for premier league' season 2015/16
                        $soccerseason = $api->get_soccer_season();
                        foreach ($soccerseason->payload as $fixture) { ?>
                            <option name="id" value="<? echo $fixture->id;?>"><?php echo $fixture->caption; ?></option>
                        <?php } ?>
                    </select>
                    <button type="submit" name="action"><?php _e('Submit','BrAc'); ?></button>
                </form>
                <h1 style="margin-top: 150px;"><?php _e('Export as CSV','BrAc'); ?></h1>
                <form method="post" name="leagueForm" action="">
                <input type="hidden" name="action" value="download_zip">
                    <?php
                    $api = new FootballData();
                    $soccerseason = $api->get_soccer_season();

                    foreach ($soccerseason->payload as $fixture) { ?>
                        <label><input type="checkbox" name="export_league[]" value="<? echo $fixture->id;?>"><?php echo $fixture->caption; ?></label><br>
                    <?php } ;?>
                <button type="submit" name="action"><?php _e('Export','BrAc'); ?></button>

            <?php
            }
        }
    }
}