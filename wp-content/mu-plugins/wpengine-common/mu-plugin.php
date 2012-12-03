<?php
/*
  Plugin Name: WP Engine System
  Plugin URI: http://wpengine.com/plugins
  Description: WP Engine-specific services and options
  Author: WP Engine
  Version: 2.0.34

  Changelog: (see changelog.txt)
 */

// Our plugin
define( 'WPE_PLUGIN_VERSION', '2.0.34' );

//setup wpe plugin url
if(is_multisite()) {
	define('WPE_PLUGIN_URL', network_site_url('/wp-content/mu-plugins/wpengine-common'));
} else {
	define( 'WPE_PLUGIN_URL', content_url('/mu-plugins/wpengine-common') );
}

require_once(dirname(__FILE__)."/wpengine-common/plugin.php");

// Login-Lockout plugin, indirect here so that it works with mu-plugin rules
$lla_path = dirname(__FILE__)."/limit-login-attempts/limit-login-attempts.php";
if ( file_exists($lla_path) ) { require_once($lla_path); }
