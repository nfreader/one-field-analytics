<?php
/**
 * Plugin Name: One Field Google Analytics
 * Plugin URI: http://nfreader.net/ofa
 * Description: The world's easist Google Analytics Plugin. Enter your code (the bit that looks like UA-########-#), hit submit and your site visitors will be tracked. Logged in users are not tracked.
 * Version: dev-1.0
 * Author: Nick Farley
 * Author URI: http://nfreader.net
 * License: tbd
 * Text Domain: ofa-google-analytics
 */

function ofa_load_textdomain(){
load_plugin_textdomain('ofa-google-analytics', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'ofa_load_textdomain' );

function ofa_installed() { ?>

<div id="ofa-message" class="updated">
	<p><strong>Huzzah!</strong> <?php _e('Your Google Analytics Tracking Code has been installed!', 'ofa-google-analytics');?> <br><small><?php _e('Deactivate the plugin to clear your tracking code','ofa-google-analytics');?></small></p>
</div>

<?php }
 
if (isset($_POST['ofa_tracking_code'])) {
	add_option('ofa_tracking_code', $_POST['ofa_tracking_code'], NULL, true);
	ofa_installed();
}

function ofa_get_tracking_code() {
	$code = apply_filters('ofa_tracking_code', get_option('ofa_tracking_code'));
	if (!empty($code)) {
		return(array)$code;
	} else {
		return array();
	}
}

function ofa_tracking_code_form() { ?>
	<div id="ofa-message" class="updated">
	<p><?php _e('<strong>Important</strong> Please enter your Google Analytics tracking code. Your website traffic will not be tracked until you do!');?>
		<form method="post" action="">
			<label for="ofa_tracking_code" style="vertical-align: baseline;">Tracking Code</label> 
			<input type="text" id="ofa_tracking_code" placeholder="UA-########-#" name="ofa_tracking_code" class="code regular-text"  style="max-width: 100%;"/>
			<input type="submit" value="Save" class="button-primary"/>
		</form>
		<small><?php _e('Your tracking code should be a string that looks like UA-########-#'); ?></small>
	</p>
	</div>
	
<?php }

if (!ofa_get_tracking_code()) {
	add_action('admin_notices','ofa_tracking_code_form');
} elseif (!(is_admin())) {

function ofa_tracking() {
	$tracking_code = get_option('ofa_tracking_code');
		?>
		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
				(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
				m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
			ga('create', '<?php echo $tracking_code; ?>');
			ga('send', 'pageview');
		</script>
		<?php
	}
	add_action('wp_footer','ofa_tracking');
} else {
	
}

//Delete the tracking code on  plugin deactivation

function ofa_deactivated() {
	delete_option('ofa_tracking_code');
}

register_deactivation_hook(__FILE__, 'ofa_deactivated');

