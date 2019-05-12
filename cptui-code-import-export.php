<?php
/**
 * Plugin Name: Custom Post Types UI Code Import Export
 * Plugin URI: https://github.com/alemadlei-tech/cptui-code-import-export
 * Description: Allows automatic code export / import for custom post types to a location.
 * Version: 1.0
 * Author: Alejandro Esteban Madrigal Leiva <me@alemadlei.tech>
 * License: GPL-3.0
 **/

if ( defined( 'WP_CLI' ) && WP_CLI ) {
  require_once dirname( __FILE__ ) . '/inc/class-plugin-cli-import.php';
}

