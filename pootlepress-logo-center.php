<?php
/*
Plugin Name: Canvas Extension - Logo Inside Nav
Plugin URI: http://pootlepress.com/canvas-extensions/
Description: An extension for WooThemes Canvas that puts the logo in the center of the primary navigation.
Version: 1.2.2
Author: PootlePress
Author URI: http://pootlepress.com/
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/
/*  Copyright 2014  Pootlepress  (email : jamie@pootlepress.co.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
	
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	require_once( 'pootlepress-logo-center-functions.php' );
    require_once( 'classes/phpQuery.php');
	require_once( 'classes/class-pootlepress-logo-center.php' );
    require_once( 'classes/class-pootlepress-canvas-options.php' );
    require_once( 'classes/class-pootlepress-updater.php');

    $GLOBALS['pootlepress_center_logo'] = new Pootlepress_Center_logo( __FILE__ );
    $GLOBALS['pootlepress_center_logo']->version = '1.2.2';

add_action('init', 'pp_lin_updater');
function pp_lin_updater()
{
    if (!function_exists('get_plugin_data')) {
        include(ABSPATH . 'wp-admin/includes/plugin.php');
    }
    $data = get_plugin_data(__FILE__);
    $wptuts_plugin_current_version = $data['Version'];
    $wptuts_plugin_remote_path = 'http://www.pootlepress.com/?updater=1';
    $wptuts_plugin_slug = plugin_basename(__FILE__);
    new Pootlepress_Updater ($wptuts_plugin_current_version, $wptuts_plugin_remote_path, $wptuts_plugin_slug);
}
?>
