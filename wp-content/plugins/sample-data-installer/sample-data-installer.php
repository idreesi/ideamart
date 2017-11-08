<?php
/*
Plugin Name: Sample Data Installer
Plugin URI: http://themes.zone
Description: Sample Data Installer for Dash Store Theme
Author: Themes Zone
Author URI: http://themes.zone
Version: 1.2
License: GNU General Public License v3.0
License URI: http://www.opensource.org/licenses/gpl-license.php
*/

function sample_data_installer_activate() {
	global $wpdb;

	add_option('sample_data_installer_plugin_activated', true);
	if( !defined('DB_CHANGED') ){

		$is_secure = false;
		if ( ( !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ) || $_SERVER['SERVER_PORT'] == 443 ) {
				$is_secure = true;
		}
		elseif ( ( !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) || ( !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on' ) ) {
				$is_secure = true;
		}
		$protocol = $is_secure ? 'https://' : 'http://';

		$our_site_url = get_site_url();
		$our_site_url = str_replace( $protocol, '', $our_site_url );
		if ($protocol === 'https://') {
			replace_in_file(dirname(__FILE__).'/demo.sql', 'http://', $protocol);
		}
		replace_in_file(dirname(__FILE__).'/demo.sql', 'dash.themes.zone', $our_site_url);
		if ( $wpdb->prefix === 'wp_') {
			replace_in_file(dirname(__FILE__).'/demo.sql', 'wp_', $wpdb->prefix);
		} else {
			replace_in_file(dirname(__FILE__).'/demo.sql', "'wp_", "'".$wpdb->prefix);
			replace_in_file(dirname(__FILE__).'/demo.sql', "`wp_", "`".$wpdb->prefix);
		}

		define('DB_CHANGED', TRUE);
	}

	$query = "";
	$query_result = "";
	$sql = file(dirname(__FILE__).'/demo.sql');
	foreach ($sql as $key=>$line) {
		$line = trim($line);
		if ($line != "" && substr($line, 0, 2) != '--') {
			$query .= $line;
			if (substr($line, -1) == ';') {
				$query_result = $wpdb->query($query);
				if ($query_results === FALSE) {
	    		echo($query);
	    	}
		 		$query = "";
	    }
		}
	}
	unset ($line);
}
register_activation_hook( __FILE__, 'sample_data_installer_activate' );

function replace_in_file($FilePath, $OldText, $NewText) {
  if(file_exists($FilePath)===TRUE) {
    if(is_writeable($FilePath)) {
      $FileContent = file_get_contents($FilePath);
      $FileContent = str_replace($OldText, $NewText, $FileContent);
      file_put_contents($FilePath, $FileContent);
		} else {
			deactivate_plugins( basename( __FILE__ ) );
			wp_die(
			'<div class="notice error">
	 				<p>File (demo.sql) is not writable! Please change permissions to 777(wr-wr-wr).</p>
					<a href="' . admin_url( 'plugins.php' ) . '">go back</a>
	 		 </div>'
			);
		}
  }
}

function sample_data_plugin_activated() {
  if(get_option('sample_data_installer_plugin_activated', false)) {
    delete_option('sample_data_installer_plugin_activated');
    add_action('admin_notices', create_function('', 'echo
    \'<div class="notice updated success is-dismissible"><p>Sample Data Installed successfully</p></div>\';'));
  }
}
add_action('admin_init', 'sample_data_plugin_activated');
