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
use RT\ClawyerCore\Abstracts\ElementorBase;

if ( ! defined( 'ABSPATH' ) ) exit;

class ImageBox extends ElementorBase {

	public function __construct( $data = [], $args = null ){
		$this->rt_name = __( 'Image Box', 'clawyer-core' );
		$this->rt_base = 'rt-image-box';
		parent::__construct( $data, $args );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'image_box_section',
			[
				'label' => esc_html__( 'Image Box', 'clawyer-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'styles',
			[
				'type'    => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Style', 'clawyer-core' ),
				'options' => array(
					'1' => esc_html__( 'Style 1', 'clawyer-core' ),
					'2' => esc_html__( 'Style 2', 'clawyer-core' ),
					'3' => esc_html__( 'Style 3', 'clawyer-core' ),
				),
				'default' => '1',
			]
		);
		$this->add_control(
			'main_image',
			[
				'label'   => __( 'Main Image', 'clawyer-core' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		$this->add_control(
			'animated_images',
			[
				'label' => esc_html__( 'Animation images', 'clawyer-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'anim_image1',
			[
				'label'   => __( 'Image 1', 'clawyer-core' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		$this->add_control(
			'anim_image2',
			[
				'label'   => __( 'Image 2', 'clawyer-core' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		$this->add_control(
			'anim_image3',
			[
				'label'   => __( 'Image 3', 'clawyer-core' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		$this->add_control(
			'anim_image4',
			[
				'label'   => __( 'Image 4', 'clawyer-core' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [ 'styles' => '3' ],
			]
		);
		$this->add_control(
			'anim_image5',
			[
				'label'   => __( 'Image 5', 'clawyer-core' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [ 'styles' => '3' ],
			]
		);
		$this->add_control(
			'anim_image6',
			[
				'label'   => __( 'Image 6', 'clawyer-core' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
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
					'{{WRAPPER}} .image-box' => 'text-align: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);
		$this->add_control(
			'arrow_shape_visibility',
			[
				'label'        => __( 'Arrow Shape Visibility', 'clawyer-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'clawyer-core' ),
				'label_off'    => __( 'Hide', 'clawyer-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'mouse_effect',
			[
				'label'        => __( 'Mouse Effects', 'clawyer-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'clawyer-core' ),
				'label_off'    => __( 'Hide', 'clawyer-core' ),
				'return_value' => 'yes',
			]
		);
		/* -- Styles Options -- */
		// $this->__image_box_style_settings();

		$this->end_controls_section();
	}

	/* -- List Options -- */
	protected function __image_box_style_settings() {
		$this->add_responsive_control(
			'image_border_radius',
			[
				'type'    => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'label'   => esc_html__( 'Border Radius', 'clawyer-core' ),
				'selectors' => array(
					'{{WRAPPER}} .image-box img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			]
		);
	}

	protected function render() {
		$data = $this->get_settings();

		$template = 'view-1';
		$template = 'view-'.$data['styles'];

		Fns::get_template( "elementor/image-box/{$template}", $data );
	}
}