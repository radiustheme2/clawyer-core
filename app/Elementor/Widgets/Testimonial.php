<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace RT\ClawyerCore\Elementor\Widgets;

use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use RT\ClawyerCore\Helper\Fns;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use RT\ClawyerCore\Abstracts\ElementorBase;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Testimonial extends ElementorBase {

	public function __construct( $data = [], $args = null ) {
		$this->rt_name = esc_html__( 'RT Testimonial', 'clawyer-core' );
		$this->rt_base = 'rt-testimonial';
		parent::__construct( $data, $args );
	}

	protected function register_controls() {

		/* -- Content Settings -- */
		$this->__testimonial_content_settings();
		$this->__testimonial_grid_settings();
		$this->__testimonial_carousel_settings();

		$this->__testimonial_box_styles();
		$this->__testimonial_quote_icon_styles();
		$this->__testimonial_thumb_styles();
		$this->__testimonial_name_styles();
		$this->__testimonial_designation_styles();
		$this->__testimonial_title_styles();
		$this->__testimonial_content_styles();
		$this->__testimonial_rating_styles();
	}

	protected function __testimonial_content_settings() {
		$this->start_controls_section(
			'sec_general',
			[
				'label' => esc_html__( 'General', 'clawyer-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'icon',
			[
				'type'        => Controls_Manager::ICONS,
				'label'   => esc_html__( 'Quote Icon', 'clawyer-core' ),
				'default' => array(
					'value' => 'fas fa-quote-left',
					'library' => 'fa-solid',
				),
			]
		);
		$repeater->add_control(
			'image',
			[
				'label'   => esc_html__( 'Choose Image', 'clawyer-core' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		$repeater->add_control(
			'name',
			[
				'label'       => esc_html__( 'Name', 'clawyer-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Enter Name', 'clawyer-core' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'designation',
			[
				'label'       => esc_html__( 'Designation', 'clawyer-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Enter Designation', 'clawyer-core' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'clawyer-core' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'content',
			[
				'label'   => esc_html__( 'Content', 'clawyer-core' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Enter Designation', 'clawyer-core' ),
			]
		);
		$repeater->add_control(
			'rating',[
				'type' => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Rating', 'clawyer-core' ),
				'options' => array(
					'1' => esc_html__( 'Rating 1', 'clawyer-core' ),
					'2' => esc_html__( 'Rating 2', 'clawyer-core' ),
					'3' => esc_html__( 'Rating 3', 'clawyer-core' ),
					'4' => esc_html__( 'Rating 4', 'clawyer-core' ),
					'5' => esc_html__( 'Rating 5', 'clawyer-core' ),
				),
			]
		);

		$this->add_control(
			'layout',
			[
				'type'    	  => Controls_Manager::SELECT,
				'label'   	  => esc_html__( 'Layout', 'clawyer-core' ),
				'options' 	  => array(
					'slider'  => esc_html__( 'Slider', 'clawyer-core' ),
					'grid' 	  => esc_html__( 'Grid', 'clawyer-core' ),
				),
				'default' 	  => 'grid',
				'description' => esc_html__( 'Testimonial Layout. Default: Grid', 'clawyer-core' ),
			]
		);
		$this->add_control(
			'style',
			[
				'type'    	  => Controls_Manager::SELECT,
				'label'   	  => esc_html__( 'Styles', 'clawyer-core' ),
				'options' 	  => array(
					'1'  => esc_html__( 'Style 1', 'clawyer-core' ),
					'2'  => esc_html__( 'Style 2', 'clawyer-core' ),
					'3'  => esc_html__( 'Style 3', 'clawyer-core' ),
					'4'  => esc_html__( 'Style 4', 'clawyer-core' ),
				),
				'default' 	  => '1',
				'description' => esc_html__( 'Testimonial Layout. Default: 1', 'clawyer-core' ),
			]
		);
		$this->add_control(
			'items',
			[
				'label'       => esc_html__( 'Testimonial List', 'clawyer-core' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'name'        => esc_html__( 'Maria Zokatti', 'clawyer-core' ),
						'designation' => esc_html__( 'Marketing Manager', 'clawyer-core' ),
						'content'     => esc_html__( '“ I believe in lifelong learning and kola is an great any more learn as recommend all my friends “',
							'clawyer-core' ),
					],
					[
						'name'        => esc_html__( 'John Doe', 'clawyer-core' ),
						'designation' => esc_html__( 'WordPress Developer', 'clawyer-core' ),
						'content'     => esc_html__( '“ I believe in lifelong learning and kola is an great any more learn as recommend all my friends “',
							'clawyer-core' ),
					],
					[
						'name'        => esc_html__( 'Kent Odeldan', 'clawyer-core' ),
						'designation' => esc_html__( 'Web Designer', 'clawyer-core' ),
						'content'     => esc_html__( '“ I believe in lifelong learning and kola is an great any more learn as recommend all my friends “', 'clawyer-core' ),
					],
				],
				'title_field' => '{{{ name }}}',
			]
		);
		$this->add_control(
			'rating',
			[
				'label'        => esc_html__( 'Rating', 'clawyer-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'clawyer-core' ),
				'label_off'    => esc_html__( 'Hide', 'clawyer-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_responsive_control(
			'align',
			[
				'type'    => Controls_Manager::CHOOSE,
				'label'   => esc_html__( 'Title Alignment', 'clawyer-core' ),
				'options' => $this->rt_alignment_options(),
				'prefix_class' => 'elementor-align-',
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .testimonial-box' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();
	}

	protected function __testimonial_grid_settings() {
		// Grid responsive
		$this->start_controls_section(
			'section_grid_options',
			[
				'label' => __( 'Grid Devices', 'clawyer-core' ),
				'condition' => [
					'layout' => 'grid',
				],
			]
		);
		$this->add_control(
			'gridcolumn-popover-toggle',
			[
				'label'        => __( 'Grid Column', 'clawyer-core' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'Default', 'clawyer-core' ),
				'label_on'     => __( 'Custom', 'clawyer-core' ),
				'return_value' => 'yes',
				'condition' => [
					'layout' => 'grid',
				],
			]
		);
		$this->start_popover();

		$this->add_control(
			'grid_column_desktop',
			[
				'label'   => esc_html__( 'Grid Column for Desktop', 'clawyer-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '4',
				'options' => $this->rt_grid_options(),
				'condition' => [
					'layout' => 'grid',
				],
			]
		);

		$this->add_control(
			'grid_column_tab',
			[
				'label'   => esc_html__( 'Grid Column for Tab', 'clawyer-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '6',
				'options' => $this->rt_grid_options(),
				'condition' => [
					'layout' => 'grid',
				],
			]
		);

		$this->add_control(
			'grid_column_mobile',
			[
				'label'   => esc_html__( 'Grid Column for Mobile', 'clawyer-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '12',
				'options' => $this->rt_grid_options(),
				'condition' => [
					'layout' => 'grid',
				],
			]
		);
		$this->end_popover();

		$this->end_controls_section();
	}

	protected function __testimonial_carousel_settings() {
		$this->start_controls_section(
			'carousel_settings',
			[
				'label'     => esc_html__( 'Carousel Settings', 'clawyer-core' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition'   => array( 'layout' => array( 'slider' ) ),
			]
		);

		$this->add_control(
			'display_pagination',
			[
				'label'        => esc_html__( 'Paginations', 'clawyer-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'clawyer-core' ),
				'label_off'    => esc_html__( 'Hide', 'clawyer-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'   => array( 'layout' => array( 'slider' ) ),
			]
		);

		$this->add_control(
			'display_arrow',
			[
				'label'        => esc_html__( 'Arrow', 'clawyer-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'clawyer-core' ),
				'label_off'    => esc_html__( 'Hide', 'clawyer-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'   => array( 'layout' => array( 'slider' ) ),
			]
		);

		$this->add_control(
			'slider_loop',
			[
				'label'        => esc_html__( 'Infinite', 'clawyer-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'clawyer-core' ),
				'label_off'    => esc_html__( 'No', 'clawyer-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'   => array( 'layout' => array( 'slider' ) ),
			]
		);

		$this->add_control(
			'slider_autoplay',
			[
				'label'        => esc_html__( 'Autoplay', 'clawyer-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'clawyer-core' ),
				'label_off'    => esc_html__( 'No', 'clawyer-core' ),
				'return_value' => 'yes',
				'default'      => false,
				'condition'   => array( 'layout' => array( 'slider' ) ),
			]
		);

		$this->add_control(
			'slider_autoplay_delay',
			[
				'label'     => esc_html__( 'Autoplay Speed', 'clawyer-core' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1000,
				'max'       => 5000,
				'step'      => 500,
				'default'   => 3000,
				'condition' => [
					'slider_autoplay' => 'yes',
					'layout' => 'slider',
				],
			]
		);

		$this->add_control(
			'centeredSlides',
			[
				'label'        => esc_html__( 'Centered Slides', 'clawyer-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'clawyer-core' ),
				'label_off'    => esc_html__( 'No', 'clawyer-core' ),
				'return_value' => 'yes',
				'default'      => false,
				'description'  => esc_html__( 'If you use centered slider options then default column will not working.', 'clawyer-core' ),
				'condition'   => array( 'layout' => array( 'slider' ) ),
			]
		);

		$this->add_control(
			'speed',
			[
				'label'   => esc_html__( 'Speed', 'clawyer-core' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 100,
				'max'     => 5000,
				'step'    => 100,
				'default' => 2000,
				'condition'   => array( 'layout' => array( 'slider' ) ),
			]
		);

		$this->add_control(
			'space',
			[
				'label'   => esc_html__( 'Space', 'clawyer-core' ),
				'type'    => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'size_units' => [ 'px'],
				'default' => [
					'size' => 24,
				],
				'condition'   => array( 'layout' => array( 'slider' ) ),
			]
		);
		$this->add_control(
			'effect',
			[
				'type'    => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Effects', 'clawyer-core' ),
				'default' => 'slide',
				'options' => array(
					'slide' => esc_html__( 'Slide', 'clawyer-core' ),
					'fade' => esc_html__( 'Fade', 'clawyer-core' ),
					'creative' => esc_html__( 'Creative',  'clawyer-core' ),
				),
				'condition' => [
					'layout' => 'slider',
				],
			]
		);
		$this->add_control(
			'slider_per_group',
			[
				'label'        => esc_html__( 'Slider Per Group', 'clawyer-core' ),
				'type'         => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 6,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'condition'   => array( 'layout' => array( 'slider' ) ),
			]
		);
		$this->add_control(
			'desktop',
			[
				'type'    => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Desktops: > 1600px', 'clawyer-core' ),
				'default' => '2',
				'options' => array(
					'1' => esc_html__( '1', 'clawyer-core' ),
					'2' => esc_html__( '2', 'clawyer-core' ),
					'3' => esc_html__( '3',  'clawyer-core' ),
					'4' => esc_html__( '4',  'clawyer-core' ),
					'5' => esc_html__( '5',  'clawyer-core' ),
					'6' => esc_html__( '6',  'clawyer-core' ),
				),
				'condition' => [
					'layout' => 'slider',
				],
			]
		);
		$this->add_control(
			'md_desktop',
			[
				'type'    => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Desktops: > 1200px', 'clawyer-core' ),
				'default' => '2',
				'options' => array(
					'1' => esc_html__( '1', 'clawyer-core' ),
					'2' => esc_html__( '2', 'clawyer-core' ),
					'3' => esc_html__( '3',  'clawyer-core' ),
					'4' => esc_html__( '4',  'clawyer-core' ),
					'5' => esc_html__( '5',  'clawyer-core' ),
					'6' => esc_html__( '6',  'clawyer-core' ),
				),
				'condition' => [
					'layout' => 'slider',
				],
			]
		);
		$this->add_control(
			'sm_desktop',
			[
				'type'    => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Desktops: > 992px', 'clawyer-core' ),
				'default' => '1',
				'options' => array(
					'1' => esc_html__( '1', 'clawyer-core' ),
					'2' => esc_html__( '2', 'clawyer-core' ),
					'3' => esc_html__( '3',  'clawyer-core' ),
					'4' => esc_html__( '4',  'clawyer-core' ),
					'5' => esc_html__( '5',  'clawyer-core' ),
				),
				'condition' => [
					'layout' => 'slider',
				],
			]
		);
		$this->add_control(
			'tablet',
			[
				'type'    => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Tablets: > 768px', 'clawyer-core' ),
				'default' => '1',
				'options' => array(
					'1' => esc_html__( '1', 'clawyer-core' ),
					'2' => esc_html__( '2', 'clawyer-core' ),
					'3' => esc_html__( '3',  'clawyer-core' ),
					'4' => esc_html__( '4',  'clawyer-core' ),
					'5' => esc_html__( '5',  'clawyer-core' ),
				),
				'condition' => [
					'layout' => 'slider',
				],
			]
		);
		$this->add_control(
			'mobile',
			[
				'type'    => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Phones: > 576px', 'clawyer-core' ),
				'default' => '1',
				'options' => array(
					'1' => esc_html__( '1', 'clawyer-core' ),
					'2' => esc_html__( '2', 'clawyer-core' ),
					'3' => esc_html__( '3',  'clawyer-core' ),
					'4' => esc_html__( '4',  'clawyer-core' ),
					'5' => esc_html__( '5',  'clawyer-core' ),
				),
				'condition' => [
					'layout' => 'slider',
				],
			]
		);
		$this->add_control(
			'sm_mobile',
			[
				'type'    => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Phones: > 425px', 'clawyer-core' ),
				'default' => '1',
				'options' => array(
					'1' => esc_html__( '1', 'clawyer-core' ),
					'2' => esc_html__( '2', 'clawyer-core' ),
					'3' => esc_html__( '3',  'clawyer-core' ),
					'4' => esc_html__( '4',  'clawyer-core' ),
					'5' => esc_html__( '5',  'clawyer-core' ),
				),
				'condition' => [
					'layout' => 'slider',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __testimonial_box_styles() {
		$this->start_controls_section(
			'testimonial_box_style',
			[
				'label' => esc_html__( 'Box Style', 'clawyer-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'testimonial_padding',
			[
				'type'    => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'label'   => esc_html__( 'Padding', 'clawyer-core' ),
				'selectors' => array(
					'{{WRAPPER}} .testimonial-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'testimonial_box_border',
				'label'     => esc_html__( 'Border', 'clawyer-core' ),
				'selector'  => '{{WRAPPER}} .testimonial-box',
			]
		);
		$this->add_control(
			'testimonial_box_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'clawyer-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .testimonial-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'testimonial_box_bg_color',
				'label'    => esc_html__( 'Background', 'clawyer-core' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'background' => [
						'label' => esc_html__('Background Color', 'clawyer-core'),
					]
				],
				'selector' => '{{WRAPPER}} .testimonial-box',
			]
		);
		$this->end_controls_section();
	}

	protected function __testimonial_quote_icon_styles() {
		$this->start_controls_section(
			'testimonial_quote_icon_style',
			[
				'label' => esc_html__( 'Quote Icon', 'clawyer-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'icon_color',
			[
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Color', 'clawyer-core' ),
				'selectors' => array(
					'{{WRAPPER}} .testimonial-box .quote-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .testimonial-box .quote-icon svg' => 'color: {{VALUE}}',
					'{{WRAPPER}} .testimonial-box .quote-icon svg path' => 'stroke: {{VALUE}}; fill: {{VALUE}};',
				),
			]
		);
		$this->add_control(
			'icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'clawyer-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .testimonial-box .quote-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .testimonial-box .quote-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_box_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label'   => esc_html__( 'Icon Box Style', 'clawyer-core' ),
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'icon_box_bg',
				'label'    => esc_html__( 'Background', 'clawyer-core' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'fields_options' => [
					'background' => [
						'label' => esc_html__('Icon Box Background', 'clawyer-core'),
						'default' => 'classic',
					]
				],
				'selector' => '{{WRAPPER}} .testimonial-box .quote-icon',
			]
		);
		$this->add_control(
			'iconbox_width',
			[
				'label' => esc_html__( 'Width', 'clawyer-core' ),
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
					'{{WRAPPER}} .testimonial-box .quote-icon' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'iconbox_height',
			[
				'label' => esc_html__( 'Height', 'clawyer-core' ),
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
					'{{WRAPPER}} .testimonial-box .quote-icon' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'iconbox_margin',
			[
				'type'    => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'label'   => esc_html__( 'Margin', 'clawyer-core' ),
				'selectors' => array(
					'{{WRAPPER}} .testimonial-box .quote-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			]
		);
		$this->add_responsive_control(
			'iconbox_padding',
			[
				'type'    => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'label'   => esc_html__( 'Padding', 'clawyer-core' ),
				'selectors' => array(
					'{{WRAPPER}} .testimonial-box .quote-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'iconbox_border',
				'label'     => __( 'Border', 'clawyer-core' ),
				'selector'  => '{{WRAPPER}} .testimonial-box .quote-icon',
			]
		);

		$this->add_responsive_control(
			'iconbox_radius',
			[
				'type'    => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'label'   => esc_html__( 'Border Radius', 'clawyer-core' ),
				'selectors' => array(
					'{{WRAPPER}} .testimonial-box .quote-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'infobox_box_shadow',
				'selector' => '{{WRAPPER}} .testimonial-box .quote-icon',
				'fields_options' =>
					[
						'box_shadow_type' =>
							[
								'default' =>'no'
							],
					]
			]
		);
		$this->end_controls_section();
	}

	protected function __testimonial_content_styles() {
		$this->start_controls_section(
			'testimonial_content_style',
			[
				'label' => esc_html__( 'Content', 'clawyer-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'content_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'clawyer-core' ),
				'selectors' => [
					'{{WRAPPER}} .testimonial-box .testimonial-content' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typo',
				'label'    => esc_html__( 'Typography', 'clawyer-core' ),
				'selector' => '{{WRAPPER}} .testimonial-box .testimonial-content',
			]
		);
		$this->add_responsive_control(
			'content_margin',
			[
				'type'    => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'label'   => esc_html__( 'Margin', 'clawyer-core' ),
				'selectors' => array(
					'{{WRAPPER}} .testimonial-box .testimonial-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			]
		);
		$this->end_controls_section();
	}

	protected function __testimonial_thumb_styles() {
		$this->start_controls_section(
			'testimonial_thumb_style',
			[
				'label' => esc_html__( 'User Image', 'clawyer-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'thum_display',
			[
				'label' => esc_html__( 'Thumb', 'clawyer-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'clawyer-core' ),
				'label_off' => esc_html__( 'Hide', 'clawyer-core' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'thumb_size_width',
			[
				'label'      => esc_html__( 'Thumb Size Width', 'clawyer-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 50,
						'max'  => 600,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .testimonial-box .testimonial-user-info img' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .testimonial-box .testimonial-user-info .author-img' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'=>[
					'thum_display' =>'yes'
				]
			]
		);
		$this->add_control(
			'thumb_size_height',
			[
				'label'      => esc_html__( 'Thumb Size Height', 'clawyer-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 50,
						'max'  => 600,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .testimonial-box .testimonial-user-info img' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .testimonial-box .testimonial-user-info .author-img' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition'=>[
					'thum_display' =>'yes'
				]
			]
		);
		$this->add_control(
			'border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'clawyer-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .rt-el-testimonial .testimonial-img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'=>[
					'thum_display' =>'yes'
				]
			]
		);
		$this->end_controls_section();
	}

	protected function __testimonial_name_styles() {
		$this->start_controls_section(
			'testimonial_name_style',
			[
				'label' => esc_html__( 'Name', 'clawyer-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'name_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'clawyer-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-testimonial .author-name' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'name_typo',
				'label'    => esc_html__( 'Typography', 'clawyer-core' ),
				'selector' => '{{WRAPPER}} .rt-el-testimonial .author-name',
			]
		);
		$this->end_controls_section();
	}

	protected function __testimonial_designation_styles() {
		$this->start_controls_section(
			'testimonial_designation_style',
			[
				'label' => esc_html__( 'Designation', 'clawyer-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'designation_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Designation', 'clawyer-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-testimonial .author-designation' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'designation_typo',
				'label'    => esc_html__( 'Designation', 'clawyer-core' ),
				'selector' => '{{WRAPPER}} .rt-el-testimonial .author-designation',
			]
		);
		$this->end_controls_section();
	}

	protected function __testimonial_title_styles() {
		$this->start_controls_section(
			'testimonial_title_style',
			[
				'label' => esc_html__( 'Title', 'clawyer-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'title_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Title', 'clawyer-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-testimonial .title' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typo',
				'label'    => esc_html__( 'Title', 'clawyer-core' ),
				'selector' => '{{WRAPPER}} .rt-el-testimonial .title',
			]
		);
		$this->end_controls_section();
	}

	protected function __testimonial_rating_styles() {
		$this->start_controls_section(
			'testimonial_rating_style',
			[
				'label' => esc_html__( 'Rating', 'clawyer-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'star_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Star Color', 'clawyer-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-testimonial .rating li i' => 'color: {{VALUE}}',
				]
			]
		);
		$this->add_control(
			'star_active_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Star Active Color', 'clawyer-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-testimonial .rating li i.active' => 'color: {{VALUE}}',
				]
			]
		);
		$this->end_controls_section();
	}

	protected function render() {
		$data = $this->get_settings();

		if ( 'slider' == $data['layout'] ) {
			if ( $data['slider_autoplay'] == 'yes' ) {
				$data['slider_autoplay'] = true;
			} else {
				$data['slider_autoplay'] = false;
			}
			$slider_data         = array(
				'slidesPerView'       => 1,
				'loop'                => $data['slider_loop'] == 'yes' ? true : false,
				'centeredSlides'      => $data['centeredSlides'] == 'yes' ? true : false,
				'slideToClickedSlide' => true,
				'spaceBetween'        => $data['space']['size'],
				'effect'              => $data['effect'],
				'slidesPerGroup'      => $data['slider_per_group']['size'],
				'autoplay'            => array(
					'delay' => $data['slider_autoplay_delay'],
				),
				'speed'               => $data['speed'],
				'auto'                => $data['slider_autoplay'],
				'breakpoints' => array(
					'0'    => array('slidesPerView' => 1 ),
					'425'  => array('slidesPerView' => $data['sm_mobile']),
					'576'  => array('slidesPerView' => $data['mobile']),
					'768'  => array('slidesPerView' => $data['tablet']),
					'992'  => array('slidesPerView' => $data['sm_desktop']),
					'1200' => array('slidesPerView' => $data['md_desktop']),
					'1600' => array('slidesPerView' => $data['desktop'])
				),
			);
			$data['slider_data'] = json_encode( $slider_data );
		}

		$template = 'grid';
		if ( 'slider' == $data['layout'] ) {
			$template = 'slider';
		}

		Fns::get_template( "elementor/testimonial/$template", $data );
	}

}