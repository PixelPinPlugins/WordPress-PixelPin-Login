<?php
add_action( 'show_user_profile', 'wpl_extra_user_profile_fields' );
add_action( 'edit_user_profile', 'wpl_extra_user_profile_fields' );

/**
* Enqueue WPL CSS file
*/
function wpl_add_stylesheet()
{
	wp_register_style( "wpl-fields", WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL . "assets/css/fields.css" );
	wp_enqueue_style( "wpl-fields" );
}

add_action( 'admin_init'   , 'wpl_add_stylesheet' );
//add_action( 'login_enqueue_scripts', 'wpl_add_stylesheets' );

function wpl_extra_user_profile_fields( $user ) { ?>
<?php
global $wpdb;

$table_name = $wpdb->prefix . "wplusersprofiles";

$userID = get_current_user_id();

$user = $wpdb->get_results( "SELECT * FROM $table_name WHERE user_id = $userID" );

?>

<?php }

add_action( 'personal_options_update', 'wpl_save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'wpl_save_extra_user_profile_fields' );

function wpl_save_extra_user_profile_fields( $user_id ) {

	global $wpdb;

	$table_name = $wpdb->prefix . "wplusersprofiles";

	$userID = get_current_user_id();
	
	//Sanitise
	$gender = sanitize_text_field($_POST['gender']);
	$phone = sanitize_text_field($_POST['phone']);
	$address = sanitize_text_field($_POST['address']);
	$city = sanitize_text_field($_POST['city']);
	$region = sanitize_text_field($_POST['region']);
	$zip = sanitize_text_field($_POST['zip']);
	$country = sanitize_text_field($_POST['country']);
	

	$data = array(
		'gender' => $gender,
		'phone' => $phone,
		'address' => $address,
		'city' => $city,
		'region' => $region,
		'zip' => $zip,
		'country' => $country,
	);

	$where = array( 'user_id' => $userID );

	$format = array (
		'%s',
	    '%s',
	    '%s',
	    '%s',
	    '%s',
	    '%s',
	    '%s'
	);

	$where_format = array( '%s' );

	if ( isset( $_POST['submit'])){
		$wpdb->update( $table_name, $data, $where, $format, $where_format );
	}
}

?>
