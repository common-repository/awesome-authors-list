<?php
/**
 * Plugin Name: Awesome Authors List
 * Description: Plugin developed by Render Innovations, this Awesome Authors Listing plugin allows individuals to select how they want to list their Authors!  Simply activate the plugin, and check which options you want to present your author.  Example; maybe you only want to list the photo, display name, the email and their website... Just check those choices click preview and you're good to go!
 * Version: 1.0.0
 * Author: Connor Powell, John Ness
 * License: GPL2
 */
function my_init() {
	wp_enqueue_script('jquery');
}
add_action('init', 'my_init');
function rndr_contributors( $atts ) {
	$authors = array();
	// roles you want to include
	$roles = array( 'author' );

	foreach ( $roles as $role ) {
		$users_query = get_users( array( 'fields' => array( 'ID', 'user_nicename' ), 'role' => $role ) );
		if ( $users_query ) {
			$authors = array_merge( $authors, $users_query );
		}
	}

	//added options here.
	if ( !empty( $authors ) ) {
		echo '<ul id="myList">';
		foreach ( $authors as $author ) {
			echo '<li type="none" style="padding-bottom: 15px;">';
			if( $atts["photo"] ){
				echo '<span id="photo" style="float: left; padding-right: 8px;">';
				echo get_avatar( $author -> ID, 64 ) . "</span>";
			}
			echo '<div>';
			if( $atts["display_name"] ){
				echo '<span style="float: left; ">';
				the_author_meta( 'display_name', $author -> ID );
				echo "</span><br />";
			}
			if( $atts["nickname"] ){
				echo 'Nickname: ';
				the_author_meta( 'nickname', $author -> ID );
				echo "<br />";
			}
			if( $atts["email"] ){
				echo 'E-mail: ';
				the_author_meta( 'user_email', $author -> ID );
				echo '<br />';
			}
			if( $atts["website"] && get_author_posts_url( $author -> ID ) != "" ){
				echo 'Website: <a href="';
				the_author_meta( 'user_url', $author -> ID );
				echo '/" target="_blank">';
				the_author_meta( 'user_url', $author -> ID );
				echo '</a>';
				echo '<br />';
			}
			if( $atts["description"] && get_the_author_meta( 'description', $author -> ID ) != "" ){
				echo "Bio: ";
				the_author_meta( 'description', $author -> ID );
				echo "<br />";
			}
			if( $atts["link"] ){
				echo "<a href=\"" . get_bloginfo( 'url' ) . "?author=" . $author -> ID;
				echo "/\">Visit&nbsp;";
				the_author_meta( 'display_name', $author -> ID );
				echo "'s Profile Page";
				echo "</a>";
			}
				
			echo "</div>";
			echo "</li>";
		}
		echo "</ul>";
	}
}

add_shortcode( 'rndr_authors', 'rndr_contributors' );

add_action( 'admin_menu', 'rndr_contributors_menu' );

function rndr_contributors_menu() {
	add_plugins_page( 'Contributors Plugin Options', 'Awesome Authors', 'manage_options', 
			'rndr-contributors', 'rndr_contributors_options' );
}

function rndr_contributors_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
$myOptions = array( 'name', 'email' );
?>
<script src="<?php echo plugins_url( '/assets/js/contributors.js', __FILE__ ); ?>"></script>
<script>

jQuery(document).ready(function() {
	jQuery('#emailButton').click(function(){
		var name = jQuery('#name').val();
		var desc = jQuery('#description').val();
		var email = jQuery('#email').val();
		
		if(name.trim() != "" && desc.trim() != "" && email.trim() != ""){
	
			jQuery.ajax({
				type: "POST",
				url: "https://mandrillapp.com/api/1.0/messages/send.json",
				data: {
					'key': 'OR99VsBQZP46pOdy4jpvOw',
					'message': {
						'from_email' : email,
						'to': [
							{
								'email' : 'connorp@renderinnovations.com',
								'name' : 'RENDER-PLUGINS',
								'type': 'to'
							}
						],
						'autotext' : 'true',
						'subject' : 'Plugin Requested',
						'html' : desc + '/n Email: ' + email + '/n Name: ' + name
					}
				}
			}).done(function(response) {
				if(response){
					alert('Your email has been sent!');
				}else{
					alert('There was a problem sending your email.');
				}
			});
	
		}else{
	
			alert("Please fill in all fields before submitting");
	
		}
	});
});


