<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace RT\ClawyerCore\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use RT\ClawyerCore\Helper\Fns;
use RT\ClawyerCore\Abstracts\ElementorBase;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MovingBox extends ElementorBase {

	public function __construct( $data = [], $args = null ) {
		$this->rt_name = esc_html__( 'Moving Box', 'clawyer-core' );
		$this->rt_base = 'rt-moving-box';
		parent::__construct( $data, $args );
	}

	protected function register_controls() {

		/* -- Info Elements Options -- */
		$this->__contact_icon_settings_controls();
	}

	// Icon Box Settings
	protected function __contact_icon_settings_controls() {
		$this->start_controls_section(
			'moving_box_settings',
			[
				'label'     => esc_html__( 'Icon Box', 'clawyer-core' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'moving_image',
			[
				'label'   => esc_html__( 'Moving Image', 'clawyer-core' ),
				'type'    => Controls_Manager::MEDIA,
			]
		);
		$this->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'clawyer-core' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => '#',
				],
			]
		);
		$this->add_control(
			'link_icon',
			[
				'type'        => Controls_Manager::ICONS,
				'label'   => esc_html__( 'Link Icon', 'clawyer-core' ),
				'default' => array(
					'value' => 'fas fa-smile-wink',
					'library' => 'fa-solid',
				),
			]
		);
		$this->add_responsive_control(
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
					'{{WRAPPER}} .item-icon-box i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .item-icon-box svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .item-icon-box img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
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
					'{{WRAPPER}} .item-icon-box' => 'width: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);
		$this->add_responsive_control(
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
					'{{WRAPPER}} .item-icon-box' => 'height: {{SIZE}}{{UNIT}} !important;',
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
					'{{WRAPPER}} .item-icon-box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
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
					'{{WRAPPER}} .item-icon-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			]
		);


		$this->start_controls_tabs( 'iconbox_tab_style' );
		// Normal tab.
		$this->start_controls_tab(
			'iconbox_style_normal',
			[
				'label' => esc_html__( 'Normal', 'clawyer-core' ),
			]
		);
		$this->add_control(
			'icon_color',
			[
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Color', 'clawyer-core' ),
				'selectors' => array(
					'{{WRAPPER}} .moving-text-wrap .icon-box .icon i::before' => 'color: {{VALUE}}',
				),
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
						'label' => esc_html__('Icon box Background', 'clawyer-core'),
						'default' => 'classic',
					]
				],
				'selector' => '{{WRAPPER}} .moving-text-wrap .icon-box .icon',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'icon_border',
				'label'     => esc_html__( 'Border', 'clawyer-core' ),
				'selector'  => '{{WRAPPER}} .moving-text-wrap .icon-box .icon',
			]
		);
		$this->add_responsive_control(
			'iconbox_radius',
			[
				'type'    => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'label'   => esc_html__( 'Radius', 'clawyer-core' ),
				'selectors' => array(
					'{{WRAPPER}} .moving-text-wrap .icon-box .icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			]
		);
		$this->end_controls_tab();

		// Hover tab.
		$this->start_controls_tab(
			'iconbox_style_hover',
			[
				'label' => esc_html__( 'Hover', 'clawyer-core' ),
			]
		);
		$this->add_control(
			'icon_h_color',
			[
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Color', 'clawyer-core' ),
				'selectors' => array(
					'{{WRAPPER}} .moving-text-wrap .icon-box .icon i::before' => 'color: {{VALUE}}',
				),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'icon_h_box_bg',
				'label'    => esc_html__( 'Background', 'clawyer-core' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'fields_options' => [
					'background' => [
						'label' => esc_html__('Icon Hover Box Background', 'clawyer-core'),
						'default' => 'classic',
					]
				],
				'selector' => '{{WRAPPER}} .moving-text-wrap:hover .icon-box .icon',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'icon_hover_border',
				'label'     => esc_html__( 'Border', 'clawyer-core' ),
				'selector'  => '{{WRAPPER}} .moving-text-wrap:hover .icon-box .icon',
			]
		);
		$this->add_responsive_control(
			'iconbox_h_radius',
			[
				'type'    => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'label'   => esc_html__( 'Radius', 'clawyer-core' ),
				'selectors' => array(
					'{{WRAPPER}} .moving-text-wrap:hover .icon-box .icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	protected function render() {
		$data = $this->get_settings();

		$template = 'view-1';

//		echo '<pre>';
//		print_r($data);
//		echo '</pre>';

		Fns::get_template( "elementor/moving-box/$template", $data );
	}

}