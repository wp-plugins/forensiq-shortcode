<?php

/*
Plugin Name: Forensiq Shortcode
Description: Creates a Forensiq shortcode to manage Forensiq Javscript. Find out <a target="_blank" href="http://forensiq.com/">more here</a>.
Plugin URI: http://www.skyrockinc.com/forensiq-shortcode/
Version: 0.1
Author: hypedtext
Author URI: http://www.skyrockinc.com/
*/

class Forensiq_Shortcode {

	function __construct() {
		if ( is_admin() ) {
			add_action( 'admin_menu', array( &$this, 'add_option_page' ) );  
		} else {
			add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_scripts' ) );
			add_shortcode( 'forensiq', array( &$this, 'forensiqshortcode' ) );
		}
	}
	
	function CookieParams () {
		$this->__construct();
	}
	
	function forensiqshortcode () {
		$fqorg = get_option( 'orgid' );
		$fqprefix = get_option( 'prefix' );
		if ( get_option( 'paramsorcookies' ) == 'params' ) {
		?>
		<script type="text/javascript">
			var fqsource = getParameterByName( '<?php echo get_option( 'sourceid' ); ?>' );
			var fqp = "<?php echo $fqprefix; ?> - " + fqsource;
			var fqa = getParameterByName( '<?php echo get_option( 'subsource' ); ?>' );
			var fqcmp = getParameterByName( '<?php echo get_option( 'campaignid' ) ?>' );
			var fqrf = getReferrer();
			if ( fqsource ) {
				document.write( "<scr" + "ipt src='//c.fqtag.com/tag/implement-r.js?org=<?php echo $fqorg; ?>&s=&p=" + fqp + "&a=" + fqa + "&cmp=" + fqcmp + "&rt=display&sl=1&fmt=banner&rf=" + fqrf + "&fq=1'></scr" + "ipt>" );
				document.write( "<noscr" + "ipt><img src='https://www.fqtag.com/pixel.cgi?org=<?php echo $fqorg; ?>&s=&p=" + fqp + "&a=" + fqa + "&cmp=" + fqcmp + "&rt=displayImage&sl=1&fmt=banner&rf=" + fqrf + "&fq=1' width='1' height='1' border='0' /></noscr" + "ipt>" );
			}
		</script>
		<?php
		} else {
		?>
		<script type="text/javascript">
			/*console.log( 'utm_source = ' + getCookie( 'utm_source' ) );
			console.log( 'utm_campaign = ' + getCookie( 'utm_campaign' ) );
			console.log( 'utm_content = ' + getCookie( 'utm_content' ) );*/
			var fqsource = getCookie( '<?php echo get_option( 'sourceid' ); ?>' );
			var fqp = "<?php echo $fqprefix; ?> - " + fqsource;
			var fqa = getCookie( '<?php echo get_option( 'subsource' ); ?>' );
			var fqcmp = getCookie( '<?php echo get_option( 'campaignid' ) ?>' );
			var fqrf = getReferrer();
			if ( fqsource ) {
				document.write( "<scr" + "ipt src='//c.fqtag.com/tag/implement-r.js?org=<?php echo $fqorg; ?>&s=&p=" + fqp + "&a=" + fqa + "&cmp=" + fqcmp + "&rt=display&sl=1&fmt=banner&rf=" + fqrf + "&fq=1'></scr" + "ipt>" );
				document.write( "<noscr" + "ipt><img src='https://www.fqtag.com/pixel.cgi?org=<?php echo $fqorg; ?>&s=&p=" + fqp + "&a=" + fqa + "&cmp=" + fqcmp + "&rt=displayImage&sl=1&fmt=banner&rf=" + fqrf + "&fq=1' width='1' height='1' border='0' /></noscr" + "ipt>" );
			}
		</script>
		<?php
		}
	}

	function enqueue_scripts () {
			wp_enqueue_script( 'forensiq', plugins_url( '/inc/functions.js', __FILE__ ) );
	}

