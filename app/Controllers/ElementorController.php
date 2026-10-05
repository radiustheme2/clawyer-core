<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace RT\ClawyerCore\Controllers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Plugin;
use Elementor\Controls_Manager;
use RT\Clawyer\Helpers\Fns as ThemeFns;
use RT\ClawyerCore\Elementor\Controls\ImageSelectorControl;
use RT\ClawyerCore\Elementor\Controls\Select2AjaxControl;
use RT\ClawyerCore\Helper\Fns;
use RT\ClawyerCore\Traits\SingletonTraits;
use RT\ClawyerCore\Elementor\Core\ElementorCore;

/* = Custom Addons = */
use RT\ClawyerCore\Elementor\Widgets\Title;
use RT\ClawyerCore\Elementor\Widgets\Button;
use RT\ClawyerCore\Elementor\Widgets\InfoBox;
use RT\ClawyerCore\Elementor\Widgets\WritingTitle;
use RT\ClawyerCore\Elementor\Widgets\ImageBox;
use RT\ClawyerCore\Elementor\Widgets\Faq;
use RT\ClawyerCore\Elementor\Widgets\ContactBox;
use RT\ClawyerCore\Elementor\Widgets\SocialIcon;
use RT\ClawyerCore\Elementor\Widgets\MovingBox;

/* = Listing Addons = */
use RT\ClawyerCore\Elementor\Widgets\ListingLocationsGrid;
use RT\ClawyerCore\Elementor\Widgets\ListingLocationsSlider;
use RT\ClawyerCore\Elementor\Widgets\ListingCategoriesSlider;
use RT\ClawyerCore\Elementor\Widgets\ListingCategoriesFilter;
use RT\ClawyerCore\Elementor\Widgets\ListingCategoryBox;
use RT\ClawyerCore\Elementor\Widgets\ListingAdvancedSearch;

/* = Default Addons = */
use RT\ClawyerCore\Elementor\Widgets\ContactForm;
use RT\ClawyerCore\Elementor\Widgets\Parallax;
use RT\ClawyerCore\Elementor\Widgets\Post;
use RT\ClawyerCore\Elementor\Widgets\PricingTable;
use RT\ClawyerCore\Elementor\Widgets\ProgressBar;
use RT\ClawyerCore\Elementor\Widgets\SiteLogo;
use RT\ClawyerCore\Elementor\Widgets\Team;
use RT\ClawyerCore\Elementor\Widgets\Testimonial;
use RT\ClawyerCore\Elementor\Widgets\VideoIcon;
use RT\ClawyerCore\Elementor\Widgets\SiteMenu;
use RT\ClawyerCore\Elementor\Widgets\MenuIcons;
use RT\ClawyerCore\Elementor\Widgets\SocialShare;

/* = Widgets Addons = */
use RT\ClawyerCore\Elementor\Widgets\AboutWidget;


class ElementorController {
	use SingletonTraits;

