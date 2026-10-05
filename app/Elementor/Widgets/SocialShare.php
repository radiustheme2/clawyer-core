<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace RT\ClawyerCore\Elementor\Widgets;

use RT\ClawyerCore\Abstracts\ElementorWidgetBase;
use RT\ClawyerCore\Helper\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * SocialShare class.
 */
class SocialShare extends ElementorWidgetBase {

	/**
	 * Control Fields
	 *
	 * @var array
	 */
	private $control_fields = [];

	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->clawyer_name = esc_html__( 'Social Share', 'clawyer-core' );
		$this->clawyer_base = 'rtsb-social-share';
		parent::__construct( $data, $args );
	}

	/**
	 * Style dependencies.
	 *
	 * @return array
	 */
	public function get_style_depends(): array {
		if ( ! $this->is_edit_mode() ) {
			return [];
		}

		return [];
	}

	/**
	 * Script dependencies.
	 *
	 * @return array
	 */
	public function get_script_depends(): array {
		if ( ! $this->is_edit_mode() ) {
			return [];
		}

		return [];
	}

	/**
	 * Controls for layout tab
	 *
	 * @return SocialShare
	 */
	protected function content_tab() {
		$fields['layout_section'] = $this->start_section(
			esc_html__( 'Presets', 'clawyer-core' ),
			'CONTENT'
		);

		$fields['image_layout'] = [
			'type'        => 'rt-image-select',
			'label'       => esc_html__( 'Choose Layout', 'clawyer-core' ),
			'description' => esc_html__( 'Choose layout', 'clawyer-core' ),
			'options'     => [
				'1' => [
					'title' => esc_html__( 'Layout 1', 'clawyer-core' ),
					'url'   => esc_url( Fns::get_assets_url( 'images/layout/grid-Layout-1.png' ) ),
				],
				'2' => [
					'title' => esc_html__( 'Layout 2', 'clawyer-core' ),
					'url'   => esc_url( Fns::get_assets_url( 'images/layout/grid-Layout-1.png' ) ),
				],
				'3' => [
					'title'  => esc_html__( 'Layout 3', 'clawyer-core' ),
					'url'    => esc_url( Fns::get_assets_url( 'images/layout/grid-Layout-1.png' ) ),
					'is_pro' => true
				],
			],
			'default'     => 'bottom',
		];

		$fields['exclude_posts'] = [
			'type'        => 'rt-select2',
			'label'       => esc_html__( 'Exclude Products', 'clawyer-core' ),
			'description' => esc_html__( 'Please select the products to show. Leave it blank to exclude none.', 'clawyer-core' ),
			'source_name' => 'post_type',
			'source_type' => 'post',
			'multiple'    => true,
			'label_block' => true,
		];

		$fields['layout_direction'] = [
			'type'    => 'choose',
			'label'   => esc_html__( 'Layout Direction', 'clawyer-core' ),
			'options' => [
				'horizontal' => [
					'title' => esc_html__( 'Horizontal', 'clawyer-core' ),
					'icon'  => 'eicon-navigation-horizontal',
				],
				'vertical'   => [
					'title' => esc_html__( 'Vertical', 'clawyer-core' ),
					'icon'  => 'eicon-navigation-vertical',
				],
			],
			'default' => 'horizontal',
			'toggle'  => true,
		];

		$fields['layout_section_end'] = $this->end_section();

		$this->control_fields = array_merge( $this->control_fields, $fields );

		return $this;
	}

	/**
	 * Controls for style tab
	 *
	 * @return SocialShare
	 */
	protected function style_tab() {
		$fields['style_section'] = $this->start_section(
			esc_html__( 'Presets', 'clawyer-core' ),
			'STYLE'
		);

		$fields['rtsb_el_typography'] = [
			'mode'     => 'group',
			'type'     => 'typography',
			'selector' => '{{WRAPPER}}',
		];

		$fields['alignment'] = [
			'mode'      => 'responsive',
			'type'      => 'choose',
			'label'     => esc_html__( 'Alignment', 'clawyer-core' ),
			'options'   => [
				'left'   => [
					'title' => esc_html__( 'Left', 'clawyer-core' ),
					'icon'  => 'eicon-text-align-left',
				],
				'center' => [
					'title' => esc_html__( 'Center', 'clawyer-core' ),
					'icon'  => 'eicon-text-align-center',
				],
				'right'  => [
					'title' => esc_html__( 'Right', 'clawyer-core' ),
					'icon'  => 'eicon-text-align-right',
				],
			],
			'selectors' => [
				'{{WRAPPER}}'
			],
		];

		$fields['color'] = [
			'type'      => 'color',
			'label'     => esc_html__( 'Color', 'clawyer-core' ),
			'separator' => 'default',
			'selectors' => [
				'{{WRAPPER}}'
			],
		];

		$fields['rtsb_el_border'] = [
			'mode'           => 'group',
			'type'           => 'border',
			'label'          => esc_html__( 'Border', 'clawyer-core' ),
			'selector'       => '{{WRAPPER}}',
			'fields_options' => [
				'color' => [
					'label' => esc_html__( 'Border Color', 'clawyer-core' ),
				],
			],
			'separator'      => 'default',
		];

		$fields['padding'] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Padding', 'clawyer-core' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selector'   => '{{WRAPPER}}',
			'separator'  => 'default',
		];

		$fields['show_share_pre_text'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Header Text?', 'clawyer-core' ),
			'description' => esc_html__( 'Switch on to show social sharing text before icons.', 'clawyer-core' ),
			'label_on'    => esc_html__( 'On', 'clawyer-core' ),
			'label_off'   => esc_html__( 'Off', 'clawyer-core' ),
			'separator'   => 'default',
		];
		$fields['share_pre_text']      = [
			'type'        => 'text',
			'label'       => esc_html__( 'Header Text', 'clawyer-core' ),
			'description' => esc_html__( 'Enter the text to show before icons.', 'clawyer-core' ),
			'default'     => esc_html__( 'Share:', 'clawyer-core' ),
			'label_block' => true,
			'condition'   => [ 'show_share_pre_text' => [ 'yes' ] ],
		];

		$fields['share_toggle_icon'] = [
			'type'        => 'icons',
			'label'       => esc_html__( 'Choose Toggle Icon', 'clawyer-core' ),
			'description' => esc_html__( 'Please choose the share toggle icon.', 'clawyer-core' ),
			'condition'   => [ 'share_toggle' => [ 'yes' ] ],
		];

		$fields['share_platforms'] = [
			'type'        => 'repeater',
			'mode'        => 'repeater',
			'label'       => esc_html__( 'Add Sharing Platforms', 'clawyer-core' ),
			'separator'   => 'default',
			'title_field' => '{{{ share_items }}}',
			'fields'      => [
				'share_items' => [
					'label'     => esc_html__( 'Platform Name', 'clawyer-core' ),
					'type'      => 'select',
					'separator' => 'default',
					'options'   => [
						'opt1' => esc_html__( 'Opt1', 'clawyer-core' ),
						'opt2' => esc_html__( 'Opt2', 'clawyer-core' ),
					],
				],
				'share_text'  => [
					'label' => esc_html__( 'Sharing Text', 'clawyer-core' ),
					'type'  => 'text',
				],
			],
			'default'     => [
				'share_items' => 'Hello select',
				'share_text'  => 'Hello text',
			],
		];


		//TODO: Tab Start
		$fields['color_tabs'] = $this->start_tab_group();
		$fields['color_tab']  = $this->start_tab( esc_html__( 'Normal', 'clawyer-core' ) );

		$fields['color1'] = [
			'type'      => 'color',
			'label'     => esc_html__( 'Color', 'clawyer-core' ),
			'selector'  => '{{WRAPPER}}',
			'separator' => 'default',
		];

		$fields['color_tab_end']   = $this->end_tab();
		$fields['hover_color_tab'] = $this->start_tab( esc_html__( 'Hover', 'clawyer-core' ) );

		$fields['color2'] = [
			'type'      => 'color',
			'label'     => esc_html__( 'Hover Color', 'clawyer-core' ),
			'selector'  => '{{WRAPPER}}',
			'separator' => 'default',
		];


		$fields['hover_color_tab_end'] = $this->end_tab();
		$fields['color_tabs_end']      = $this->end_tab_group();
		//TODO: Tab End

		$fields['style_section_end'] = $this->end_section();

		return $fields;
	}


	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		$this->content_tab()->style_tab();
		if ( empty( $this->control_fields ) ) {
			return [];
		}

		return $this->control_fields;
	}

	/**
	 * Render Function
	 *
	 * @return void
	 */
	protected function render() {
		$data     = $this->get_settings_for_display();
		$template = 'view-1';
		Fns::get_template( "elementor/social-share/$template", $data );
	}
}
