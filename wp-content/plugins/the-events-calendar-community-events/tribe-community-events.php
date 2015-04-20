<?php
/*
 Plugin Name: The Events Calendar: Community Events
 Description: Community Events is an add-on providing additional functionality to the open source plugin The Events Calendar. Empower users to submit and manage their events on your website. <a href="http://tri.be/shop/wordpress-community-events/?utm_campaign=in-app&utm_source=docblock&utm_medium=plugin-community">Check out the full feature list</a>. Need more features? Peruse our selection of <a href="http://tri.be/products/?utm_campaign=in-app&utm_source=docblock&utm_medium=plugin-community" target="_blank">plugins</a>.
 Version: 3.9
 Author: Modern Tribe, Inc.
 Author URI: http://m.tri.be/21
 Text Domain: tribe-events-community
 License: GPLv2 or later
*/

/*
Copyright 2011-2012 by Modern Tribe Inc and the contributors

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

require_once( 'tribe-community-events/tribe-community-events.php' );
require_once( 'tribe-community-events/templates.php' );
require_once( 'vendor/tribe-common-libraries/tribe-common-libraries.class.php' );
require_once( 'template-tags.php' );
require_once( 'tribe-community-events/pue.php' );
new TribeCommunityEvents_PUE( __FILE__ );

/**
 * Instantiate class and get the party started!
 *
 * @since 1.0
 */
function Tribe_CE_Load() {
	add_filter( 'tribe_tec_addons', array( 'TribeCommunityEvents', 'init_addon' ) );
	if ( class_exists( 'TribeEvents' ) && defined( 'TribeEvents::VERSION' ) && version_compare( TribeEvents::VERSION, TribeCommunityEvents::REQUIRED_TEC_VERSION, '>=' ) ) {
		TribeCommunityEvents::instance();
		TribeCommunityEvents_Templates::instance();
		require_once( 'tribe-community-events/schema.php');
		add_action( 'admin_init', array('TribeCommunityEvents_Schema', 'init'));
	} elseif ( !class_exists( 'TribeEvents' ) ) {
		add_action( 'admin_notices', 'tribe_ce_show_fail_message' );
	}
}

/**
 * Shows message if the plugin can't load due to TEC not being installed.
 *
 * @return void
 * @author Nick Ciske
 * @since 1.0
 */
function tribe_ce_show_fail_message() {
	if ( current_user_can( 'activate_plugins' ) ) {
		$url = 'plugin-install.php?tab=plugin-information&plugin=the-events-calendar&TB_iframe=true';
		$title = __( 'The Events Calendar', 'tribe-events-community' );
		echo '<div class="error"><p>'.sprintf( __( 'To begin using The Events Calendar: Community Events, please install the latest version of <a href="%s" class="thickbox" title="%s">The Events Calendar</a>.', 'tribe-events-community' ), $url, $title ) . '</p></div>';
	}
}

register_activation_hook( __FILE__ , array( 'TribeCommunityEvents', 'activateFlushRewrite' ) );


add_action( 'plugins_loaded', 'Tribe_CE_Load', 1 ); // high priority so that it's not too late for tribe_register-helpers class