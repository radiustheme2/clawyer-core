<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace RT\ClawyerCore\Hooks;

if ( ! defined( 'ABSPATH' ) ) exit;

use RT\ClawyerCore\Traits\SingletonTraits;

class FilterHooks {
	use SingletonTraits;


	public function __construct() {
		//Add user contact info
		add_filter( 'user_contactmethods', [ __CLASS__, 'rt_user_extra_contact_info' ] );
		add_filter( 'the_password_form', [ __CLASS__, 'rt_post_password_form' ] );
		//remove admin bar
		add_action( 'after_setup_theme', [ __CLASS__, 'remove_admin_bar' ], 999 );
	}

	/**
	 * Remove admin bar
	 * @return void
	 */
	public static function remove_admin_bar() {
		$remove_admin_bar = clawyer_option( 'rt_remove_admin_bar' );
		if ( $remove_admin_bar && ! current_user_can( 'administrator' ) && ! is_admin() ) {
			show_admin_bar( false );
		}
	}

	/* User Contact Info */
	public static function rt_user_extra_contact_info( $contactmethods ) {
		$contactmethods['rt_phone']     = __( 'Phone Number', 'clawyer-core' );
		$contactmethods['rt_facebook']  = __( 'Facebook', 'clawyer-core' );
		$contactmethods['rt_twitter']   = __( 'Twitter', 'clawyer-core' );
		$contactmethods['rt_linkedin']  = __( 'LinkedIn', 'clawyer-core' );
		$contactmethods['rt_vimeo']     = __( 'Vimeo', 'clawyer-core' );
		$contactmethods['rt_youtube']   = __( 'Youtube', 'clawyer-core' );
		$contactmethods['rt_instagram'] = __( 'Instagram', 'clawyer-core' );
		$contactmethods['rt_pinterest'] = __( 'Pinterest', 'clawyer-core' );
		$contactmethods['rt_reddit']    = __( 'Reddit', 'clawyer-core' );

		return $contactmethods;
	}

	/*
	 * change post password from
	 */
	public static function rt_post_password_form() {
		global $post;
		$label  = 'pwbox-' . ( empty( $post->ID ) ? wp_rand() : $post->ID );
		$output = '<form action="' . esc_url( home_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="post-password-form" method="post">
		<p>' . esc_html_e( 'This content is password protected. To view it please enter your password below:', 'clawyer-core' ) . '</p>
		<p><label for="' . $label . '"><span class="pass-label">' . esc_html_e( 'Password:', 'clawyer-core' ) . ' </span><input name="post_password" id="' . $label
		          . '" type="password" size="20" /> <input type="submit" name="Submit" value="' . esc_attr_e( 'Enter', 'clawyer-core' ) . '" /></label></p></form>
		';

		return $output;
	}

}