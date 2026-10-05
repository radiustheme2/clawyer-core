<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 * @var $listing_cat_id             string
 * @var $cats_desc                  string
 * @var $image                      array
 * @var $icon_type                  string
 * @var $icon_display               string
 * @var $counter_label              string
 * @var $count_display              string
 * @var $button_switch              string
 */

use RT\Clawyer\Plugins\Listing_Functions;

?>
<div class="rt-listing-category-box layout-3">
    <?php
        $term_id = $listing_cat_id;
        $term = get_term( $term_id );
        if ( ! is_wp_error( $term ) && ! empty( $term ) ){
            $term_icon = get_term_meta( $term_id, '_rtcl_icon', true );
            $count = Listing_Functions::rt_term_post_count($term_id, 'rtcl_category');
        ?>
        <div class="category-box">
	        <?php if ( $image['id'] ) { ?>
                <div class="category-box-img">
                    <?php echo wp_get_attachment_image( $image['id'], 'full' ); ?>
                </div>
	        <?php } ?>
            <div class="category-box-content icon-display-<?php echo esc_attr( $icon_display ); ?>">
                <div class="content-top">
                    <?php if ( $icon_display ){ ?>
                        <div class="category-icon">
                            <?php echo wp_kses_post( Listing_Functions::listing_cat_icon( $term_id, $icon_type ) ); ?>
                        </div>
                     <?php } ?>
                    <div class="name-count">
                        <h3 class="listing-category">
                            <a href="<?php echo esc_url( get_term_link( get_term( $term_id ), get_term( $term_id )->name ) ); ?>">
                                <?php echo esc_html( get_term( $term_id )->name ); ?>
                            </a>
                        </h3>
                        <?php if (!empty( $count || $counter_label ) && $count_display ){ ?>
                            <div class="listing-count">
                                <?php if (!empty( $count )){ ?>
                                    <span class="count"><?php echo esc_html( $count ); ?></span>
                                <?php } if (!empty( $counter_label )){ ?>
                                    <span class="count-label"><?php echo esc_html( $counter_label ); ?></span>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
	            <?php if (!empty( $cats_desc || $button_switch )){ ?>
                    <div class="content-bottom">
			            <?php if (!empty( $cats_desc )){ ?>
                            <div class="desc">
					            <p><?php echo esc_html( $cats_desc ); ?></p>
                            </div>
			            <?php } if (!empty( $button_switch )){ ?>
                            <a class="button-link" href="<?php echo esc_url( get_term_link( get_term( $term_id ), get_term( $term_id )->name ) ); ?>">
					            <?php esc_html_e( 'Read More', 'clawyer-core' ); ?>
                            </a>
			            <?php } ?>
                    </div>
	            <?php } ?>
            </div>
        </div>
    <?php } ?>
</div>