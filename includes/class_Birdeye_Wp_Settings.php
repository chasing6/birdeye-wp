<?php

/**
 * Created by PhpStorm.
 * User: casey
 * Date: 12/3/16
 * Time: 10:52 PM
 */
class Birdeye_Wp_Settings {


    public function __construct() {}

    public function save_birdeye_wp_settings() {

        $settings = json_decode( stripslashes( $_POST['settings'] ) );
        $returnArray = [];

        foreach ($settings as $setting) {
             if ( update_option( $setting->name, $setting->value ) ) {
                 array_push( $returnArray, array( 'success' => true, 'message' => str_replace( array('-', '_'), ' ', $setting->name ) . ' was updated' ) );
             } else {
                 array_push( $returnArray, array( 'success' => false, 'message' => str_replace( array('-', '_'), ' ', $setting->name ) . ' was not updated' ) );
             }
        }

        echo json_encode( $returnArray );

        wp_die();
    }

    /*
     * "https://api.birdeye.com/resources/v1/aggregation/business/755009344?api_key=92bcd6e0-c102-43fd-8a67-1a7be5258451" –v
     * */


}

