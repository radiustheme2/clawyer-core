<?php

namespace RT\ClawyerCore\Controllers;
if ( ! defined( 'ABSPATH' ) ) exit;

use RT\Clawyer\Helpers\Constants;
use RT\ClawyerCore\Traits\SingletonTraits;

/**
 * Enqueue.
 */
class ScriptController {
	use SingletonTraits;

	/**
	 * register default hooks and actions for WordPress
	 * @return
	 */
	public function __construct() {
//		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
//		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ], 99 );
	}

	/**
	 * Enqueue Scripts
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'repeater-button', CLAWYER_CORE_BASE_URL . 'assets/css/repeater-button.css', [], '1.0.0' );
		wp_enqueue_script( 'repeater-button', CLAWYER_CORE_BASE_URL . 'assets/js/repeater-button.js', [ 'jquery' ], '1.0.0', true );
		$this->localized_scripts();
	}


	private function localized_scripts(){
		$localize_data = array(
			// Doctor Translate Text
			'newItem' => esc_html__('Add New Item' , 'clawyer-core'),
		);
		wp_localize_script( 'repeater-button', 'ClawyerCoreObj', $localize_data );
	}

}
