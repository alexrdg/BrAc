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
        'custom menu',
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
        <option value="">test</option>
        <option value="">test2</option>
        <option value="">test3</option>
    </select>
<?php
}