<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0.4
 */

namespace RT\ClawyerCore\Controllers;

if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Plugin;
use FluentForm\App\Models\Form;
use FluentForm\App\Models\FormMeta;
use FluentForm\Framework\Support\Arr;
use RT\ClawyerCore\Modules\UserImport;
use RT\ClawyerCore\Traits\SingletonTraits;

class DemoImportController {
	use SingletonTraits;

	public function __construct() {
		add_action( 'ocdi/after_import',          array( $this, 'rt_import_user_setup' ) );
		add_action( 'ocdi/before_widgets_import', array( $this, 'remove_default_widgets' ) );
		add_filter( 'ocdi/import_files',          array( $this, 'demo_config' ) );
		add_filter( 'ocdi/before_content_import', array( $this, 'before_import' ) );
		add_filter( 'ocdi/after_import',          array( $this, 'after_import' ) );
		add_action( 'ocdi/after_import',          [ $this, 'after_import_options_setup' ] );
		add_filter( 'ocdi/disable_pt_branding',   '__return_true' );
		add_action( 'init',                       array( $this, 'rewrite_flush_check' ) );
		add_action( 'template_redirect', [ __CLASS__, 'user_import' ] );
		add_action( 'admin_footer', [ $this, 'footer_style' ] );
	}

	/**
	 * @return void
	 */
	public static function user_import() {
		if ( isset( $_GET['export_user'] ) && $_GET['export_user'] == 1 ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			UserImport::export_users();
		}
	}

	public function remove_default_widgets( $selected_import ) {
		delete_option( 'sidebars_widgets' );
	}

	/**
	 * @param $selected_import
	 *
	 * @return void
	 */
	public function rt_import_user_setup( $selected_import ) {
		// Update post author id
		global $wpdb;
		$id    = get_current_user_id();
		$query = "UPDATE $wpdb->posts SET post_author = $id";
		$wpdb->query( $query ); //phpcs:ignore

		/// Import Users
		new UserImport();
	}

	/**
	 * @return array
	 */
	public function demo_config() {

		$demos_array = array(
			'demo1' => array(
				'title' => __( 'Home One', 'clawyer-core' ),
				'page'  => __( 'Home One', 'clawyer-core' ),
				'screenshot' => CLAWYER_CORE_DEMO_URL . 'screenshots/1.png',
				'preview_link' => 'https://radiustheme.com/demo/wordpress/themes/clawyer/',
			),
			'demo2' => array(
				'title' => __( 'Home Two', 'clawyer-core' ),
				'page'  => __( 'Home Two', 'clawyer-core' ),
				'screenshot' => CLAWYER_CORE_DEMO_URL . 'screenshots/2.png',
				'preview_link' => 'https://radiustheme.com/demo/wordpress/themes/clawyer/home-two/',
			),
		);

		$config = array();
		$import_path  = trailingslashit( CLAWYER_CORE_DEMO_CONTENT ) . 'sample-data/';

		foreach ( $demos_array as $key => $demo ) {
			$config[] = array(
				'import_file_id'               => $key,
				'import_page_name'             => $demo['page'],
				'import_file_name'             => $demo['title'],
				'local_import_file'            => $import_path . 'contents.xml',
				'local_import_widget_file'     => $import_path . 'widgets.wie',
				'local_import_customizer_file' => $import_path . 'customizer.dat',
				'import_preview_image_url'   => $demo['screenshot'],
				'preview_url'                => $demo['preview_link'],
			);
		}

		return $config;
	}

	/**
	 * @param $selected_import
	 *
	 * @return void
	 */
	public function before_import( $selected_import ) {
		$this->remove_default_rtcl_pages();
		$this->remove_rtcl_builder_default_form();
		/* = Hello World Post = */
		$this->set_draft_post();

		$this->import_rtcl_builder_forms();

		update_option( 'rtcl_importing_demo', 'yes' );
	}

