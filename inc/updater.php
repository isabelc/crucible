<?php
/**
* Theme updater delivers version updates to WP dash.
*/
// @new update.
define( 'ST_CRUCIBLE_URL', 'http://smartestthemes.com' );
define( 'ST_CRUCIBLE_DLNAME', 'Crucible WordPress Theme' ); // @todo after putting up download

if ( !class_exists( 'EDD_SL_Theme_Updater' ) ) {
	include( dirname( __FILE__ ) . '/EDD_SL_Theme_Updater.php' );
}

function crucible_edd_sl_updater() {

	$license = trim( get_option( 'st_crucible_license_key' ) );// @new

	$edd_updater = new EDD_SL_Theme_Updater( array(
			'remote_api_url' 	=> ST_CRUCIBLE_URL,
			'version' 			=> '1.0.0', // @todo update match from style.css
			'license' 			=> $license,
			'item_name' 		=> ST_CRUCIBLE_DLNAME,
			'author'			=> 'Smartest Themes',
			'url'           => home_url()
		)
	);
}
add_action( 'admin_init', 'crucible_edd_sl_updater' );


/**
* Add menu item
*/
function crucible_license_menu() {

	$themeobject = wp_get_theme();
	$slug = $themeobject->Template;
	add_theme_page( __( 'Theme License', 'crucible' ), __( 'Theme License', 'crucible' ), 'manage_options', $slug . 'license', 'crucible_license_page' );
}
add_action('admin_menu', 'crucible_license_menu');

/**
* Settings page
*/
function crucible_license_page() {
	$license 	= get_option( 'st_crucible_license_key' );
	$status 	= get_option( 'st_crucible_license_key_status' );
	$themeobject = wp_get_theme();
	$themeslug = $themeobject->Template;
	$themeurl = 'http://smartestthemes.com/downloads/'.$themeslug.'/';

	?>
	<div class="wrap">
		<h2><?php _e('Theme License', 'crucible'); ?></h2>
<p><?php _e('A theme license will grant you access to support and updates. If you wish to update to the latest theme version or get support for this theme, you need an active license.', 'crucible'); ?> 
&nbsp;<a href="<?php echo $themeurl; ?>" target="_blank"><?php _e( 'Purchase a license', 'crucible' ); ?></a></p>

		<form method="post" action="options.php">
			<?php settings_fields('crucible_theme_license'); ?>
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e('License Key', 'crucible'); ?>
						</th>
						<td>
							<input id="st_crucible_license_key" name="st_crucible_license_key" type="text" class="regular-text" value="<?php echo esc_attr( $license ); ?>" />
							<label class="description" for="st_crucible_license_key"><?php _e('Enter your license key', 'crucible'); ?></label>
						</td>
					</tr>
					<?php if( false !== $license ) { ?>
						<tr valign="top">
							<th scope="row" valign="top">
								<?php _e('Activate License', 'crucible'); ?>
							</th>
							<td>
								<?php if( $status !== false && 'valid' == $status ) { ?>
									<span style="color:green;font-weight:bold;padding:12px;"><?php _e('Status: valid  ', 'crucible'); ?> &nbsp;</span>
									<?php wp_nonce_field( 'st_crucible_nonce', 'st_crucible_nonce' ); ?>
									<input type="submit" class="button-secondary" name="edd_theme_license_deactivate" value="<?php _e('Deactivate License', 'crucible'); ?>"/>
								<?php } else { ?>
		<span style="color:red;font-weight:bold;padding:12px;"><?php _e('Status: not valid', 'crucible'); ?></span>

<?php wp_nonce_field( 'st_crucible_nonce', 'st_crucible_nonce' ); ?>
									<input type="submit" class="button-secondary" name="edd_theme_license_activate" value="<?php _e('Activate License', 'crucible'); ?>"/>
								<?php } ?>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php submit_button(); ?>
		</form>
	<?php
}

function crucible_register_option() {
	
	register_setting('crucible_theme_license', 'st_crucible_license_key', 'edd_theme_sanitize_license' );
}
add_action('admin_init', 'crucible_register_option');

/**
* Gets rid of the local license status option when adding a new one
*/

function edd_theme_sanitize_license( $new ) {
	$new = sanitize_text_field( $new );
	$old = get_option( 'st_crucible_license_key' );
	if( $old && $old != $new ) {
		delete_option( 'st_crucible_license_key_status' ); // new license has been entered, so must reactivate
	}
	return $new;
}

/**
* Activate license key.
*/
function crucible_activate_license() {

	if( isset( $_POST['edd_theme_license_activate'] ) ) {
	 	if( ! check_admin_referer( 'st_crucible_nonce', 'st_crucible_nonce' ) )
			return; // get out if we didn't click the Activate button

		$license = trim( get_option( 'st_crucible_license_key' ) );
		$api_params = array(
			'edd_action' => 'activate_license',
			'license' => $license,
			'item_name' => urlencode( ST_CRUCIBLE_DLNAME ),
			'url' => home_url()
		);

		$response = wp_remote_get( add_query_arg( $api_params, ST_CRUCIBLE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

		if ( is_wp_error( $response ) )
			return false;
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		update_option( 'st_crucible_license_key_status', $license_data->license );
	}
}
add_action('admin_init', 'crucible_activate_license');

/**
* Deactivate a license key, allows user to transfer license to another site. 
*/

function crucible_deactivate_license() {

	if( isset( $_POST['edd_theme_license_deactivate'] ) ) {

		if( ! check_admin_referer( 'st_crucible_nonce', 'st_crucible_nonce' ) )
			return;

		$license = trim( get_option( 'st_crucible_license_key' ) );
		$api_params = array(
			'edd_action'=> 'deactivate_license',
			'license' 	=> $license,
			'item_name' => urlencode( ST_CRUCIBLE_DLNAME )
		);

		$response = wp_remote_get( add_query_arg( $api_params, ST_CRUCIBLE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

		if ( is_wp_error( $response ) )
			return false;

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "deactivated" or "failed"
		if( $license_data->license == 'deactivated' )
			delete_option( 'st_crucible_license_key_status' );
	}
}
add_action('admin_init', 'crucible_deactivate_license');