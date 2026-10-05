<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace RT\ClawyerCore\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit;

use RT\Clawyer\Helpers\Fns;
use \WP_Widget;
use \RT_Widget_Fields;

//---------------------------------------------------------------------------
// Flickr widget
//---------------------------------------------------------------------------

class Contact_Widget extends WP_Widget {

	public function __construct() {
		$id    = CLAWYER_CORE_PREFIX . '_contact';
		$title = __( 'Clawyer: Contact', 'clawyer-core' );
		$args  = [
			'description' => esc_html__( 'Displays Contact Info', 'clawyer-core' )
		];
		parent::__construct( $id, $title, $args );
	}


	public function form( $instance ) {
		$defaults = [
			'title'   => __( 'Contact', 'clawyer-core' ),
			'address' => clawyer_option( 'rt_contact_address' ),
			'mail'    => clawyer_option( 'rt_email' ),
			'phone'   => clawyer_option( 'rt_phone' ),
			'website' => clawyer_option( 'rt_website' ),
			'shortcode_title' => '',
			'shortcode' => '',
		];

		$instance = wp_parse_args( (array) $instance, $defaults );

		$fields = [
			'title'   => [
				'label' => esc_html__( 'Title', 'clawyer-core' ),
				'type'  => 'text',
			],
			'address' => [
				'label' => esc_html__( 'Address', 'clawyer-core' ),
				'type'  => 'textarea',
			],
			'mail'    => [
				'label' => esc_html__( 'Mail', 'clawyer-core' ),
				'type'  => 'text',
			],
			'phone'   => [
				'label' => esc_html__( 'Phone', 'clawyer-core' ),
				'type'  => 'text',
			],
			'website' => [
				'label' => esc_html__( 'Website', 'clawyer-core' ),
				'type'  => 'text',
			],
			'shortcode_title'   => [
				'label' => esc_html__( 'Shortcode Title', 'clawyer-core' ),
				'type'  => 'text',
			],
			'shortcode' => [
				'label' => esc_html__( 'Shortcode', 'clawyer-core' ),
				'type'  => 'text',
			],
		];

		RT_Widget_Fields::display( $fields, $instance, $this );
	}

	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title']   = !empty( $new_instance['title'] ) ? Fns::sanitize( $new_instance[ 'title' ]) : '';
		$instance['address'] = !empty( $new_instance['address'] ) ? sanitize_textarea_field( $new_instance['address']) : '';
		$instance['mail']    = !empty( $new_instance['mail'] ) ? sanitize_email( $new_instance['mail']) : '';
		$instance['phone']   = !empty( $new_instance['phone'] ) ? Fns::sanitize( $new_instance[ 'phone' ]) : '';
		$instance['website'] = !empty( $new_instance['website'] ) ? sanitize_url( $new_instance['website']) : '';
		$instance['shortcode_title']   = !empty( $new_instance['shortcode_title'] ) ? Fns::sanitize( $new_instance[ 'shortcode_title' ]) : '';
		$instance['shortcode'] = !empty( $new_instance['shortcode'] ) ? Fns::sanitize( $new_instance['shortcode']) : '';

		return $instance;
	}

	public function widget( $args, $instance ) {

		echo wp_kses_post( $args['before_widget'] );
		$title = apply_filters( 'widget_title', $instance['title'] );
		if ( ! empty( $title ) ) {
			echo wp_kses_post(  $args['before_title'] . $title . $args['after_title'] );
		}

		echo wp_kses_post( clawyer_contact_render( $instance ) );

		//do_shortcode( "[rt_contact address='{$_address}' mail='{$_mail}' phone='{$_phone}' website='{$_website}']" );
		echo wp_kses_post( $args['after_widget'] );
	}
}