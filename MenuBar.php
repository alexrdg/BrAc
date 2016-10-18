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
    ?>
    <h1>Hello World !</h1><br>
    <h2>Choose your league</h2>
    <select>
        <?php
            $api = new FootballData();
            // fetch and dump summary data for premier league' season 2015/16
            $soccerseason = $api->getSoccerSeason();
            foreach ($soccerseason->payload as $fixture) { ?>
            <option value="<?=$fixture->id;?>"><?php echo $fixture->caption; ?></option>
        <?php } ?>
    </select>
    <button type="button">Submit</button>
<?php
}