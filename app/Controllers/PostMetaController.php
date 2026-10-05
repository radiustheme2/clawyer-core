<?php

namespace RT\ClawyerCore\Controllers;

if ( ! defined( 'ABSPATH' ) ) exit;

use RT\Clawyer\Helpers\Fns;
use \RT_Postmeta;
use RT\ClawyerCore\Traits\SingletonTraits;
use RT\ClawyerCore\Builder\Builder;
use RT\ClawyerCore\Helper\FnsBuilder;

class PostMetaController {
	use SingletonTraits;

	public $postmeta;

	public function __construct() {
		$this->postmeta = RT_Postmeta::getInstance();
		add_action( 'init', [ $this, 'add_meta_box' ] );
	}

	/**
	 * Add all metabox
	 * @return void
	 */
	function add_meta_box() {

		$this->postmeta->add_meta_box(
			"rt_page_settings",
			__( 'Layout Settings', 'clawyer-core' ),
			[ 'page', 'post' ],
			'',
			'',
			'high',
			[
				'fields' => [
					"rt_layout_meta_data" => [
						'label' => __( 'Layouts', 'clawyer-core' ),
						'type'  => 'group',
						'value' => $this->get_post_page_meta_args(),
					],
				],
			]
		);

		$this->postmeta->add_meta_box(
			"rt_el_builder_settings",
			__( 'Header - Footer Builder Settings', 'clawyer-core' ),
			[ 'elementor-rt-hfb' ],
			'',
			'',
			'high',
			[
				'fields' => $this->get_el_builder_meta_args(),
			] );

	}

	function get_el_builder_meta_args() {

		return apply_filters( 'clawyer_layout_meta_field', [
			'template_type' => [
				'label'   => __( 'Template Type', 'clawyer-core' ),
				'type'    => 'select',
				'options' => [
					'default' => __( 'Choose Options', 'clawyer-core' ),
					'header'  => __( 'Header', 'clawyer-core' ),
					'footer'  => __( 'Footer', 'clawyer-core' ),
				],
				'default' => 'default',
			],

			'show_on' => [
				'label'   => __( 'Show On', 'clawyer-core' ),
				'type'    => 'multi_select2',
				'options' => FnsBuilder::get_builder_type(),
				'default' => [],
				'class'   => 'rt-header-footer-select'
			],

			'choose_post' => [
				'label'       => __( 'Choose posts or pages', 'clawyer-core' ),
				'type'        => 'ajax_select',
				'data_source' => 'post',
				'default'     => [],
			],

		] );
	}