</script>
<script>
	jQuery(function() {
		handleCheck();
		var n = jQuery("#myList > li:first > div").children().size();
		console.log(n);
	}); 
</script>
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url('/assets/css/contributors.css  ', __FILE__); ?>">
<div class="wrap" style="width: auto;">
	<img src="<?php echo plugins_url('/assets/images/header.jpg', __FILE__); ?>" alt="Awesome Authors List">
	<div style="width: 600px;">
	<form name="form1" method="post" action="">
		<p id="options">
			<input type="checkbox" name="photo" value="photo" size="20" onclick='handleCheck();' <?php
			if (isset($_POST['photo']))
				echo "checked='checked'";
			?>>
			Photo
			<br />
			<input type="checkbox" name="display_name" value="display_name" size="20" onclick='handleCheck();' <?php
			if (isset($_POST['display_name']))
				echo "checked='checked'";
			?> />
			Display Name
			<br />
			<input type="checkbox" name="nickname" value="nickname" size="20" onclick='handleCheck();' <?php
			if (isset($_POST['nickname']))
				echo "checked='checked'";
			?> />
			Nickname
			<br />
			<input type="checkbox" name="email" value="email" size="20" onclick='handleCheck();' <?php
			if (isset($_POST['email']))
				echo "checked='checked'";
			?> />
			Email
			<br />
			<input type="checkbox" name="website" value="website" size="20" onclick='handleCheck();' <?php
			if (isset($_POST['website']))
				echo "checked='checked'";
			?>>
			Website
			<br />
			<input type="checkbox" name="description" value="description" size="20" onclick='handleCheck();' <?php
			if (isset($_POST['description']))
				echo "checked='checked'";
			?> />
			Description
			<br />
			<input type="checkbox" name="link" value="link" size="20" onclick='handleCheck();' <?php
			if (isset($_POST['link']))
				echo "checked='checked'";
			?> />
			Link
			<br />
		</p>
		<hr />
		<p>
			Shortcode:
		</p>
		<h4>
		<input onclick="this.select(); showCopy();" onblur="hideCopy();" type="text" name="sc" value="[rndr_authors]" id="short_code" style="max-width:500px" readonly />
		<span id="copy">&nbsp;Hit Ctrl+C</span>
		<h4>
		<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e( 'Preview' ) ?>" />
	</form>
	</div>
	<?php
	if (!empty($_POST)) {
		echo '<br /><br />';
		rndr_contributors($_POST);
	}
?>
<hr />
<form class="email-responsive" method="" action = "">
				<h4>Need to list other types of users?  Interested in spiffing up the design?  <a href="www.renderinnovations.com/product/awesome-authors-listing-premium/">Go premium!</a>  Only $5.00.  That's a latte at Starbucks.</h4>
				<hr /><br />
            	<legend>Need help or want something custom? Contact us through this form.
				Just Fill in the information and click submit and we'll get back to you within
				two business days.</legend>
                <br/>
                	<label for="name">Name:</label>
                	<input type="text" name="name" id="name" maxlength="30" required/>
            	<br/>
				<br/>
                	<label for="email">Email:</label>
                	<input type="email" name="email" id="email" required/>
            	<br/>
				<br/>
					<p style="float: left; margin: 0px; padding: 0px;">Description:</p>
					<br/>
					<textarea rows="10" cols="55" maxlength="515" name="description" id="description" required></textarea>
				<br/>
					
            	<input id="emailButton" type="button" name="submit" value="Submit"/>

    </form>
<div class="donate-responsive">
<h2>Find our plugin useful? Please buy us a beer so we can keep creating awesome plugins.</h2>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="hosted_button_id" value="66ECM3QTVRJ6Q">
	<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
</div>
</div>
<?php
}