	public function __construct() {
		ElementorCore::instance();
		add_action( 'elementor/widgets/register', [ $this, 'register_widget' ] );
		add_action( 'elementor/elements/categories_registered', [ $this, 'widget_category' ] );
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'editor_style' ] );
		add_action( 'elementor/controls/register', [ $this, 'register_new_control' ] );
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'editor_scripts' ] );
		add_action( 'wp_ajax_rt_select2_object_search', [ $this, 'select2_ajax_posts_filter_autocomplete' ] );
		add_action( 'wp_ajax_nopriv_rt_select2_object_search', [ $this, 'select2_ajax_posts_filter_autocomplete' ] );

		/* = Select2 ajax save data = */
		add_action( 'wp_ajax_rt_select2_get_title', [ $this, 'select2_ajax_get_posts_value_titles' ] );
		add_action( 'wp_ajax_nopriv_rt_select2_get_title', [ $this, 'select2_ajax_get_posts_value_titles' ] );
		if ( class_exists( 'RT\\Clawyer\\Init' ) ) {
			add_action( 'elementor/icons_manager/additional_tabs', [ $this, 'fontello_support' ] );
		}

		/* = Listing Categories = */
		add_filter( 'rtcl_el_listing_category_widget_general_field', [ $this, 'category_settings_cols_modity'], 10, 2 );
		add_filter( 'rtcl_el_category_box_style', [ $this, 'added_el_listing_category_box_style' ], 10 );
		add_filter( 'rtcl_el_listing_category_widget_style_field', [ $this, 'category_settings_add_new_controls'], 10, 2 );
		add_filter( 'rtcl_el_slider_category_widget_style_field', [ $this, 'category_slider_settings_add_new_controls'], 10, 2 );

		/* = Listing Showcase = */
		add_filter( 'rtcl_el_listings_grid_style', [ __CLASS__, 'added_listings_showcase_style_grid' ], 10 );
		add_filter( 'rtcl_el_listing_widget_general_field', [ $this, 'listing_widget_grid_general_field' ], 10, 2 );
		add_filter( 'el_listing_widget_content_visibility_fields', [ $this, 'listing_widget_content_visibility_field' ], 10, 2 );

	}


	/**
	 * Editor JS.
	 *
	 * @return void
	 */
	public function editor_scripts() {
		$version = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? time() : CLAWYER_CORE;

		wp_enqueue_script(
			'clawyer-editor-script',
			Fns::get_assets_url( 'js/editor.js' ),
			[
				'jquery',
				'elementor-editor',
				'jquery-elementor-select2',
			],
			$version,
			true
		);
	}

	/**
	 * Register Controls
	 *
	 * @param $controls_manager
	 *
	 * @return void
	 */
	public function register_new_control( $controls_manager ) {
		$controls_manager->register( new ImageSelectorControl() );
		$controls_manager->register( new Select2AjaxControl() );
	}

	/**
	 * Register Elementor Widget.
	 * Just put the widget class reference here
	 * @return void
	 */
	public function register_widget() {

		$widgets = [
			/* = Custom Addons = */
			Title::class,
			Button::class,
			InfoBox::class,
			WritingTitle::class,
			ImageBox::class,
			Faq::class,
			ContactBox::class,
			SocialIcon::class,
			MovingBox::class,
			/* = Default Addons = */
			ContactForm::class,
			Parallax::class,
			Post::class,
			PricingTable::class,
			ProgressBar::class,
			Team::class,
			Testimonial::class,
			VideoIcon::class,
			SocialShare::class,
			SiteLogo::class,
			SiteMenu::class,
			MenuIcons::class,
			/* = Listing Addons = */
			ListingLocationsGrid::class,
			ListingLocationsSlider::class,
			ListingCategoriesSlider::class,
			ListingCategoriesFilter::class,
			ListingCategoryBox::class,
			ListingAdvancedSearch::class,
			/* = Widgets Addons = */
			AboutWidget::class,
		];

		/*if ( Plugin::$instance->preview->is_preview_mode() ) {
			if ( is_singular( ElmentorBuilderController::$post_type ) ) {
				$widgets[] = SiteLogo::class;
				$widgets[] = SiteMenu::class;
				$widgets[] = MenuIcons::class;
			}
		} else {
			$widgets[] = SiteLogo::class;
			$widgets[] = SiteMenu::class;
			$widgets[] = MenuIcons::class;
		}*/

		foreach ( $widgets as $class ) {
			Plugin::instance()->widgets_manager->register( new $class );
		}
	}

	/**
	 * Elementor Editor Style
	 * @return void
	 */
	public function editor_style() {
		$icon         = Fns::get_assets_url( 'images/icon.png' );
		$editor_style = '.elementor-element .icon .rdtheme-el-custom{content: url(' . $icon . ');width: 28px;}';
		$editor_style .= '.elementor-panel .select2-container {min-width: 100px !important; min-height: 30px !important;}';
		$editor_style .= '.elementor-panel .elementor-control-type-heading .elementor-control-title {color: #93013d !important}';

		wp_add_inline_style( 'elementor-editor', $editor_style );
	}

	/**
	 * Register Elementor category
	 *
	 * @param $elements_manager
	 *
	 * @return void
	 */
	public function widget_category( $elements_manager ) {
		$id                = CLAWYER_CORE_PREFIX . '-widgets';
		$categories[ $id ] = [
			'title' => __( 'RadiusTheme Elements', 'clawyer-core' ),
			'icon'  => 'fa fa-plug',
		];

		$get_all_categories = $elements_manager->get_categories();
		$categories         = array_merge( $categories, $get_all_categories );
		$set_categories     = function ( $categories ) {
			$this->categories = $categories;
		};

		$set_categories->call( $elements_manager, $categories );
	}

	//load frontend script
	public function rt_load_scripts() {
		//wp_enqueue_script( 'imagesloaded' );
		//wp_enqueue_script( 'isotope' );
		wp_enqueue_script( 'select2' );
		wp_enqueue_script( 'elementor-script', CLAWYER_CORE_BASE_URL . 'elementor/assets/scripts.js', [ 'jquery' ], CLAWYER_CORE, true );
	}


	/**
	 * Adding custom icon to icon control in Elementor
	 */
	public function fontello_support( $tabs = [] ) {
		// Check by theme name
		if ( get_template() === 'clawyer' ) {
			$css_source = clawyer_get_css( 'fontello' );
		} else {
			$css_source = '';
		}

		// Append new icons
		$fontello = clawyer_flaticon_icons();

		$tabs['clawyer-fontello-icons'] = [
			'name'          => 'clawyer-fontello-icons',
			'label'         => esc_html__( 'Fontello Icons', 'clawyer-core' ),
			'labelIcon'     => 'fab fa-elementor',
			'prefix'        => '',
			'displayPrefix' => '',
			'url'           => $css_source,
			'icons'         => $fontello,
			'ver'           => '1.0',
		];

		return $tabs;
	}


	/**
	 * Ajax callback for rt-select2
	 *
	 * @param $post_type
	 * @param $limit
	 * @param $search
	 * @param $paged
	 *
	 * @return array
	 */
	public function get_query_data( $post_type = 'any', $limit = 10, $search = '', $paged = 1 ) {
		global $wpdb;
		$where = '';
		$data  = [];

		if ( - 1 == $limit ) {
			$limit = '';
		} elseif ( 0 == $limit ) {
			$limit = 'limit 0,1';
		} else {
			$offset = 0;
			if ( $paged ) {
				$offset = ( $paged - 1 ) * $limit;
			}
			$limit = $wpdb->prepare( ' limit %d, %d', esc_sql( $offset ), esc_sql( $limit ) );
		}

		if ( 'any' === $post_type ) {
			$in_search_post_types = get_post_types( [ 'exclude_from_search' => false ] );
			if ( empty( $in_search_post_types ) ) {
				$where .= ' AND 1=0 ';
			} else {
				$where .= " AND {$wpdb->posts}.post_type IN ('" . join(
						"', '",
						array_map( 'esc_sql', $in_search_post_types )
					) . "')";
			}
		} elseif ( ! empty( $post_type ) ) {
			$where .= $wpdb->prepare( " AND {$wpdb->posts}.post_type = %s", esc_sql( $post_type ) );
		}

		if ( ! empty( $search ) ) {
			$where .= $wpdb->prepare( " AND {$wpdb->posts}.post_title LIKE %s", '%' . esc_sql( $search ) . '%' );
		}

		$query   = "select post_title,ID  from $wpdb->posts where post_status = 'publish' {$where} {$limit}";
		$results = $wpdb->get_results( $query ); //phpcs:ignore

		if ( ! empty( $results ) ) {
			foreach ( $results as $row ) {
				$data[ $row->ID ] = $row->post_title . ' [#' . $row->ID . ']';
			}
		}

		return $data;
	}


	/**
	 * Ajax callback for rt-select2
	 *
	 * @return void
	 */
	public function select2_ajax_posts_filter_autocomplete() {

		$query_per_page = 15;
		$paged          = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1; // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		$post_type      = ThemeFns::sanitize( isset( $_POST['post_type'] ) ? wp_unslash( $_POST['post_type'] ) : 'post', 'post' ); // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		$source_name    = ThemeFns::sanitize( isset( $_POST['source_name'] ) ? wp_unslash( $_POST['source_name'] ) : 'post_type', 'post_type' ); // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		$search         = ThemeFns::sanitize( isset( $_POST['search'] ) ? wp_unslash( $_POST['search'] ) : '' ); // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.InputNotValidated

		$results = $post_list = [];
		switch ( $source_name ) {
			case 'taxonomy':
				$args = [
					'hide_empty' => false,
					'orderby'    => 'name',
					'order'      => 'ASC',
					'search'     => $search,
					'number'     => '5',
				];

				if ( $post_type !== 'all' ) {
					$args['taxonomy'] = $post_type;
				}

				$post_list = wp_list_pluck( get_terms( $args ), 'name', 'term_id' );
				break;
			case 'user':
				$users = [];

				foreach ( get_users( [ 'search' => "*{$search}*" ] ) as $user ) {
					$user_id           = $user->ID;
					$user_name         = $user->display_name;
					$users[ $user_id ] = $user_name;
				}

				$post_list = $users;
				break;
			default:
				$post_list = $this->get_query_data( $post_type, $query_per_page, $search, $paged );
		}

		$pagination = true;
		if ( count( $post_list ) < $query_per_page ) {
			$pagination = false;
		}
		if ( ! empty( $post_list ) ) {
			foreach ( $post_list as $key => $item ) {
				$results[] = [
					'text' => $item,
					'id'   => $key,
				];
			}
		}
		wp_send_json(
			[
				'results'    => $results,
				'pagination' => [ 'more' => $pagination ],
			]
		);
	}


	/**
	 * Ajax callback for rt-select2
	 *
	 * @return void
	 */
	public function select2_ajax_get_posts_value_titles() {

		if ( empty( $_POST['id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			wp_send_json_error( [] );
		}

		if ( empty( array_filter( $_POST['id'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			wp_send_json_error( [] );
		}
		$ids         = array_map( 'intval', $_POST['id'] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$source_name = ThemeFns::sanitize( isset( $_POST['source_name'] ) ? wp_unslash( $_POST['source_name'] ) : '' ); // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.InputNotValidated

		switch ( $source_name ) {
			case 'taxonomy':
				$args = [
					'hide_empty' => false,
					'orderby'    => 'name',
					'order'      => 'ASC',
					'include'    => implode( ',', $ids ),
				];

				if ( isset( $_POST['post_type'] ) && $_POST['post_type'] !== 'all' ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.InputNotValidated
					$args['taxonomy'] = sanitize_text_field( wp_unslash( $_POST['post_type'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.InputNotValidated
				}

				$response = wp_list_pluck( get_terms( $args ), 'name', 'term_id' );
				break;
			case 'user':
				$users = [];

				foreach ( get_users( [ 'include' => $ids ] ) as $user ) {
					$user_id           = $user->ID;
					$user_name         = $user->display_name . '-' . $user->ID;
					$users[ $user_id ] = $user_name;
				}

				$response = $users;
				break;
			default:
				$post_info = get_posts(
					[
						'post_type' => sanitize_text_field( wp_unslash( $_POST['post_type'] ?? 'post' ) ), // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.InputNotValidated
						'include'   => implode( ',', $ids ),
					]
				);
				$response  = wp_list_pluck( $post_info, 'post_title', 'ID' );
		}

		if ( ! empty( $response ) ) {
			wp_send_json_success( [ 'results' => $response ] );
		} else {
			wp_send_json_error( [] );
		}
	}


	/**
	 * @param $fields
	 * @param $obj
	 *
	 * @return mixed
	 */
	public function category_settings_cols_modity( $fields, $obj ){
		$modify_array = array(
			array(
				'id'        => 'rtcl_sec_responsive',
				'condition'   => [ 'rtcl_cats_style' => ['style-1', 'style-2']],
			),
			array(
				'id'        => 'rtcl_col_xl',
				'condition'   => [ 'rtcl_cats_style' => ['style-1', 'style-2']],
			),
		);
		$fields = $obj->modify_controls( $modify_array, $fields );

		return $fields;
	}


	/**
	 * Undocumented function
	 *
	 * @param [array] $data array for list style.
	 *
	 * @return array
	 */
	public static function added_el_listing_category_box_style( $data ) {
		$data['style-3'] = esc_html__( 'Style 3', 'clawyer-core' );
		return $data;
	}

	/**
	 * @param $fields
	 * @param $obj
	 *
	 * @return mixed
	 */
	public function category_settings_add_new_controls( $fields, $obj ){
		if( 'rtcl-listing-cat-box' == $obj->rtcl_base ){
			$label_color = array(
				array(
					'id'      => 'items_margin',
					'mode'    => 'responsive',
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', '%' ),
					'label'   => esc_html__( 'Item Margin', 'clawyer-core' ),
					'selectors'  => array(
						'{{WRAPPER}} .rtcl-categories.rtcl-categories-elementor .cat-item-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				),
			);
			$fields = $obj->insert_new_controls( 'rtcl_wrapper_padding', $label_color, $fields );
		}
		return $fields;
	}

	public function category_slider_settings_add_new_controls( $fields, $obj ){
		if( 'rtcl-listing-category-slider' == $obj->rtcl_base ){
			$label_color = array(
				array(
					'id'      => 'rtcl_background_body_border_radius',
					'mode'    => 'responsive',
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', '%' ),
					'label'   => esc_html__( 'Border Radius', 'clawyer-core' ),
					'selectors' => array(
						'{{WRAPPER}} .cat-item-wrap .cat-details' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					),
				),
			);
			$fields = $obj->insert_new_controls( 'rtcl_content_alignment', $label_color, $fields );
		}
		return $fields;
	}


	/**
	 * Undocumented function
	 *
	 * @param [array] $data array for list style.
	 *
	 * @return array
	 */
	public static function added_listings_showcase_style_grid( $data = [] ) {
		$data['style-6'] = [
			'title' => esc_html__( 'Style 6', 'clawyer-core' ),
			'url'   => CLAWYER_CORE_BASE_URL ."assets/images/layout/grid-style-06.png",
		];
		$data['style-7'] = [
			'title' => esc_html__( 'Style 7', 'clawyer-core' ),
			'url'   => CLAWYER_CORE_BASE_URL ."assets/images/layout/grid-style-06.png",
		];
		return $data;
	}


	/**
	 * @param $fields
	 * @param $obj
	 *
	 * @return mixed
	 */
	public function listing_widget_grid_general_field( $fields, $obj ){
		$modify_array = array(
			array(
				'id'        => 'rtcl_listings_column',
				'devices' => [ 'widescreen', 'desktop', 'laptop', 'tablet_extra', 'tablet', 'mobile_extra', 'mobile' ],
				'condition' => [
					'rtcl_listings_view' => 'grid',
				],
			),
		);
		$fields = $obj->modify_controls( $modify_array, $fields );

		return $fields;
	}

	/**
	 * @param $fields
	 * @param $obj
	 *
	 * @return mixed
	 */
	public function listing_widget_content_visibility_field( $fields, $obj ){
		$new_control = array(
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'rtcl_show_address',
				'label'       => __( 'Show Address', 'clawyer-core' ),
				'label_on'    => __( 'On', 'clawyer-core' ),
				'label_off'   => __( 'Off', 'clawyer-core' ),
				'default'     => '',
				'description' => __( 'Show or Hide Address. Default: Off', 'clawyer-core' ),
				'condition' => array(
					'rtcl_listings_grid_style' => array( 'style-6', 'style-7', 'style-8' ),
				),
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'rtcl_show_review',
				'label'       => __( 'Show Review', 'clawyer-core' ),
				'label_on'    => __( 'On', 'clawyer-core' ),
				'label_off'   => __( 'Off', 'clawyer-core' ),
				'default'     => '',
				'description' => __( 'Show or Hide Review. Default: Off', 'clawyer-core' ),
				'condition' => array(
					'rtcl_listings_grid_style' => array( 'style-6', 'style-7', 'style-8' ),
				),
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'rtcl_show_status',
				'label'       => __( 'Show On/Off Status', 'clawyer-core' ),
				'label_on'    => __( 'On', 'clawyer-core' ),
				'label_off'   => __( 'Off', 'clawyer-core' ),
				'default'     => '',
				'description' => __( 'Show or Hide Review. Default: Off', 'clawyer-core' ),
				'condition' => array(
					'rtcl_listings_grid_style' => array( 'style-6', 'style-7' ),
				),
			),
		);
		$fields = $obj->insert_new_controls( 'rtcl_show_category', $new_control, $fields );
		return $fields;
	}

}