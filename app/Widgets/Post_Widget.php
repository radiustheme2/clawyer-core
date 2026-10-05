<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace RT\ClawyerCore\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit;

use \WP_Widget;
use \RT_Widget_Fields;
use RT\ClawyerCore\Helper\Fns;
use RT\Clawyer\Helpers\Fns as ThemeFns;


class Post_Widget extends WP_Widget {

	public function __construct() {
		$id    = CLAWYER_CORE_PREFIX . '_blog_post';
		$title = __( 'Clawyer: Blog Post', 'clawyer-core' );
		$args  = [
			'description' => esc_html__( 'Displays Blog Post', 'clawyer-core' )
		];
		parent::__construct( $id, $title, $args );
	}

	public function form( $instance ) {
		$defaults = [
			'title'              => __( 'Latest Post', 'clawyer-core' ),
			'layout'             => 'blog-list-style',
			'query_title'        => '',
			'posts_type'         => 'post',
			'posts_per_page'     => 5,
			'orderby'            => 'date',
			'order'              => 'DESC',
			'post_id'            => '',
			'meta_title'         => '',
			'category'           => 'category',
			'author'             => '',
			'date'               => '',
			'content'            => '',
		];

		$instance = wp_parse_args( (array) $instance, $defaults );

		$fields = [
			'title'          => [
				'label' => esc_html__( 'Title', 'clawyer-core' ),
				'type'  => 'text',
			],
			'layout'         => [
				'label'   => esc_html__( 'Layout Style', 'clawyer-core' ),
				'type'    => 'select',
				'options' => [
					'blog-list-style'             => __( 'List', 'clawyer-core' ),
					'blog-grid-style'             => __( 'Grid', 'clawyer-core' ),
				]
			],
			'query_title'    => [
				'label' => esc_html__( 'QUERY', 'clawyer-core' ),
				'type'  => 'heading',
			],
			'posts_type'     => [
				'label'   => esc_html__( 'Post Type', 'clawyer-core' ),
				'type'    => 'select',
				'options' => ThemeFns::get_post_types()
			],
			'posts_per_page' => [
				'label' => esc_html__( 'Posts Per Page', 'clawyer-core' ),
				'type'  => 'number',
			],
			'orderby'        => [
				'label'   => esc_html__( 'Order by', 'clawyer-core' ),
				'type'    => 'select',
				'options' => [
					'date'          => __( 'Date', 'clawyer-core' ),
					'author'        => __( 'Author', 'clawyer-core' ),
					'title'         => __( 'Title', 'clawyer-core' ),
					'modified'      => __( 'Last modified date', 'clawyer-core' ),
					'parent'        => __( 'Post parent ID', 'clawyer-core' ),
					'comment_count' => __( 'Number of comments', 'clawyer-core' ),
					'menu_order'    => __( 'Menu order', 'clawyer-core' ),
					'rand'          => __( 'Random order', 'clawyer-core' ),
					'popular'       => __( 'Popular Post', 'clawyer-core' ),
				]
			],
			'order'          => [
				'label'   => esc_html__( 'Order', 'clawyer-core' ),
				'type'    => 'select',
				'options' => [
					'ASC'  => __( 'ASC', 'clawyer-core' ),
					'DESC' => __( 'DESC', 'clawyer-core' ),
				]
			],
			'post_id'        => [
				'label' => esc_html__( 'Post by ID', 'clawyer-core' ),
				'type'  => 'text',
				'desc'  => esc_html__( 'Enter post id by comma (,) separator.', 'clawyer-core' ),
			],

			'meta_title'         => [
				'label' => esc_html__( 'Choose Meta', 'clawyer-core' ),
				'type'  => 'heading',
			],
			'category'           => [
				'label' => esc_html__( 'Category', 'clawyer-core' ),
				'type'  => 'checkbox',
			],
			'author'             => [
				'label' => esc_html__( 'Author', 'clawyer-core' ),
				'type'  => 'checkbox',
			],
			'date'               => [
				'label' => esc_html__( 'Date', 'clawyer-core' ),
				'type'  => 'checkbox',
			],
			'content'               => [
				'label' => esc_html__( 'Content', 'clawyer-core' ),
				'type'  => 'checkbox',
			],
		];

		RT_Widget_Fields::display( $fields, $instance, $this );
	}

	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title']              = !empty($new_instance[ 'title' ]) ? sanitize_text_field($new_instance[ 'title' ]) : '';
		$instance['layout']             = !empty( $new_instance['layout'] ) ? $new_instance['layout'] : '';
		$instance['query_title']        = ThemeFns::sanitize( $new_instance[ 'query_title' ] );
		$instance['posts_type']         = ThemeFns::sanitize( $new_instance[ 'posts_type' ], 'post' );
		$instance['posts_per_page']     = !empty( $new_instance['posts_per_page'] ) ? $new_instance['posts_per_page'] : '4';
		$instance['orderby']            = !empty( $new_instance['orderby'] ) ? $new_instance['orderby'] : 'date';
		$instance['order']              = !empty( $new_instance['order'] ) ? $new_instance['order'] : 'DESC';
		$instance['post_id']            = !empty( $new_instance['post_id'] ) ? $new_instance['post_id'] : '';
		$instance['category']           = !empty( $new_instance['category'] ) ? $new_instance['category'] : '';
		$instance['author']             = !empty( $new_instance['author'] ) ? $new_instance['author'] : '';
		$instance['date']               = !empty( $new_instance['date'] ) ? $new_instance['date'] : '';
		$instance['content']            = !empty( $new_instance['content'] ) ? $new_instance['content'] : '';

		return $instance;
	}

	public function widget( $args, $instance ) {

		echo wp_kses_post( $args['before_widget'] );
		$title = apply_filters( 'widget_title', $instance['title'] );
		if ( ! empty( $title ) ) {
			echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
		}

		$postArgs = [
			'post_type'           => $instance['posts_type'] ?? 'post',
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => $instance['posts_per_page'] ?? 4,
			'post_status'         => 'publish',
		];

		if ( ! empty( $instance['orderby'] ) ) {
			$postArgs['orderby'] = $instance['orderby'];
		}

		if ( ! empty( $instance['order'] ) ) {
			$postArgs['order'] = $instance['order'];
		}

		if ( ! empty( $instance['post_id'] ) ) :
			$post_ids             = explode( ',', $instance['post_id'] );
			$postArgs['post__in'] = $post_ids;
		endif;

		$query = new \WP_Query( $postArgs );

		$meta_list  = [];
		$_meta_list = clawyer_option( 'rt_blog_meta', false, true );
		foreach ( $_meta_list as $meta ) {
			if ( ! empty( $instance[ $meta ] ) ) {
				$meta_list[] = $meta;
			}
		}

		$data = [
			'meta_list' => $meta_list,
			'content'   => $instance['content'],
		];
		$layout     = $instance['layout'] ?? 'blog-list-style';
		$post_count = 1;
		if ( $query->have_posts() ) :
			echo "<div class='clawyer-widget-post " . esc_attr( $layout ) . "'>";
			while ( $query->have_posts() ) : $query->the_post();
				set_query_var( 'post_count', $post_count );
				Fns::get_template( "widgets/latest-posts", $data );
				$post_count ++;
			endwhile;
			echo "</div>";
			wp_reset_postdata();
		endif;

		echo wp_kses_post( $args['after_widget'] );
	}
}