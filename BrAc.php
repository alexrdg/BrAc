<?php
/*
Plugin Name: BrAC
Plugin URI: https://github.com/alexrdg/BrAc
Description: Plugin for Soccer results - Need to activate the widget to work !
Version: 1.5
Author: alexRdg
Author URI: http://www.alexrdg.com/
License:
*/
require_once 'SoccerSeason.php';
require_once 'Team.php';
require_once 'FootballData.php';
require_once 'MenuBar.php';

add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );

// Creating the widget
class wpb_widget extends WP_Widget {

    function __construct() {
        parent::__construct(
        // Base ID of your widget
            'wpb_BrAc',

            // Widget name will appear in UI
            __('BrAc Widget', 'wpb_BrAc_domain'),

            // Widget description
            array( 'description' => __( 'Widget for BrAc plugin.', 'wpb_BrAc_domain' ), )
        );
    }

    // Creating widget front-end
    // This is where the action happens
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if ( ! empty( $title ) )
            echo $args['before_title'] . $title . $args['after_title'];

        // This is where you run the code and display the output
        $api = new FootballData();
        ?>
        <div style="margin-top: 50px;">
            <?php
            $competition = get_option('league_id');
            $competition_select = $api->get_soccerseason_by_id($competition);
            $day_of_game = $competition_select->payload->currentMatchday;
            $fixture_match = $api->get_fixtures_for_league_and_match($day_of_game);
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
                <?php foreach ($fixture_match->fixtures as $fixture) {
                    $date_formated = explode('T', $fixture->date);?>
                    <tr>
                        <td style="text-align: center;"><? echo __($fixture->homeTeamName,'wpb_BrAc_domain'); ?><br>
                            <?php echo __($fixture->result->goalsHomeTeam,'wpb_BrAc_domain'); ?>
                        </td>
                        <td style="text-align: center;"><? echo __($date_formated[0],'wpb_BrAc_domain'); ?></td>
                        <td style="text-align: center;"><? echo __($fixture->awayTeamName,'wpb_BrAc_domain'); ?><br>
                            <?php echo __($fixture->result->goalsAwayTeam,'wpb_BrAc_domain'); ?>
                        </td>
                    </tr>
                <?php }?>
            </table>
        </div>
        <?php
        echo $args['after_widget'];
    }
    // Widget Backend
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( 'New title', 'wpb_widget_domain' );
        }
        // Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php
    }
    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }
}
function wpb_load_widget() {
    register_widget( 'wpb_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );

