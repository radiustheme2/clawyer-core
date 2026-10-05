<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.2
 */

namespace RT\ClawyerCore\Elementor\Widgets;

use Elementor\Utils;
use RT\ClawyerCore\Helper\Fns;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use RT\ClawyerCore\Abstracts\ElementorBase;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Team extends ElementorBase {

	public function __construct( $data = [], $args = null ) {
		$this->rt_name = esc_html__( 'RT Team', 'clawyer-core' );
		$this->rt_base = 'rt-agents';
		parent::__construct( $data, $args );
	}

	protected function register_controls() {
		// widget title
		$this->start_controls_section(
			'rt_agent_grid',
			[
				'label' => esc_html__( 'Team Grid', 'clawyer-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'layout',
			[
				'label'   => esc_html__( 'Style', 'clawyer-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'style1',
				'options' => [
					'style1' => __( 'Style 1', 'clawyer-core' ),
					'style2' => __( 'Style 2', 'clawyer-core' ),
					'style3' => __( 'Style 3', 'clawyer-core' ),
				],

			]
		);

		$this->add_control(
			'image',
			[
				'label'   => __( 'Team Image', 'clawyer-core' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'    => 'thumbnail',
				'exclude' => [ 'custom' ], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
				'include' => [],
				'default' => 'large',
			]
		);

		$this->add_control(
			'team_name',
			[
				'label'       => __( 'Team Name', 'clawyer-core' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'E.g. John Doe', 'clawyer-core' ),
				'default'     => __( 'John Doe', 'clawyer-core' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'team_designation',
			[
				'label'       => __( 'Team Designation', 'clawyer-core' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'E.g. Marketing Manager', 'clawyer-core' ),
				'default'     => __( 'Marketing Manager', 'clawyer-core' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'company_name',
			[
				'label'       => __( 'Company Name', 'clawyer-core' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'E.g. Eco Builders', 'clawyer-core' ),
				'default'     => __( 'Eco Builders', 'clawyer-core' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'company_url',
			[
				'label'         => __( 'Company Link', 'clawyer-core' ),
				'type'          => \Elementor\Controls_Manager::URL,
				'placeholder'   => __( 'Company link', 'clawyer-core' ),
				'show_external' => true,
				'default'       => [
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				],
				'condition'     => [
					'company_name!' => '',
				],
				'label_block' => true,
			]
		);


		$this->add_control(
			'team_phone',
			[
				'label'       => __( 'Phone', 'clawyer-core' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( '+121 456 8976', 'clawyer-core' ),
				'default'     => __( '+121 456 8976', 'clawyer-core' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'thumb_label',
			[
				'label'       => __( 'Thumb label', 'clawyer-core' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'E.g. Thumb Label', 'clawyer-core' ),
				'default'     => __( 'Thumb Label', 'clawyer-core' ),
				'label_block' => true,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'social_title', [
				'label'   => __( 'Title', 'clawyer-core' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'List Title', 'clawyer-core' ),
			]
		);

		$repeater->add_control(
			'social_icon',
			[
				'label'   => __( 'Icon', 'clawyer-core' ),
				'type'    => Controls_Manager::ICONS,
				'default' => [
					'value'   => 'fab fa-facebook-f',
					'library' => 'solid',
				],
			]
		);

		$repeater->add_control(
			'social_link', [
				'label'       => __( 'Link ', 'clawyer-core' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$this->add_control(
			'social_icon_list',
			[
				'label'       => __( 'Social Icon List', 'clawyer-core' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'social_title' => __( 'Facebook', 'clawyer-core' ),
						'social_icon'  => [
							'value'   => 'fab fa-facebook-f',
							'library' => 'solid',
						],
						'social_link'  => '#',
					],
					[
						'social_title' => __( 'Twitter', 'clawyer-core' ),
						'social_icon'  => [
							'value'   => 'fab fa-twitter',
							'library' => 'solid',
						],
						'social_link'  => '#',
					],
					[
						'social_title' => __( 'linkedin', 'clawyer-core' ),
						'social_icon'  => [
							'value'   => 'fab fa-linkedin-in',
							'library' => 'solid',
						],
						'social_link'  => '#',
					],
				],
				'title_field' => '{{{ social_title }}}',
				'condition'   => [
					'layout' => 'style1',
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
					'{{WRAPPER}} .team-block' => 'text-align: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		/* Title Settings
		====================================================================== */
		$this->start_controls_section(
			'name_style',
			[
				'label' => __( 'Name Style', 'clawyer-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'name_typography',
				'selector' => '{{WRAPPER}} .team-name',
			]
		);

		$this->add_control(
			'name_spacing',
			[
				'label'              => __( 'Name Spacing', 'clawyer-core' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px' ],
				'selectors'          => [
					'{{WRAPPER}} .team-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'allowed_dimensions' => 'vertical',
				'default'            => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
					'isLinked' => false,
				],
			]
		);

		$this->add_control(
			'name_color',
			[
				'label'     => __( 'Name Color', 'clawyer-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .team-name' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();


		/* Designation Settings
		====================================================================== */
		$this->start_controls_section(
			'designation_style',
			[
				'label' => __( 'Designation Style', 'clawyer-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'designation_typography',
				'selector' => '{{WRAPPER}} .designation',
			]
		);

		$this->add_control(
			'designation_spacing',
			[
				'label'              => __( 'Designation Spacing', 'clawyer-core' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px' ],
				'selectors'          => [
					'{{WRAPPER}} .designation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'allowed_dimensions' => 'vertical',
				'default'            => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
					'isLinked' => false,
				],
			]
		);

		$this->add_control(
			'designation_color',
			[
				'label'     => __( 'Designation Color', 'clawyer-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .designation' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		/* Company / Agency Settings
		====================================================================== */
		$this->start_controls_section(
			'agency_style',
			[
				'label' => __( 'Company / Designation Style', 'clawyer-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'agency_typography',
				'selector' => '{{WRAPPER}} .company-name a, {{WRAPPER}} .company-name a',
			]
		);

		$this->add_control(
			'agency_spacing',
			[
				'label'              => __( 'Company Spacing', 'clawyer-core' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px' ],
				'selectors'          => [
					'{{WRAPPER}} .company-name a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'allowed_dimensions' => 'vertical',
				'default'            => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
					'isLinked' => false,
				],
			]
		);

		$this->add_control(
			'agency_color',
			[
				'label'     => __( 'Company Color', 'clawyer-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .company-name a, {{WRAPPER}} .company-name' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		/* Contact Style
		====================================================================== */
		$this->start_controls_section(
			'contact_style',
			[
				'label' => __( 'Contact Style', 'clawyer-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'contact_typography',
				'selector' => '{{WRAPPER}} .item-phn-no',
			]
		);

		$this->add_control(
			'contact_color',
			[
				'label'     => __( 'Contact Color', 'clawyer-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .item-phn-no' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		/* Thumb Meta Style
		====================================================================== */
		$this->start_controls_section(
			'thumb_meta_style',
			[
				'label' => __( 'Thumb Meta Style', 'clawyer-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'thumb_meta_typography',
				'selector' => '{{WRAPPER}} .thumb-label',
			]
		);

		$this->add_control(
			'thumb_meta_color',
			[
				'label'     => __( 'Thumb Meta Color', 'clawyer-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .thumb-label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'thumb_meta_bg',
			[
				'label'     => __( 'Thumb Meta Background', 'clawyer-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .thumb-label' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'thumb_meta_dot_color',
			[
				'label'     => __( 'Thumb Meta Dot color', 'clawyer-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .thumb-label' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		/* Contact Style
		====================================================================== */
		$this->start_controls_section(
			'social_icon_style',
			[
				'label' => __( 'Social Icon Style', 'clawyer-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'social_icon_size',
			[
				'label'      => __( 'Icon Size', 'clawyer-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 10,
						'max'  => 60,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 16,
				],
				'selectors'  => [
					'{{WRAPPER}} .rt-social-item a'   => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs(
			'social_icon_style_tabs'
		);

		$this->start_controls_tab(
			'social_icon_normal_tab',
			[
				'label' => __( 'Normal', 'clawyer-core' ),
			]
		);

		$this->add_control(
			'social_icon_color',
			[
				'label'     => __( 'Social Icon Color', 'clawyer-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-agents-wrapper .team-social-dropdown .social-item .social-link'          => 'color: {{VALUE}}',
					'{{WRAPPER}} .rt-agents-wrapper .team-social-dropdown .social-item .social-link svg path' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_icon_bg_color',
			[
				'label'     => __( 'Social Icon Background', 'clawyer-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-agents-wrapper .team-social-dropdown .social-item .social-link' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'social_icon_hover_tab',
			[
				'label' => __( 'Hover', 'clawyer-core' ),
			]
		);

		$this->add_control(
			'social_icon_color_hover',
			[
				'label'     => __( 'Social Icon Color Hover', 'clawyer-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-agents-wrapper .team-social-dropdown .social-item .social-link:hover'          => 'color: {{VALUE}}',
					'{{WRAPPER}} .rt-agents-wrapper .team-social-dropdown .social-item .social-link:hover svg path' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_icon_bg_color_hoer',
			[
				'label'     => __( 'Social Icon Background Hover', 'clawyer-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-agents-wrapper .team-social-dropdown .social-item .social-link:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$data = $this->get_settings();

		$template = 'view-1';
		if ( 'style2' == $data['layout'] ) {
			$template = 'view-2';
		} elseif ( 'style3' == $data['layout'] ) {
			$template = 'view-3';
		}
		Fns::get_template( "elementor/team/$template", [ 'data' => $data ] );
	}

}