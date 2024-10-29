<?php

/**
 * Auto Comment Moderation by ModerateHatespeech.com 
 *
 * @link              https://moderatehatespeech.com
 * @since             1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       Auto Comment Moderation
 * Plugin URI:        https://moderatehatespeech.com
 * Description:       Automatically send toxic and hateful comments to the comment moderation queue.
 * Version:           1.0.0
 * Author:            ModerateHatespeech.com
 * Author URI:        https://moderatehatespeech.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       auto-comment-moderation
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'AUTO_COMMENT_MODERATION_VERSION', '1.0.0' );

/**
 * Show a message asking for API key on activate if such field doesn't currently exist in DB
 * Adds default, empty values to options key
 * @since 1.0.0
 */
function modhs_activate_auto_comment_moderation() {
	if ( !isset(get_option( 'modhs_settings' )['modhs_apikey']) || empty(get_option( 'modhs_settings' )['modhs_apikey']) ){
		set_transient( 'modhs-admin-notice-activate', true );
		update_option('modhs_settings', array("modhs_apikey" => "", "modhs_confidence" => 80));
	}
}


/**
 * Handles the displaying of an admin notice asking user for API key
 * @since 1.0.0
 */
function modhs_activate_notice(){
    if( get_transient( 'modhs-admin-notice-activate' ) ){
    ?>
		<div class="error is-dismissible">
			<p>Please enter an API Key from <a href="https://moderatehatespeech.com">ModerateHatespeech.com</a> in the <a href="admin.php?page=moderatehatespeech">Settings</a> to enable core functionality.</p>
		</div>
    <?php
		/* Delete transient, only display this notice once. */
		delete_transient( 'modhs-admin-notice-activate' );
    }
}

/**
 * Queries the ModerateHatespeech.com API if API Key is set
 * Sets comment status back to pending if comment is flagged as toxic, and confidence is above set threshold
 * Does not fire if comment is not currently approved
 * If an error is returned, no action is taken
 * 
 * @param int $comment_ID                    comment ID of comment
 * @param int|string $comment_approved       status of comment
 * @param array $comment_data                array of comment data
 * @since 1.0.0
 * 
 */
function modhs_process_comment($comment_ID, $comment_approved, $comment_data){
	
	// Only process comments that are approved
	if ( $comment_approved === 1 ) {
		
		$options = get_option( 'modhs_settings' );
		
		if ( !empty($options['modhs_apikey']) ){
			
			$response = wp_remote_post("https://api.moderatehatespeech.com/api/v1/toxic/", array(
		        'method' => 'POST',
		        'timeout' => 7,
		        'redirection' => 2,
		        'blocking' => true,
		        'body' => json_encode(array(
		            'token' => $options['modhs_apikey'],
		            'text' => $comment_data['comment_content']
		        ))
		    ));
			
		    if ( !is_wp_error($response) ) {
		        $result = json_decode($response['body'], true);
				if ( isset($result['class']) && $result['class'] == "flag" && $result['confidence'] * 100 > $options['modhs_confidence'] ){
					$comment = get_comment( $comment_ID, 'ARRAY_A' );
					$comment['comment_approved'] = 0;
					wp_update_comment( $comment );
				}
		    }
			
		}
	}
}

/*
 * Register action hooks
 */
add_action( 'admin_notices', 'modhs_activate_notice' );
add_action( 'comment_post', 'modhs_process_comment', 10, 3 );
register_activation_hook( __FILE__, 'modhs_activate_auto_comment_moderation' );

/**
 * Require admin page
 */
require plugin_dir_path( __FILE__ ) . 'admin/auto-comment-moderation-admin.php';