	function get_post_page_meta_args() {
		$sidebars = [ 'default' => __( 'Default from customizer', 'clawyer-core' ) ] + Fns::sidebar_lists();

		return apply_filters( 'clawyer_layout_meta_field', [
			'layout'            => [
				'label'   => __( 'Layout', 'clawyer-core' ),
				'type'    => 'select',
				'options' => [
					'default'       => __( 'Default from customizer', 'clawyer-core' ),
					'full-width'    => __( 'Full Width', 'clawyer-core' ),
					'left-sidebar'  => __( 'Left Sidebar', 'clawyer-core' ),
					'right-sidebar' => __( 'Right Sidebar', 'clawyer-core' ),
				],
				'default' => 'default',
			],
			'single_post_style' => [
				'label'   => __( 'Post View Style', 'clawyer-core' ),
				'type'    => 'select',
				'options' => [ 'default' => __( 'Default from customizer', 'clawyer-core' ) ] + Fns::single_post_style(),
				'default' => 'default',
			],
			'header_style'      => [
				'label'   => __( 'Header Style', 'clawyer-core' ),
				'type'    => 'select',
				'options' => [
					'default' => __( 'Default from customizer', 'clawyer-core' ),
					'1'       => __( 'Layout 1', 'clawyer-core' ),
					'2'       => __( 'Layout 2', 'clawyer-core' ),
					'3'       => __( 'Layout 3', 'clawyer-core' ),
				],
				'default' => 'default',
			],
			'sidebar'           => [
				'label'   => __( 'Custom Sidebar', 'clawyer-core' ),
				'type'    => 'select',
				'options' => $sidebars,
				'default' => 'default',
			],
			'top_bar'           => [
				'label'   => __( 'Top Bar Visibility', 'clawyer-core' ),
				'type'    => 'select',
				'options' => [
					'default' => __( 'Default from customizer', 'clawyer-core' ),
					'on'      => __( 'ON', 'clawyer-core' ),
					'off'     => __( 'OFF', 'clawyer-core' ),
				],
				'default' => 'default',
			],
			'topbar_style'      => [
				'label'   => __( 'Topbar Style', 'clawyer-core' ),
				'type'    => 'select',
				'options' => [
					'default' => __( 'Default from customizer', 'clawyer-core' ),
					'1'       => __( 'Layout 1', 'clawyer-core' ),
					'2'       => __( 'Layout 2', 'clawyer-core' ),
					'3'       => __( 'Layout 3', 'clawyer-core' ),
				],
				'default' => 'default',
			],
			'header_width'      => [
				'label'   => __( 'Header Width', 'clawyer-core' ),
				'type'    => 'select',
				'options' => [
					'default'   => __( 'Default from customizer', 'clawyer-core' ),
					'box-width' => __( 'Box Width', 'clawyer-core' ),
					'fullwidth' => __( 'Full Width', 'clawyer-core' ),
				],
				'default' => 'default',
			],
			'menu_alignment'    => [
				'label'   => __( 'Menu Alignment', 'clawyer-core' ),
				'type'    => 'select',
				'options' => [
					'default'     => __( 'Default from customizer', 'clawyer-core' ),
					'menu-left'   => __( 'Left Alignment', 'clawyer-core' ),
					'menu-center' => __( 'Center Alignment', 'clawyer-core' ),
					'menu-right'  => __( 'Right Alignment', 'clawyer-core' ),
				],
				'default' => 'default',
			],

			'tr_header'         => [
				'label'   => __( 'Transparent Header', 'clawyer-core' ),
				'type'    => 'select',
				'options' => [
					'default' => __( 'Default from customizer', 'clawyer-core' ),
					'on'      => __( 'ON', 'clawyer-core' ),
					'off'     => __( 'OFF', 'clawyer-core' ),
				],
				'default' => 'default',
			],
			'tr_header_color' => [
				'label'   => __( 'Transparent color', 'clawyer-core' ),
				'type'    => 'select',
				'options' => [
					'default' => __( 'Default from customizer', 'clawyer-core' ),
					'tr-header-light'    => __( 'Light Color', 'clawyer-core' ),
					'tr-header-dark'     => __( 'Dark Color', 'clawyer-core' ),
				],
				'default' => 'default',
			],
			'banner'      => [
				'label'   => __( 'Banner Visibility', 'clawyer-core' ),
				'type'    => 'select',
				'options' => [
					'default' => __( 'Default from customizer', 'clawyer-core' ),
					'on'      => __( 'ON', 'clawyer-core' ),
					'off'     => __( 'OFF', 'clawyer-core' ),
				],
				'default' => 'default',
			],
			'banner_style'      => [
				'label'   => __( 'Banner Style', 'clawyer-core' ),
				'type'    => 'select',
				'options' => [
					'default' => __( 'Default from customizer', 'clawyer-core' ),
					'1'       => __( 'Layout 1', 'clawyer-core' ),
					'2'       => __( 'Layout 2', 'clawyer-core' ),
				],
				'default' => 'default',
			],
			'breadcrumb'        => [
				'label'   => __( 'Banner Content (Breadcrumb) Visibility', 'clawyer-core' ),
				'type'    => 'select',
				'options' => [
					'default' => __( 'Default from customizer', 'clawyer-core' ),
					'on'      => __( 'ON', 'clawyer-core' ),
					'off'     => __( 'OFF', 'clawyer-core' ),
				],
				'default' => 'default',
			],
			'banner_height'     => [
				'label'   => __( 'Banner Height (px)', 'clawyer-core' ),
				'type'    => 'number',
				'default' => 'default',
			],
			'footer_style'      => [
				'label'   => __( 'Footer Layout', 'clawyer-core' ),
				'type'    => 'select',
				'options' => [
					'default' => __( 'Default from customizer', 'clawyer-core' ),
					'1'       => __( 'Layout 1', 'clawyer-core' ),
					'2'       => __( 'Layout 2', 'clawyer-core' ),
				],
				'default' => 'default',
			],
			'footer_schema'     => [
				'label'   => __( 'Footer Schema', 'clawyer-core' ),
				'type'    => 'select',
				'options' => [
					'default'      => __( 'Default from customizer', 'clawyer-core' ),
					'footer-light' => __( 'Light Footer', 'clawyer-core' ),
					'footer-dark'  => __( 'Dark Footer', 'clawyer-core' ),
				],
				'default' => 'default',
			],
			'rt_padding_top'    => [
				'label' => __( 'Padding Top (Page Content)', 'clawyer-core' ),
				'type'  => 'number',
			],
			'rt_padding_bottom' => [
				'label' => __( 'Padding Bottom (Page Content)', 'clawyer-core' ),
				'type'  => 'number',
			],
		] );
	}
}

