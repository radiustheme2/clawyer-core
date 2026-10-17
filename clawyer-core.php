<?php
/*
Plugin Name: Clawyer Core
Plugin URI: https://www.radiustheme.com
Description: Clawyer Theme Core Plugin
Version: 1.2.2
Author: RadiusTheme
Author URI: https://www.radiustheme.com
Text Domain: clawyer-core
Domain Path: /languages
License: GPLv2 or later
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'CLAWYER_CORE' ) ) {
	define( 'CLAWYER_CORE', '1.2.2' );
	define( 'CLAWYER_ITEM', 'rt1224' );
	define( 'CLAWYER_CORE_PREFIX', 'clawyer' );
	define( 'CLAWYER_CORE_BASE_URL', plugin_dir_url( __FILE__ ) );
	define( 'CLAWYER_CORE_BASE_DIR', plugin_dir_path( __FILE__ ) );
	define( 'CLAWYER_CORE_DEMO_CONTENT', plugin_dir_path( __FILE__ ) . 'demo-importer/' );
	define( 'CLAWYER_CORE_DEMO_URL', plugin_dir_url( __FILE__ ) . 'demo-content/' );
}

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) :
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
endif;

if ( class_exists( 'RT\\ClawyerCore\\Init' ) ) :
	RT\ClawyerCore\Init::instance();
endif;

require_once CLAWYER_CORE_DEMO_CONTENT . 'init.php';