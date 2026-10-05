<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace RT\ClawyerCore\Abstracts;

use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

abstract class ElementorBase extends Widget_Base {

	public $rt_name;
	public $rt_base;
	public $rt_category;
	public $rt_icon;
	public $rt_translate;
	public $rt_dir;

	public function __construct( $data = [], $args = null ) {
		$this->rt_category = CLAWYER_CORE_PREFIX . '-widgets'; // Category /@dev
		$this->rt_icon     = 'rdtheme-el-custom';
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return $this->rt_base;
	}

	public function get_title() {
		return $this->rt_name;
	}

	public function get_icon() {
		return $this->rt_icon;
	}

	public function get_categories() {
		return [ $this->rt_category ];
	}

	/**
	 * Getting Custome taxanomy for portfolio - category- single service
	 */
	public static function listing_terms_slug($term) {
		if (class_exists('Rtcl')) {
			$terms = get_terms( $term );
			if(!empty($terms)){
				$category_links = array();
				foreach ($terms as $key => $value) {
					$category_links[$value->term_id] = $value->name;
				}
				return $category_links;
			}
		}
	}

	/**
	 * Getting Custome taxanomy for portfolio - category- single service
	 */
	public static function listing_locations_slug() {
		if (class_exists('Rtcl')) {
			$terms = get_terms( "rtcl_location" );
			if(!empty($terms)){
				$category_links = array();
				foreach ($terms as $key => $value) {
					$category_links[$value->term_id] = $value->name;
				}
				return $category_links;
			}
		}
	}

	/**
	 * @return array
	 */
	public function rt_blog_posts_title() {
		$args = array(
			'post_type'      => 'rtcl_listing',
			'post_status'    => 'publish',
			'taxonomy'       => 'rtcl_category',
			'posts_per_page' => -1,
		);
		$post_title = array();
		$grid_query = new \WP_Query( $args );
		if ( $grid_query->have_posts() ) :
			while ( $grid_query->have_posts() ) : $grid_query->the_post();
				$post_title[get_the_ID()] = get_the_title();
			endwhile; wp_reset_postdata();
		endif;
		return $post_title;
	}

	public function rt_grid_options(){
		return [
			'3'  => __( '4 Columns', 'clawyer-core' ),
			'4'  => __( '3 Columns', 'clawyer-core' ),
			'6'  => __( '2 Columns', 'clawyer-core' ),
			'12' => __( '1 Columns', 'clawyer-core' ),
		];
	}

	public function rt_alignment_options(){
		return array(
			'left'    => array(
				'title' => __( 'Left', 'clawyer-core' ),
				'icon' => 'eicon-text-align-left',
			),
			'center' => array(
				'title' => __( 'Center', 'clawyer-core' ),
				'icon' => 'eicon-text-align-center',
			),
			'right' => array(
				'title' => __( 'Right', 'clawyer-core' ),
				'icon' => 'eicon-text-align-right',
			),
			'justify' => array(
				'title' => __( 'Justified', 'clawyer-core' ),
				'icon' => 'eicon-text-align-justify',
			),
		);
	}

	/**
	 * @return array
	 */
	public function rt_get_all_image_sizes() {
		global $_wp_additional_image_sizes;
		$image_sizes = [ '0' => __( 'Default Image Size', 'clawyer-core' ) ];
		foreach ( $_wp_additional_image_sizes as $index => $item ) {
			$image_sizes[ $index ] = __( ucwords( $index . ' - ' . $item['width'] . 'x' . $item['height'] ), 'clawyer-core' );
		}
		$image_sizes['full'] = __( "Full Size", 'clawyer-core' );

		return $image_sizes;
	}
}