	/**
	 * @return void
	 */
	private function remove_default_rtcl_pages() {
		$listings     = $this->get_page_by_title( 'Listings', 'page' );
		$listing_form = $this->get_page_by_title( 'Listing Form', 'page' );
		$myaccount    = $this->get_page_by_title( 'My Account', 'page' );
		$checkout     = $this->get_page_by_title( 'Checkout', 'page' );

		if ( $listings ) {
			wp_delete_post( $listings, true );
		}

		if ( $listing_form ) {
			wp_delete_post( $listing_form, true );
		}

		if ( $myaccount ) {
			wp_delete_post( $myaccount, true );
		}

		if ( $checkout ) {
			wp_delete_post( $checkout, true );
		}
	}

	private function remove_rtcl_builder_default_form() {
		global $wpdb;
		$table = $wpdb->prefix . 'rtcl_forms';
		$wpdb->query( "TRUNCATE TABLE $table" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.SchemaChange
	}

	private function import_rtcl_builder_forms() {
		$formFile = trailingslashit( CLAWYER_CORE_DEMO_CONTENT ) . 'sample-data/rtclform.json';
		$fileExists = file_exists( $formFile );
		if ( $fileExists ) {
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			$data  = file_get_contents( $formFile );
			$forms = json_decode( $data, true );

			if ( $forms && is_array( $forms ) ) {
				foreach ( $forms as $formItem ) {
					\Rtcl\Models\Form\Form::query()->insert( $formItem );
				}
			}
		}
	}

	/**
	 * @param $selected_import
	 *
	 * @return void
	 */
	public function after_import( $selected_import ) {
		$this->assign_menu( $selected_import['import_file_id'] );
		$this->assign_frontpage( $selected_import );

		$this->update_permalinks();
		$this->rtcl_page_update_options();
//		$this->rtcl_taxonomy_updater();
		$this->update_ajax_filter_options();
		$this->disable_read_listing_data();

		/* = Elementor = */
		$this->set_elementor_active_kit();
		$this->set_elementor_settings();
		/* = Fluent Form = */
		$this->import_fluent_forms( $selected_import );

		update_option('elementor_allow_unfiltered_files', true );
		flush_rewrite_rules();
		update_option( 'clawyer_ocdi_importer_rewrite_flash', true );
	}

	/**
	 * @param $demo
	 *
	 * @return void
	 */
	private function assign_menu( $demo ) {
		$primary  = get_term_by( 'name', 'Main Menu', 'nav_menu' );

		set_theme_mod( 'nav_menu_locations', array(
			'primary'  => $primary->term_id,
		));
	}

	/**
	 * @param $selected_import
	 *
	 * @return void
	 */
	private function assign_frontpage( $selected_import ) {
		$blog_page  = $this->get_page_by_title( 'Blog', 'page' );
		$front_page = $this->get_page_by_title( $selected_import['import_page_name'], 'page' );

		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front',  $front_page );
		update_option( 'page_for_posts', $blog_page );
	}

	/**
	 * @return void
	 */
	private function rtcl_page_update_options() {
		$listings     = $this->get_page_by_title( 'Listings', 'page' );
		$listing_form = $this->get_page_by_title( 'Listing Form', 'page' );
		$myaccount    = $this->get_page_by_title( 'My Account', 'page' );
		$checkout     = $this->get_page_by_title( 'Checkout', 'page' );
		$compare      = $this->get_page_by_title( 'Compare Page', 'page' );

		$data = array(
			'rtcl_advanced_settings'          => array(
				'permalink'    => 'listing',
				'listings'     => $listings,
				'listing_form' => $listing_form,
				'myaccount'    => $myaccount,
				'checkout'     => $checkout,
				'compare_page' => $compare,
			)
		);

		foreach ( $data as $key => $value ) {
			$defaults = get_option( $key, array() );
			$args     = wp_parse_args( $value, $defaults );
			update_option( $key, $args );
		}
	}

	/**
	 * @param $array
	 * @param $tax_ids
	 *
	 * @return void
	 */
	public function replaceTaxonomy( &$array, $tax_ids ) {
		if ( ! empty( $array ) ) {
			foreach ( $array as $key => &$value ) {
				if ( ! in_array( $key, [ "rtcl_listings_by_categories", "rtcl_cats" ] ) ) {
					if ( is_array( $value ) ) {
						$this->replaceTaxonomy( $value, $tax_ids );
					}
				} else {
					if ( is_array( $value ) ) {
						$new_value = [];
						foreach ( $value as $id ) {
							if ( isset( $tax_ids[ $id ] ) ) {
								$new_value[] = $tax_ids[ $id ];
							}
						}
						$value = $new_value;
					} else {
						$value = $tax_ids[ $value ] ?? '';
					}
				}
			}
		}
	}

	public function rtcl_taxonomy_updater() {
		$old_tqxonomies_id = [
			'rtcl_location'     => [
				'california'    => 13,
				'kansas'        => 14,
				'virginia'      => 15,
				'florida'       => 16,
				'texas'         => 17,
				'arlington'     => 18,
				'los-angeles'   => 20,
				'manhattan'     => 22,
				'miami'         => 24,
				'hollywood'     => 25,
				'richmond'      => 26,
			],
			'rtcl_category' => [
				'dogs'     => 9,
				'cats'     => 10,
				'birds'    => 11,
				'rabbit'   => 12,
				'fish'     => 38,
				'others'   => 39,
				'hen'      => 40,
			]
		];

		$args            = [
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
			'fields'         => 'ids',
			'meta_query'     => [
				[
					'key'     => '_elementor_data',
					'compare' => 'EXISTS',
				],
			],
		];
		$query           = get_posts( $args );

		foreach ( $query as $page_id ) {
			$meta_json = get_post_meta( $page_id, '_elementor_data', true );
			$meta_data = json_decode( $meta_json, true );

			$new_taxonomy_ids = [];
			foreach ( $old_tqxonomies_id as $tax => $taxonomies ) {
				foreach ( $taxonomies as $slug => $id ) {
					$term = get_term_by( 'slug', $slug, $tax );
					if ( $term && isset( $term->term_id ) ) {
						$new_taxonomy_ids[ $id ] = $term->term_id;
					}
				}
			}

			if ( ! empty( $new_taxonomy_ids ) ) {
				$this->replaceTaxonomy( $meta_data, $new_taxonomy_ids );
				$updatedJson = wp_slash( wp_json_encode( $meta_data ) );
				update_post_meta( $page_id, '_elementor_data', $updatedJson );

				Plugin::instance()->files_manager->clear_cache();
			}
		}
	}

	public function after_import_options_setup() {
		$json_file = trailingslashit( CLAWYER_CORE_DEMO_CONTENT ) . 'sample-data/rtcl-options.json';

		if ( ! file_exists( $json_file ) ) {
			return;
		}
		$json = file_get_contents( $json_file );
		$data = json_decode( $json, true );

		// Import settings
		if ( ! empty( $data['settings'] ) ) {
			foreach ( $data['settings'] as $setting ) {
				update_option( $setting['key'], $setting['value'] );
			}
		}
	}

	private function update_ajax_filter_options() {
		$filter_options = array(
			'ajax-filter' => array(
				'name'  => 'Ajax Filter',
				'items' => [
					[
						'id'          => 'search',
						'title'       => 'Keyword',
						'placeholder' => 'Keyword',
					],
					[
						'id'         => 'category',
						'title'      => 'Category',
						'type'       => 'Select Services',
						'hide_empty' => '1',
						'show_count' => '1',
						'more_less'  => '1',
					],
					[
						'id'         => 'location',
						'title'      => 'checkbox',
						'type'       => 'Select Location',
						'hide_empty' => '1',
						'show_count' => '1',
						'more_less'  => '1',
					],
					[
						'id'    => 'cf',
						'title' => 'Custom fields',
					],
					[
						'id'    => 'rating',
						'title' => 'Rating',
					]

				],
			),
		);

		update_option( 'rtcl_filter_settings', $filter_options );
	}

	private function disable_read_listing_data() {
		delete_option( 'rtcl_importing_demo' );
	}

	public function rewrite_flush_check() {
		if ( get_option( 'clawyer_ocdi_importer_rewrite_flash' ) == true  ) {
			flush_rewrite_rules();
			delete_option( 'clawyer_ocdi_importer_rewrite_flash' );
		}
	}

	private function update_permalinks() {
		update_option( 'permalink_structure', '/%postname%/' );
	}

	/**
	 * Sets the active Elementor kit.
	 *
	 * @return RTDemoimport
	 */
	private function set_elementor_active_kit() {
		global $wpdb;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$pageIds = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT ID FROM $wpdb->posts WHERE (post_name = %s OR post_title = %s) AND post_type = 'elementor_library' AND post_status = 'publish'",
				'default-kit',
				'Default Kit'
			)
		);

