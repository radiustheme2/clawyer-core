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
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use RT\ClawyerCore\Abstracts\ElementorBase;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ListingCategoriesFilter extends ElementorBase {

	public function __construct( $data = [], $args = null ) {
		$this->rt_name = esc_html__( 'Listing Cats Filter', 'clawyer-core' );
		$this->rt_base = 'rt-listing-categories-filter';
		parent::__construct( $data, $args );
	}


	protected function register_controls() {

		/* -- Content Settings -- */
		$this->__portfolio_post_content_settings();
		$this->__portfolio_post_layout_settings();
		$this->__portfolio_post_grid_settings();
		$this->__visibility_settings();
		/* -- Style Settings -- */
		$this->__portfolio_post_box_style();
		$this->__portfolio_post_thumbnail_style();
		$this->__portfolio_post_title_style();
		$this->__portfolio_post_content_style();
		$this->__portfolio_post_link_style();
	}

	protected function __portfolio_post_content_settings(){
		$this->start_controls_section(
			'Portfolio_post_content_settings',
			[
				'label' => esc_html__( 'Content Settings', 'clawyer-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'Portfolios_post_options',
			[
				'type' => Controls_Manager::HEADING,
				'label'   => esc_html__( 'Content Settings', 'clawyer-core' ),
				'separator' => 'before',
			]
		);
		$this->add_control(
			'post_limit',
			[
				'label'       => __( 'Post Limit', 'clawyer-core' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Post Limit', 'clawyer-core' ),
				'description' => __( 'Enter number of post to show.', 'clawyer-core' ),
				'default'     => '3',
			]
		);
		$this->add_control(
			'post_excerpt',
			[
				'label'       => __( 'Post Excerpt', 'clawyer-core' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Post Excerpt Limit', 'clawyer-core' ),
				'description' => __( 'Enter number of post word to show.', 'clawyer-core' ),
				'default'     => '8',
			]
		);
		$this->add_control(
			'post_source',
			[
				'label'       => __( 'Post Source', 'clawyer-core' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'most_recent' => __( 'From all recent post', 'clawyer-core' ),
					'by_title'       => __( 'By Post ID', 'clawyer-core' ),
					'by_category' => __( 'By Category', 'clawyer-core' ),
					'by_tags'     => __( 'By Tags', 'clawyer-core' ),
				],
				'default'     => [ 'most_recent' ],
				'description' => __( 'Select posts source that you like to show.', 'clawyer-core' ),
			]
		);
		$this->add_control(
			'titles',
			[
				'label'       => __( 'Choose Titles', 'clawyer-core' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => $this->rt_blog_posts_title(),
				'label_block' => true,
				'condition'   => [
					'post_source' => 'by_title',
				],
				'description' => __( 'Select post category\'s.', 'clawyer-core' ),
			]
		);

		$this->add_control(
			'categories',
			[
				'label'       => __( 'Choose Categories', 'clawyer-core' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => $this->listing_terms_slug('rtcl_category'),
				'label_block' => true,
				'condition'   => [
					'post_source' => 'by_category',
				],
				'description' => __( 'Select post category\'s.', 'clawyer-core' ),
			]
		);
		$this->add_control(
			'offset',
			[
				'label'       => __( 'Post offset', 'clawyer-core' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Post offset', 'clawyer-core' ),
				'description' => __( 'Number of post to displace or pass over. The offset parameter is ignored when post limit => -1 (show all posts) is used.', 'clawyer-core' ),
			]
		);

		$this->add_control(
			'exclude',
			[
				'label'       => __( 'Exclude posts', 'clawyer-core' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => 'true',
				'description' => __( 'Enter the post IDs separated by comma for exclude', 'clawyer-core' ),
			]
		);
		$this->add_control(
			'orderby',
			[
				'label'   => esc_html__( 'Order by', 'clawyer-core' ),
				'type'    =>Controls_Manager::SELECT,
				'options' => [
					'date'           => esc_html__( 'Date', 'clawyer-core' ),
					'ID'             => esc_html__( 'Order by post ID', 'clawyer-core' ),
					'author'         => esc_html__( 'Author', 'clawyer-core' ),
					'title'          => esc_html__( 'Title', 'clawyer-core' ),
					'modified'       => esc_html__( 'Last modified date', 'clawyer-core' ),
					'comment_count'  => esc_html__( 'Number of comments', 'clawyer-core' ),
					'rand'           => esc_html__( 'Random order', 'clawyer-core' ),
				],
			]
		);
		$this->add_control(
			'order',
			[
				'label'   => esc_html__( 'Sort order', 'clawyer-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'ASC'  => esc_html__( 'ASC', 'clawyer-core' ),
					'DESC' => esc_html__( 'DESC', 'clawyer-core' ),
				],
			]
		);
		$this->add_control(
			'readmore',
			[
				'label'        => esc_html__( 'Read More Button', 'clawyer-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'clawyer-core' ),
				'label_off'    => esc_html__( 'Hide', 'clawyer-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'post_thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
				'exclude' => [ 'custom' ], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
				'include' => [],
				'default' => 'full',
			]
		);
		$this->end_controls_section();
	}

	protected function __portfolio_post_layout_settings() {
		$this->start_controls_section(
			'Portfolio_post_layout_settings',
			[
				'label' => esc_html__( 'Layouts Settings', 'clawyer-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		//Image selector
		$this->add_control(
			'styles',
			[
				'label' => esc_html__('Design Style', 'clawyer-core'),
//				'type' => ImageSelectorControl::ImageSelector,
				'type' => 'rt-image-select',
				'options' => [
					'1' => [
						'title' => esc_html__('Style 1', 'clawyer-core'),
						'url' =>  trailingslashit( get_template_directory_uri() ) . 'assets/img/theme/portfolio-1.jpg',
					],
					'2' => [
						'title' => esc_html__('Style 2', 'clawyer-core'),
						'url' => trailingslashit( get_template_directory_uri() ) . 'assets/img/theme/portfolio-2.jpg',
					],
					'3' => [
						'title' => esc_html__('Style 3', 'clawyer-core'),
						'url' => trailingslashit( get_template_directory_uri() ) . 'assets/img/theme/portfolio-3.jpg',
					],
					'4' => [
						'title' => esc_html__('Style 4', 'clawyer-core'),
						'url' => trailingslashit( get_template_directory_uri() ) . 'assets/img/theme/portfolio-4.jpg',
					],
				],
				'default' => '1',
			]
		);
		$this->add_control(
			'Portfolios_layout_options',
			[
				'type' => Controls_Manager::HEADING,
				'label'   => esc_html__( 'Layout Settings', 'clawyer-core' ),
				'separator' => 'before',
			]
		);
		$this->add_control(
			'layouts',
			[
				'type'    	  => Controls_Manager::SELECT,
				'label'   	  => esc_html__( 'Layouts', 'clawyer-core' ),
				'options' 	  => array(
					'grid' => esc_html__( 'Grid', 'clawyer-core' ),
					'masonry' => esc_html__( 'Masonry', 'clawyer-core' ),
					'slider' => esc_html__( 'Slider', 'clawyer-core' ),
					'list' => esc_html__( 'List', 'clawyer-core' ),
				),
				'default' 	  => 'grid',
				'description' => esc_html__( 'Portfolio Layout. Default: Grid', 'clawyer-core' ),
			]
		);
		$this->end_controls_section();
	}

	protected function __portfolio_post_grid_settings() {
		$this->start_controls_section(
			'grid_settings',
			[
				'label'     => esc_html__( 'Grid Settings', 'clawyer-core' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						['name' => 'layouts', 'operator' => '==', 'value' => 'grid'],
						['name' => 'masonry_layouts', 'operator' => '==', 'value' => '1'],
					],
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
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						['name' => 'layouts', 'operator' => '==', 'value' => 'grid'],
						['name' => 'masonry_layouts', 'operator' => '==', 'value' => '1'],
					],
				],
			]
		);

		$this->start_popover();
		$this->add_control(
			'grid_column_desktop',
			[
				'label'   => esc_html__( 'Grid Column for Desktop', 'clawyer-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '3',
				'options' => $this->rt_grid_options(),
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						['name' => 'layouts', 'operator' => '==', 'value' => 'grid'],
						['name' => 'masonry_layouts', 'operator' => '==', 'value' => '1'],
					],
				],
			]
		);
		$this->add_control(
			'grid_column_tab',
			[
				'label'   => esc_html__( 'Grid Column for Tab', 'clawyer-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '2',
				'options' => $this->rt_grid_options(),
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						['name' => 'layouts', 'operator' => '==', 'value' => 'grid'],
						['name' => 'masonry_layouts', 'operator' => '==', 'value' => '1'],
					],
				],
			]
		);
		$this->add_control(
			'grid_column_mobile',
			[
				'label'   => esc_html__( 'Grid Column for Mobile', 'clawyer-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '1',
				'options' => $this->rt_grid_options(),
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						['name' => 'layouts', 'operator' => '==', 'value' => 'grid'],
						['name' => 'masonry_layouts', 'operator' => '==', 'value' => '1'],
					],
				],
			]
		);
		$this->end_popover();

		$this->end_controls_section();
	}

	protected function __visibility_settings() {
		$this->start_controls_section(
			'visibility_settings',
			[
				'label'     => esc_html__( 'Visibility Settings', 'clawyer-core' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'rtcl_show_types',
			[
				'type'        => Controls_Manager::SWITCHER,
				'label'       => __( 'Show Types', 'clawyer-core' ),
				'label_on'    => __( 'On', 'clawyer-core' ),
				'label_off'   => __( 'Off', 'clawyer-core' ),
				'default'     => '',
				'description' => __( 'Show or Hide Types. Default: On', 'clawyer-core' ),
			]
		);
		$this->add_control(
			'rtcl_show_labels',
			[
				'type'        => Controls_Manager::SWITCHER,
				'label'       => __( 'Show Badge', 'clawyer-core' ),
				'label_on'    => __( 'On', 'clawyer-core' ),
				'label_off'   => __( 'Off', 'clawyer-core' ),
				'default'     => '',
				'description' => __( 'Show or Hide labels. Default: On', 'clawyer-core' ),
			]
		);
		$this->add_control(
			'rtcl_show_image',
			[
				'type'        => Controls_Manager::SWITCHER,
				'label'       => __( 'Show Badge', 'clawyer-core' ),
				'label_on'    => __( 'On', 'clawyer-core' ),
				'label_off'   => __( 'Off', 'clawyer-core' ),
				'default'     => 'yes',
				'description' => __( 'Show or Hide labels. Default: On', 'clawyer-core' ),
			]
		);
		$this->add_control(
			'rtcl_show_favourites',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => __( 'Show Favourites', 'clawyer-core' ),
				'label_on'  => __( 'On', 'clawyer-core' ),
				'label_off' => __( 'Off', 'clawyer-core' ),
				'default'   => 'yes',
			]
		);
		$this->end_controls_section();
	}

	protected function __portfolio_post_box_style() {
		$this->start_controls_section(
			'portfolio_post_box_style',
			[
				'label' => esc_html__( 'Post Box Style', 'clawyer-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'portfolio_post_box_bg_color',
				'label'    => esc_html__( 'Background', 'clawyer-core' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'background' => [
						'label' => esc_html__('Background Color', 'clawyer-core'),
					]
				],
				'selector' => '{{WRAPPER}} .ayo-portfolio-box.el-portfolio-box',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'portfolio_post_box_border',
				'label'     => __( 'Border', 'clawyer-core' ),
				'selector'  => '{{WRAPPER}} .ayo-portfolio-box.el-portfolio-box',
			]
		);
		$this->add_control(
			'portfolio_post_box_border',
			[
				'label' => esc_html__( 'Border Radius', 'clawyer-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .ayo-portfolio-box.el-portfolio-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'portfolio_post_padding',
			[
				'type'    => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'label'   => esc_html__( 'Padding', 'clawyer-core' ),
				'selectors' => array(
					'{{WRAPPER}} .ayo-portfolio-box.el-portfolio-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			]
		);
		$this->end_controls_section();
	}

	protected function __portfolio_post_thumbnail_style() {
		$this->start_controls_section(
			'portfolio_post_thumb_style',
			[
				'label' => esc_html__( 'Thumbnail Image', 'clawyer-core' ),
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
				'label'      => __( 'Thumb Size Width', 'clawyer-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 50,
						'max'  => 1200,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .el-portfolio-box .item-img' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .el-portfolio-box .item-img img' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'=>[
					'thum_display' =>'yes'
				]
			]
		);
		$this->add_control(
			'thumb_size_height',
			[
				'label'      => __( 'Thumb Size Height', 'clawyer-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 50,
						'max'  => 600,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .el-portfolio-box .item-img' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .el-portfolio-box .item-img img' => 'height: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .el-portfolio-box .item-img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'=>[
					'thum_display' =>'yes'
				]
			]
		);
		$this->add_control(
			'portfolio_post_thumb_hover_style',
			[
				'type' => Controls_Manager::HEADING,
				'label'   => esc_html__( 'Hover Style', 'clawyer-core' ),
				'separator' => 'before',
				'condition'=>[
					'thum_display' =>'yes'
				]
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'portfolio_post_thumb_hover_overlay1',
				'label'    => esc_html__( 'Background', 'clawyer-core' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'fields_options' => [
					'background' => [
						'label' => esc_html__('Overlay 1', 'clawyer-core'),
						'default' => 'classic',
					]
				],
				'selector'  => '{{WRAPPER}} .ayo-portfolio-box.style-1 .item-img::before',
				'condition'=>[
					'thum_display' =>'yes'
				]
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'portfolio_post_thumb_hover_overlay2',
				'label'    => esc_html__( 'Background', 'clawyer-core' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'fields_options' => [
					'background' => [
						'label' => esc_html__('Overlay 2', 'clawyer-core'),
						'default' => 'classic',
					]
				],
				'selector'  => '{{WRAPPER}} .ayo-portfolio-box.style-1 .item-img::after',
				'condition'=>[
					'thum_display' =>'yes'
				]
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'portfolio_post_thumb_hover_overlay2_border',
				'label'     => __( 'Border', 'clawyer-core' ),
				'selector'  => '{{WRAPPER}} .ayo-portfolio-box.style-1 .item-img::after',
				'condition'=> [
					'thum_display' =>'yes'
				]
			]
		);
		$this->end_controls_section();
	}

	protected function __portfolio_post_title_style() {
		$this->start_controls_section(
			'portfolio_post_title_style',
			[
				'label' => esc_html__( 'Title Style', 'clawyer-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'portfolio_post_title_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'clawyer-core' ),
				'selectors' => [
					'{{WRAPPER}} .title-cat-wrapper .list-title a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .ayo-portfolio-box .content .title a' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'portfolio_post_title_typo',
				'label'    => esc_html__( 'Typography', 'clawyer-core' ),
				'selector' => '{{WRAPPER}} .ayo-portfolio-box .content .title a, {{WRAPPER}} .title-cat-wrapper .list-title a',
			]
		);
		$this->add_control(
			'portfolio_post_title_hover_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Hover Color', 'clawyer-core' ),
				'selectors' => [
					'{{WRAPPER}} .title-cat-wrapper:hover .list-title a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .ayo-portfolio-box .content .title a:hover' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();
	}

	protected function __portfolio_post_content_style() {
		$this->start_controls_section(
			'portfolio_post_content_style',
			[
				'label' => esc_html__( 'Content', 'clawyer-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'portfolio_post_content_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'clawyer-core' ),
				'selectors' => [
					'{{WRAPPER}} .ayo-portfolio-box .content p' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'portfolio_post_content_typo',
				'label'    => esc_html__( 'Typography', 'clawyer-core' ),
				'selector' => '{{WRAPPER}} .ayo-portfolio-box .content p',
			]
		);
		$this->end_controls_section();
	}

	// Button Style
	protected function __portfolio_post_link_style() {
		$this->start_controls_section(
			'portfolio_post_link_style',
			[
				'label' => esc_html__( 'Read More Style', 'clawyer-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'portfolio_post_link_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label'   => esc_html__( 'Read More Style', 'clawyer-core' ),
				'separator' => 'before',
				'condition' => ['readmore' => 'yes'],
			]
		);

		$this->start_controls_tabs( 'portfolio_post_link_tab_style' );
		// Normal tab.
		$this->start_controls_tab(
			'portfolio_post_link_style_normal',
			[
				'label' => __( 'Normal', 'clawyer-core' ),
				'condition' => ['readmore' => 'yes'],
			]
		);

		$this->add_control(
			'portfolio_post_link_color',
			[
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Color', 'clawyer-core' ),
				'selectors' => array(
					'{{WRAPPER}} .ayo-portfolio-box .list-button' => 'color: {{VALUE}}',
					'{{WRAPPER}} .ayo-portfolio-box .content .post-btn a' => 'color: {{VALUE}}',
				),
				'condition' => ['readmore' => 'yes'],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'portfolio_post_link_typo',
				'label'    => esc_html__( 'Typography', 'clawyer-core' ),
				'selector' => '{{WRAPPER}} .ayo-portfolio-box .content .post-btn a, {{WRAPPER}} .ayo-portfolio-box .list-button',
				'condition' => ['readmore' => 'yes'],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'portfolio_post_link_bg_color',
				'label'    => esc_html__( 'Background', 'clawyer-core' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'fields_options' => [
					'background' => [
						'label' => esc_html__(' Background', 'clawyer-core'),
						'default' => 'classic',
					]
				],
				'selector'  => '{{WRAPPER}} .ayo-portfolio-box .content .post-btn a, {{WRAPPER}} .ayo-portfolio-box .list-button',
				'condition' => ['readmore' => 'yes'],
			]
		);
		$this->add_responsive_control(
			'portfolio_post_link_padding',
			[
				'type'    => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'label'   => esc_html__( 'Padding', 'clawyer-core' ),
				'selectors' => array(
					'{{WRAPPER}} .ayo-portfolio-box .list-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} .ayo-portfolio-box .content .post-btn a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
				'condition' => ['readmore' => 'yes'],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'portfolio_post_link_border',
				'label'     => __( 'Border', 'clawyer-core' ),
				'selector'  => '{{WRAPPER}} .ayo-portfolio-box .content .post-btn a, {{WRAPPER}} .ayo-portfolio-box .list-button',
				'condition' => ['readmore' => 'yes'],
			]
		);
		$this->add_responsive_control(
			'portfolio_post_link_border_radius',
			[
				'type'    => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'label'   => esc_html__( 'Radius', 'clawyer-core' ),
				'selectors' => array(
					'{{WRAPPER}} .title-cat-wrapper .list-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} .ayo-portfolio-box .content .post-btn a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
				'condition' => ['readmore' => 'yes'],
			]
		);
		$this->end_controls_tab();

		// Hover tab.
		$this->start_controls_tab(
			'portfolio_post_link_style_hover',
			[
				'label' => __( 'Hover', 'clawyer-core' ),
				'condition' => ['readmore' => 'yes'],
			]
		);
		$this->add_control(
			'portfolio_post_link_hover_color',
			[
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Color', 'clawyer-core' ),
				'selectors' => array(
					'{{WRAPPER}} .title-cat-wrapper:hover .list-button' => 'color: {{VALUE}}',
					'{{WRAPPER}} .ayo-portfolio-box .content .post-btn a:hover' => 'color: {{VALUE}}',
				),
				'condition' => ['readmore' => 'yes'],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'portfolio_post_link_hover_bg_color',
				'label'    => esc_html__( 'Background', 'clawyer-core' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'fields_options' => [
					'background' => [
						'label' => esc_html__(' Background', 'clawyer-core'),
						'default' => 'classic',
					]
				],
				'selector'  => '{{WRAPPER}} .ayo-portfolio-box .content .post-btn a:hover, {{WRAPPER}} .title-cat-wrapper:hover .list-button',
				'condition' => ['readmore' => 'yes'],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'portfolio_post_link_hover_border',
				'label'     => __( 'Border', 'clawyer-core' ),
				'selector'  => '{{WRAPPER}} .ayo-portfolio-box .content .post-btn a:hover, {{WRAPPER}} .title-cat-wrapper:hover .list-button',
				'condition' => ['readmore' => 'yes'],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	protected function render() {
		$data = $this->get_settings();

		$data['rtcl_thumb_image_size'] = 'full';

		$template = 'view-1';

		Fns::get_template( "elementor/listing-categories-filter/$template", $data );
	}

}