<?php
add_action( 'show_user_profile', 'extra_user_profile_fields' );
add_action( 'edit_user_profile', 'extra_user_profile_fields' );

function extra_user_profile_fields( $user ) { ?>
<?php
global $wpdb;

$table_name = $wpdb->prefix . "wplusersprofiles";

$userID = get_current_user_id();

$user = $wpdb->get_results( "SELECT * FROM $table_name WHERE user_id = $userID" );

$addressEnabled = get_option('wpl_settings_pixelpin_address_enabled');
		
$phoneEnabled = get_option('wpl_settings_pixelpin_phone_enabled');

$genderEnabled = get_option('wpl_settings_pixelpin_gender_enabled');

?>

<h3 <?php if ($genderEnabled == '0' && $phoneEnabled == '0') echo 'style="display:none"' ?> ><?php _e("Extra profile information", "blank"); ?></h3>

<?php foreach ($user as $row){ ?>
<table class="form-table">
	<tr <?php if( $genderEnabled == '0' ) echo 'style="display:none"'; ?>>
		<th><label for="gender"><?php _e("Gender"); ?></label></th>
		<td>
			<input type="text" name="gender" id="gender" value="<?php echo $row->gender ?>" class="regular-text" /><br />
		</td>
	</tr>
	<tr <?php if( $phoneEnabled == '0' ) echo 'style="display:none"'; ?>>
		<th><label for="phone"><?php _e("Phone Number"); ?></label></th>
		<td>
			<input type="text" name="phone" id="phone" value="<?php echo $row->phone ?>" class="regular-text" /><br />
		</td>
	</tr>
</table>
<table class="form-table">
	<h3 <?php if( $addressEnabled == '0' ) echo 'style="display:none"'; ?>><?php _e("Address Information", "blank"); ?></h3>
	<tr <?php if( $addressEnabled == '0' ) echo 'style="display:none"'; ?>>
		<th><label for="address"><?php _e("Street Address"); ?></label></th>
		<td>
			<input type="text" name="address" id="address" value="<?php echo $row->address ?>" class="regular-text" /><br />
		</td>
	</tr>
	<tr <?php if( $addressEnabled == '0' ) echo 'style="display:none"'; ?>>
		<th><label for="city"><?php _e("City"); ?></label></th>
		<td>
			<input type="text" name="city" id="city" value="<?php echo $row->city ?>" class="regular-text" /><br />
		</td>
	</tr>
	<tr <?php if( $addressEnabled == '0' ) echo 'style="display:none"'; ?>>
		<th><label for="region"><?php _e("Region"); ?></label></th>
		<td>
			<input type="text" name="region" id="region" value="<?php echo $row->region ?>" class="regular-text" /><br />
			<span class="description"><?php _e("Your Region can be your province, state or county depending on where you live."); ?></span>
		</td>
	</tr>
	<tr <?php if( $addressEnabled == '0' ) echo 'style="display:none"'; ?>>
		<th><label for="zip"><?php _e("Postal Code"); ?></label></th>
		<td>
			<input type="text" name="zip" id="zip" value="<?php echo $row->zip ?>" class="regular-text" /><br />
			<span class="description"><?php _e("Your Postal code can either be your ZIP or Post Code depending on where you live."); ?></span>
		</td>
	</tr>
	<tr <?php if( $addressEnabled == '0' ) echo 'style="display:none"'; ?>>
		<th><label for="country"><?php _e("Country"); ?></label></th>
		<td>
			<input type="text" name="country" id="country" value="<?php echo $row->country ?>" class="regular-text" /><br />
		</td>
	</tr>
</table>
<?php } ?>

<?php }

add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );

function save_extra_user_profile_fields( $user_id ) {

	global $wpdb;

	$table_name = $wpdb->prefix . "wplusersprofiles";

	$userID = get_current_user_id();

	$data = array(
		'gender' => $_POST['gender'],
		'phone' => $_POST['phone'],
		'address' => $_POST['address'],
		'city' => $_POST['city'],
		'region' => $_POST['region'],
		'zip' => $_POST['zip'],
		'country' => $_POST['country'],
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
