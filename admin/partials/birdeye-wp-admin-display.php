<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://caseydallen.com
 * @since      1.0.0
 *
 * @package    Birdeye_Wp
 * @subpackage Birdeye_Wp/admin/partials
 */
$api_key = get_option( 'birdeye-api-key' );
$business_id = get_option( 'birdeye-business-id' );

global $birdeye_wp;


?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="birdeye-settings-box">
    <div class="settings-box-header">Birdeye WP Settings</div>
    <div class="settings-box-fields">

        <form action="?page=birdeye-wp" method="post" name="birdeye-settings-form">
            <label for="birdeye-api-key">API KEY</label>

            <input type="text" name="birdeye-api-key" id="birdeye-api-key" value="<?=$api_key ?>" >

            <label for="birdeye-business-id">Business Id</label>

            <input type="text" id="birdeye-business-id" name="birdeye-business-id" value="<?=$business_id ?>">
            <?= submit_button('Save Settings');?>
        </form>


    </div>

    <div id="dashboard">

        <?php
        // string(7) "allTime" string(10) "last30Days" string(15) "positiveReviews" string(15) "negativeReviews" string(11) "syndication"
        if ($birdeye_wp->dashboard) {


            // echo '<pre>';
            // var_dump($birdeye_wp->dashboard);
            // echo '</pre>';
            echo '<h1>dashboard</h1>';
            echo '<div style="max-width:80%;margin:0 auto 20px;">';
            foreach ($birdeye_wp->dashboard->allTime->benchmark as $key => $benchmark) {
              preg_match_all('/((?:^|[A-Z])[a-z]+)/',$key,$words);

              $title = strtoupper( implode( ' ', $words[0]) );

              printf( '<div class="one-fourth"><h2>%s</h2><div>%s</div></div>', $title, $benchmark );
            }

            echo '<div style="clear:both"></div>';
            echo '<h2>Review Sources</h2><ul>';
            foreach ($birdeye_wp->dashboard->syndication->reviewListings as $dashboard_data) {

                printf( '<li><span class="source-name">%s</span> : <span class="source-url">%s</span></li>', $dashboard_data->name, $dashboard_data->url);
            }
            echo '</ul>';
        }
        echo '</div>';
        ?>
    </div>
</div>