	function add_option_page() {
		if ( function_exists( 'add_options_page' ) ) {
			add_options_page( 'Forensiq Shortcode', 'Forensiq Shortcode', 'manage_options', __FILE__, array( &$this, 'options_page' ) );
		}
	}

	function options_page() {
		if ( isset( $_POST['update'] ) ) {
			echo '<div id="message" class="updated fade"><p><strong>';
			$option_01 = htmlentities( stripslashes( $_POST['orgid'] ) , ENT_COMPAT );
			update_option( 'orgid', $option_01 );
			$option_02 = htmlentities( stripslashes( $_POST['prefix'] ) , ENT_COMPAT );
			update_option( 'prefix', $option_02 );
			$option_03 = htmlentities( stripslashes( $_POST['sourceid'] ) , ENT_COMPAT );
			update_option( 'sourceid', $option_03 );
			$option_04 = htmlentities( stripslashes( $_POST['subsource'] ) , ENT_COMPAT );
			update_option( 'subsource', $option_04 );
			$option_05 = htmlentities( stripslashes( $_POST['campaignid'] ) , ENT_COMPAT );
			update_option( 'campaignid', $option_05 );
			$option_06 = htmlentities( stripslashes( $_POST['paramsorcookies'] ) , ENT_COMPAT );
			update_option( 'paramsorcookies', $option_06 );
			echo 'Options Updated!';
			echo '</strong></p></div>';
		}
	?>
		<div class="wrap">
			<h2>Forensiq Settings</h2>
			<hr>
			<br>
				<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
				<input type="hidden" name="update" id="update" value="true" />
				<fieldset class="options">
					<table class="wp-list-table widefat">
						<tr>
							<td class="plugin-title" width="20%"><strong>Org ID</strong></td>
							<td width="80%">
								<input type="text" name="orgid" size="22" value="<?php echo get_option( 'orgid' ); ?>">
							</td>
						</tr>
						<tr class="alternate">
							<td class="plugin-title" width="20%"><strong>Prefix for source param</strong>
								<span>Use a short string to add a prefix to the source param. Make it a lot easier to work with multiple sites in the Forensiq UI.</span>
							</td>
							<td width="80%">
								<input type="text" name="prefix" size="22" value="<?php echo get_option( 'prefix' ); ?>">
							</td>
						</tr>
						<tr>
							<td class="plugin-title" width="20%"><strong>Source ID</strong>
								<span>Name of Source ID.</span>
							</td>
							<td width="80%">
								<input type="text" name="sourceid" size="22" value="<?php echo get_option( 'sourceid' ); ?>">
							</td>
						</tr>
						<tr class="alternate">
							<td class="plugin-title" width="20%"><strong>Sub Source</strong>
								<span>Name of Sub Source.</span>
							</td>
							<td width="80%">
								<input type="text" name="subsource" size="22" value="<?php echo get_option( 'subsource' ); ?>">
							</td>
						</tr>
						<tr>
							<td class="plugin-title" width="20%"><strong>Campaign ID</strong>
								<span>Name of Campaign ID.</span>
							</td>
							<td width="80%">
								<input type="text" name="campaignid" size="22" value="<?php echo get_option( 'campaignid' ); ?>">
							</td>
						</tr>
						<tr>
							<td class="plugin-title" width="20%">
								<strong>Params or Cookies</strong>
								<span>Choose URL params or cookies for reporting.</span>
							</td>
							 <td align="left" width="80%">
								<input type="radio" name="paramsorcookies" value="params" <?php if ( get_option('paramsorcookies') == "params" ) { echo "checked"; } ?>>Params&nbsp;
								<input type="radio" name="paramsorcookies" value="cookies" <?php if ( get_option('paramsorcookies') == "cookies" ) { echo "checked"; } ?>>Cookies&nbsp;
							</td>
						</tr>
					</table>
				</fieldset>
				<div class="submit">
					<input type="submit" class="button-primary" name="update" value="Update options" />
				</div>
				</form>
		</div>
	<?php
	}
}
$Forensiq_Shortcode = new Forensiq_Shortcode;
