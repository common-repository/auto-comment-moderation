<?php

/*
 * Insert menu and options into WP Admin
 */
add_action( 'admin_menu', 'modhs_add_admin_menu' );
add_action( 'admin_init', 'modhs_settings_init' );

/* 
 * Register menu in WP Admin
 * @since 1.0.0
 */
function modhs_add_admin_menu() { 
	add_menu_page( 'Moderate Hatespeech', 'Moderate Hatespeech', 'manage_options', 'moderatehatespeech', 'modhs_options_page' );
}

/* 
 * Register settings in WP Admin
 * @since 1.0.0
 */
function modhs_settings_init() { 
	register_setting( 'pluginPage', 'modhs_settings' );
	
	add_settings_section(
		'modhs_pluginPage_section', 
		__( '', 'auto-comment-moderation' ), 
		'modhs_settings_cb', 
		'pluginPage'
	);

	add_settings_field( 
		'modhs_apikey', 
		__( 'ModerateHatespeech.com API Key', 'auto-comment-moderation' ), 
		'modhs_apikey_render', 
		'pluginPage', 
		'modhs_pluginPage_section' 
	);

	add_settings_field( 
		'modhs_confidence', 
		__( 'Confidence Threshold', 'auto-comment-moderation' ), 
		'modhs_confidence_render', 
		'pluginPage', 
		'modhs_pluginPage_section' 
	);
}

/* 
 * Renders form field for API Key input
 * @since 1.0.0
 */
function modhs_apikey_render() { 
	$options = get_option( 'modhs_settings' );
	?>
	<input type='text' name='modhs_settings[modhs_apikey]' value='<?php echo esc_html($options['modhs_apikey']); ?>'>
	<?php
}

/* 
 * Renders form field for confidence level select
 * @since 1.0.0
 */
function modhs_confidence_render() { 
	$options = get_option( 'modhs_settings' );
	?>
	<select name='modhs_settings[modhs_confidence]'>
		<option value='98' <?php selected( $options['modhs_confidence'], 98 ); ?>>Extremely Safe (> 98%)</option>
		<option value='90' <?php selected( $options['modhs_confidence'], 90 ); ?>>Safe (> 90%)</option>
		<option value='80' <?php selected( $options['modhs_confidence'], 80 ); ?>>Standard (> 80%)</option>
		<option value='50' <?php selected( $options['modhs_confidence'], 50 ); ?>>Strict (> 50%)</option>
		<p>(Higher confidence thresholds reduces false positives, but could miss potentially ambiguous comments)</p>
	</select>
<?php
}

/* 
 * Renders options page description
 * @since 1.0.0
 */
function modhs_settings_cb() { 
	echo __( 'Configure the options below to ensure the plugin works properly. Flagged comments are automatically set as pending and held for human moderation.', 'auto-comment-moderation' );
}

/* 
 * Renders actual options page
 * @since 1.0.0
 */
function modhs_options_page() { 
		?>
		<style>
			.form-table th {
				min-width: 260px;
			}

			input[type="text"] {
				min-width: 250px;
			}
		</style>
		<form action='options.php' method='post'>

			<h2>Auto Comment Moderation by ModerateHatespeech.com</h2>

			<?php
			settings_fields( 'pluginPage' );
			do_settings_sections( 'pluginPage' );
			submit_button();
			?>

		</form>
		<?php
}