		if ( ! is_null( $pageIds ) ) {
			$pageId    = 0;
			$deleteIds = [];

			// Retrieve page with greater id and delete others.
			if ( count( $pageIds ) > 1 ) {
				foreach ( $pageIds as $page ) {
					if ( $page->ID > $pageId ) {
						if ( $pageId ) {
							$deleteIds[] = $pageId;
						}
						$pageId = $page->ID;
					} else {
						$deleteIds[] = $page->ID;
					}
				}
			} else {
				$pageId = $pageIds[0]->ID;
			}

			// Update `elementor_active_kit` page.
			if ( $pageId > 0 ) {
				wp_update_post(
					[
						'ID'        => $pageId,
						'post_name' => sanitize_title( 'Default Kit' ),
					]
				);
				update_option( 'elementor_active_kit', $pageId );
			}
		}

		return $this;
	}

	/**
	 * Sets the Elementor default settings.
	 *
	 * @return RTDemoimport
	 */
	private function set_elementor_settings() {
		update_option( 'elementor_disable_color_schemes', 'yes' );
		update_option( 'elementor_disable_typography_schemes', 'yes' );
		update_option( 'elementor_unfiltered_files_upload', '1' );
		update_option( 'elementor_experiment-e_font_icon_svg', 'inactive' );
		update_option('elementor_container_width', '1320' );

		return $this;
	}

	/**
	 * Updates the 'Hello World!' blog post by making it a draft
	 *
	 * @return RTDemoimport
	 */
	private function set_draft_post() {
		$helloWorld = $this->get_page_by_title( 'Hello World!', 'post' );

		if ( $helloWorld ) {
			$post = get_post( $helloWorld );

			if ( $post ) {
				$helloWorldArgs = [
					'ID'          => $post->ID,
					'post_status' => 'draft',
				];

				wp_update_post( $helloWorldArgs );
			}
		}

		return $this;
	}

	/**
	 * Import fluent forms.
	 *
	 * @param array $selected_import Import array.
	 *
	 * @return void
	 */
	private function import_fluent_forms( $selected_import ) {
		if ( empty( $selected_import['local_import_file'] ) || ! is_plugin_active( 'fluentform/fluentform.php' ) ) {
			return;
		}

		$import_file = $selected_import['local_import_file'];
		$formFile    = trailingslashit( CLAWYER_CORE_DEMO_CONTENT ) . 'sample-data/fluentform.json';

		$fileExists  = file_exists( $formFile );

		if ( $fileExists ) {
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			$data          = file_get_contents( $formFile );
			$forms         = json_decode( $data, true );
			$insertedForms = [];

			if ( $forms && is_array( $forms ) ) {
				foreach ( $forms as $formItem ) {
					$formFields = wp_json_encode( [] );

					// phpcs:ignore Generic.CodeAnalysis.AssignmentInCondition.Found, Squiz.PHP.DisallowMultipleAssignments.FoundInControlStructure
					if ( $fields = Arr::get( $formItem, 'form', '' ) ) {
						$formFields = wp_json_encode( $fields );
						// phpcs:ignore Generic.CodeAnalysis.AssignmentInCondition.Found, Squiz.PHP.DisallowMultipleAssignments.FoundInControlStructure
					} elseif ( $fields = Arr::get( $formItem, 'form_fields', '' ) ) {
						$formFields = wp_json_encode( $fields );
					}

					$form = [
						'title'       => Arr::get( $formItem, 'title' ),
						'form_fields' => $formFields,
						'status'      => Arr::get( $formItem, 'status', 'published' ),
						'has_payment' => Arr::get( $formItem, 'has_payment', 0 ),
						'type'        => Arr::get( $formItem, 'type', 'form' ),
						'created_by'  => get_current_user_id(),
					];

					if ( Arr::get( $formItem, 'conditions' ) ) {
						$form['conditions'] = Arr::get( $formItem, 'conditions' );
					}

					if ( isset( $formItem['appearance_settings'] ) ) {
						$form['appearance_settings'] = Arr::get( $formItem, 'appearance_settings' );
					}

					$formId                   = Form::insertGetId( $form );
					$insertedForms[ $formId ] = [
						'title'    => $form['title'],
						'edit_url' => admin_url( 'admin.php?page=fluent_forms&route=editor&form_id=' . $formId ),
					];

					if ( isset( $formItem['metas'] ) ) {
						foreach ( $formItem['metas'] as $metaData ) {
							$settings = [
								'form_id'  => $formId,
								// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
								'meta_key' => Arr::get( $metaData, 'meta_key' ),
								'value'    => Arr::get( $metaData, 'value' ),
							];

							FormMeta::insert( $settings );
						}
					} else {
						$oldKeys = [
							'formSettings',
							'notifications',
							'mailchimp_feeds',
							'slack',
						];

						foreach ( $oldKeys as $key ) {
							if ( isset( $formItem[ $key ] ) ) {
								FormMeta::persist( $formId, $key, wp_json_encode( Arr::get( $formItem, $key ) ) );
							}
						}
					}
				}
			}
		}
	}

	private function get_page_by_title( $title, $post_type = 'page' ) {
		$page = get_posts( array(
			'post_type'   => $post_type,
			'name'        => sanitize_title( $title ),
			'post_status' => 'publish',
			'numberposts' => 1
		) );
		if ( $page ) {
			$page = $page[0];
			return $page->ID;
		} else {
			return null;
		}
	}

	public function footer_style() {
		if ( isset( $_GET['page'] ) && $_GET['page'] === 'one-click-demo-import' ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			?>
            <style>
                .ocdi__theme-about-info .theme-description,
                .ocdi__theme-about-info .theme-author,
                .ocdi__theme-about-info .top-content,
                .ocdi__theme-about-info .theme-tags,
                .ocdi__theme-about-info hr,
                .ocdi__theme-about-screenshots {
                    display: none;
                }

                .ocdi__theme-about {
                    top: 25px;
                    right: 100px;
                    position: absolute;
                }

                .ocdi__content-container .notice:not(#setting-error-tgmpa, .notice-error),
                .ocdi__admin-notices.js-ocdi-admin-notices-container,
                .ocdi__content-container .ocdi__intro-text p {
                    display: none !important;
                }

                .ocdi__content-container .ocdi__gl-item-container {
                    width: 1122px;
                    margin: auto;
                    max-width: 100%;
                    justify-content: center;
                }

                .ocdi__content-container .ocdi__gl-item-buttons {
                    position: absolute;
                    top: 8px;
                    right: 7px;
                    display: block;
                    transform: translateX(-15px);
                    transition: 0.4s;
                    opacity: 0;
                }

                .ocdi__gl-item:hover .ocdi__gl-item-buttons {
                    transform: translateX(0);
                    opacity: 1;
                }

                .ocdi__content-container .ocdi__gl-item-footer {
                    position: relative;
                }

                .ocdi__content-container .ocdi__gl-item-button {
                    color: #007e33;
                    background: #dbf6e6;
                    border-color: #9fc2ad;
                    padding: 0 12px;
                }

                .ocdi__content-container .ocdi__gl-item-button + .ocdi__gl-item-button {
                    margin-left: 5px;
                    color: #FFFFFF;
                    background: #00c194 !important;
                    border: none;
                }

                .ocdi__content-container .ocdi__gl-item-button:hover {
                    color: #FFFFFF !important;
                    background: #028c6c !important;
                    border-color: #028c6c !important;
                    transition: 0.4s;
                }

                .ocdi__content-container .ocdi__gl-item-title {
                    display: block !important;
                    text-align: left;
                }

                .ocdi hr {
                    margin: 15px 0 !important;
                }
            </style>
			<?php
		}
	}
}

new DemoImportController();