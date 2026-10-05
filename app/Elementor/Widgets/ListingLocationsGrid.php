<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace RT\ClawyerCore\Elementor\Widgets;

use Elementor\Utils;
use Elementor\Controls_Manager;
use RT\ClawyerCore\Helper\Fns;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use RT\ClawyerCore\Abstracts\ElementorBase;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ListingLocationsGrid extends ElementorBase {

	public function __construct( $data = [], $args = null ) {
		$this->rt_name = esc_html__( 'Listing Locations Grid', 'clawyer-core' );
		$this->rt_base = 'rt-listing-locations-grid';
		parent::__construct( $data, $args );
	}

	protected function register_controls() {
		/* -- Settings Options -- */
		$this->__options_settings_controls();
		$this->__visibility_settings_controls();

		/* -- Style -- */
		$this->__content_style_settings_controls();
		$this->__grid_responsive_settings_controls();
	}

	protected function __options_settings_controls() {
		$this->start_controls_section(
			'sec_general',
			[
				'label' => esc_html__( 'General', 'clawyer-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'image',
			[
				'label'   => __( 'Choose Image', 'clawyer-core' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'icon',
			[
				'type'        => Controls_Manager::ICONS,
				'label'   => esc_html__( 'Icon', 'clawyer-core' ),
				'default' => array(
					'value' => 'fas fa-smile-wink',
					'library' => 'fa-solid',
				),
			]
		);

		$repeater->add_control(
			'listing_loc_id',
			[
				'label'       => __( 'Select Location', 'clawyer-core' ),
				'type'        => Controls_Manager::SELECT2,
				'options' => $this->listing_terms_slug('rtcl_location'),
				'multiple' => false,
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'counter_label',
			[
				'label'       => esc_html__( 'Count label', 'clawyer-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Listing Ads', 'clawyer-core' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'layout',
			[
				'label'   => __( 'Layout', 'clawyer-core' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'layout-1',
				'options' => [
					'layout-1' => __( 'Layout 01', 'clawyer-core' ),
					'layout-2' => __( 'Layout 02', 'clawyer-core' ),
				],
			]
		);

		$this->add_control(
			'items',
			[
				'label'       => __( 'Locations List', 'clawyer-core' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ listing_loc_id }}}',
			]
		);

		$this->add_responsive_control(
			'box_padding',
			[
				'label'      => __( 'Padding', 'clawyer-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .location-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'alignment',
			[
				'label'     => __( 'Alignment', 'clawyer-core' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'clawyer-core' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'clawyer-core' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'clawyer-core' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .location-box' => 'text-align: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}

	protected function __visibility_settings_controls() {
		//Visibility setting
		$this->start_controls_section(
			'section_option',
			[
				'label' => __( 'Visibility', 'clawyer-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_display',
			[
				'label'        => __( 'Icon Display', 'clawyer-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'clawyer-core' ),
				'label_off'    => __( 'Hide', 'clawyer-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_control(
			'count_display',
			[
				'label'        => __( 'Count Display', 'clawyer-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'clawyer-core' ),
				'label_off'    => __( 'Hide', 'clawyer-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->end_controls_section();
	}

	protected function __content_style_settings_controls() {
		//Content setting
		$this->start_controls_section(
			'content_style',
			[
				'label' => esc_html__( 'Content Style', 'clawyer-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		/* = Box Style = */
		$this->add_control(
			'fullbox_style_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label'   => esc_html__( 'Full Box', 'clawyer-core' ),
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'icon_box_bg',
				'label'    => esc_html__( 'Background', 'clawyer-core' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'background' => [
						'label' => esc_html__('Full box background', 'clawyer-core' ),
						'default' => 'classic',
					]
				],
				'selector' => '{{WRAPPER}} .location-box-2',
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'icon_box_hover_bg',
				'label'    => esc_html__( 'Background', 'clawyer-core' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'background' => [
						'label' => esc_html__('Full box hover background', 'clawyer-core' ),
						'default' => 'classic',
					]
				],
				'selector' => '{{WRAPPER}} .location-box-2::before',
			]
		);
		$this->add_responsive_control(
			'box_radius',
			[
				'type'    => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'label'   => esc_html__( 'Radius', 'clawyer-core' ),
				'selectors' => array(
					'{{WRAPPER}} .location-box-2' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} .location-box-2::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			]
		);

		/* = Content Style = */
		$this->add_control(
			'content_style_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label'   => esc_html__( 'Content Style', 'clawyer-core' ),
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typo',
				'label'    => esc_html__( 'Title Typo', 'clawyer-core' ),
				'selector' => '{{WRAPPER}} .location-box-2 .listing-location a',
			]
		);
		$this->add_responsive_control(
			'title_margin',
			[
				'label'              => esc_html__( 'Title Margin', 'clawyer-core' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px' ],
				'default'            => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '30',
					'left'     => '',
					'isLinked' => false,
				],
				'selectors'          => [
					'{{WRAPPER}} .location-box-2 .listing-location' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'count_typo',
				'label'    => esc_html__( 'Count Label Typo', 'clawyer-core' ),
				'selector' => '{{WRAPPER}} .location-box-2 .listing-count',
			]
		);
		$this->add_responsive_control(
			'count_margin',
			[
				'label'              => esc_html__( 'Count Label Margin', 'clawyer-core' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px' ],
				'default'            => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '30',
					'left'     => '',
					'isLinked' => false,
				],
				'selectors'          => [
					'{{WRAPPER}} .location-box-2 .listing-count' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);
		$this->start_controls_tabs( 'iconbox_tab_style' );
		// Normal tab.
		$this->start_controls_tab(
			'style_normal',
			[
				'label' => esc_html__( 'Normal', 'clawyer-core' ),
			]
		);
		$this->add_control(
			'title_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Title Color', 'clawyer-core' ),
				'selectors' => [
					'{{WRAPPER}} .location-box-2 .listing-location a' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'count_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Count Label Color', 'clawyer-core' ),
				'selectors' => [
					'{{WRAPPER}} .location-box-2 .listing-count' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();

		// Hover tab.
		$this->start_controls_tab(
			'style_hover',
			[
				'label' => esc_html__( 'Hover', 'clawyer-core' ),
			]
		);
		$this->add_control(
			'title_hover_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Title Color', 'clawyer-core' ),
				'selectors' => [
					'{{WRAPPER}} .location-box-2:hover .listing-location a' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'count_hover_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Count Label Color', 'clawyer-core' ),
				'selectors' => [
					'{{WRAPPER}} .location-box-2:hover .listing-count' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_control(
			'number_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label'   => esc_html__( 'Number Settings', 'clawyer-core' ),
				'separator' => 'before',
			]
		);
		$this->add_control(
			'number_color',
			[
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Number Color', 'clawyer-core' ),
				'selectors' => array(
					'{{WRAPPER}} .location-box-2 .listing-count .count' => 'color: {{VALUE}}',
				),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'number_box_bg',
				'label'    => esc_html__( 'Background', 'clawyer-core' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'fields_options' => [
					'background' => [
						'label' => esc_html__('Number Box Background', 'clawyer-core'),
						'default' => 'classic',
					]
				],
				'selector' => '{{WRAPPER}} .location-box-2 .listing-count .count',
			]
		);
		$this->add_responsive_control(
			'number_box_size',
			[
				'label' => esc_html__( 'Number Box Size', 'clawyer-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .location-box-2 .listing-count .count' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);
		$this->end_controls_section();
	}

	protected function __grid_responsive_settings_controls() {
		// Slider responsive
		$this->start_controls_section(
			'section_grid_columns',
			[
				'label' => __( 'Grid Columns', 'clawyer-core' ),
			]
		);
		$this->add_control(
			'desktop_xl',
			[
				'type'    => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Desktops xl: > 1600px', 'clawyer-core' ),
				'default' => '3',
				'options' => array(
					'12' => esc_html__( 'Column 1', 'clawyer-core' ),
					'6' => esc_html__( 'Columns 2', 'clawyer-core' ),
					'4' => esc_html__( 'Columns 3', 'clawyer-core' ),
					'3' => esc_html__( 'Columns 4', 'clawyer-core' ),
					'2' => esc_html__( 'Columns 6', 'clawyer-core' ),
				),
			]
		);
		$this->add_control(
			'desktop_lg',
			[
				'type'    => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Desktops lg: > 1200px', 'clawyer-core' ),
				'default' => '3',
				'options' => array(
					'12' => esc_html__( 'Column 1', 'clawyer-core' ),
					'6' => esc_html__( 'Columns 2', 'clawyer-core' ),
					'4' => esc_html__( 'Columns 3',  'clawyer-core' ),
					'3' => esc_html__( 'Columns 4',  'clawyer-core' ),
					'2' => esc_html__( 'Columns 6',  'clawyer-core' ),
				),
			]
		);
		$this->add_control(
			'desktop_md',
			[
				'type'    => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Desktops md: > 992px', 'clawyer-core' ),
				'default' => '1',
				'options' => array(
					'12' => esc_html__( 'Column 1', 'clawyer-core' ),
					'6' => esc_html__( 'Columns 2', 'clawyer-core' ),
					'4' => esc_html__( 'Columns 3', 'clawyer-core' ),
					'3' => esc_html__( 'Columns 4', 'clawyer-core' ),
					'2' => esc_html__( 'Columns 6', 'clawyer-core' ),
				),
			]
		);
		$this->add_control(
			'tablet',
			[
				'type'    => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Tablets sm: > 768px', 'clawyer-core' ),
				'default' => '1',
				'options' => array(
					'12' => esc_html__( 'Column 1', 'clawyer-core' ),
					'6' => esc_html__( 'Columns 2', 'clawyer-core' ),
					'4' => esc_html__( 'Columns 3', 'clawyer-core' ),
					'3' => esc_html__( 'Columns 4', 'clawyer-core' ),
					'2' => esc_html__( 'Columns 6', 'clawyer-core' ),
				),
			]
		);
		$this->add_control(
			'mobile',
			[
				'type'    => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Phones small: > 425px', 'clawyer-core' ),
				'default' => '1',
				'options' => array(
					'12' => esc_html__( 'Column 1', 'clawyer-core' ),
					'6' => esc_html__( 'Columns 2', 'clawyer-core' ),
					'4' => esc_html__( 'Columns 3', 'clawyer-core' ),
					'3' => esc_html__( 'Columns 4', 'clawyer-core' ),
					'2' => esc_html__( 'Columns 6', 'clawyer-core' ),
				),
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$data = $this->get_settings();

		$template = 'view-1';

		Fns::get_template( "elementor/listing-locations-grid/$template", $data );
	}

}