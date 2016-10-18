<?php
/**
 * Created by PhpStorm.
 * User: alexRdg
 * Date: 17/10/2016
 * Time: 17:39
 */

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
function my_custom_menu_page(){
    $url = plugins_url();
    ?>
    <h1>Choose your league</h1>
    <form method="post" name="leagueForm" action="">
        <select name="selectChoice">
            <?php
                $api = new FootballData();
                // fetch and dump summary data for premier league' season 2015/16
                $soccerseason = $api->getSoccerSeason();
                foreach ($soccerseason->payload as $fixture) { ?>
                <option selected="<?= get_option('league_id'); ?>" name="id" value="<?=$fixture->id;?>"><?php echo $fixture->caption; ?></option>
            <?php } ;?>
        </select>
        <button type="submit" name="action">Submit</button>
    </form>
<?php
    if(isset($_POST['selectChoice'])) {
        update_option('league_id',$_POST['selectChoice']);
        echo '<h3>Sucessfully sent !</h3>';
    }
}
