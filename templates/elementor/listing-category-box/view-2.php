<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 * @var $listing_cat_id             string
 * @var $image                      array
 * @var $cat_extra_text             array
 * @var $icon_type                  string
 * @var $icon_display               string
 * @var $counter_label              string
 * @var $count_display              string
 * @var $button_switch              string
 */

use RT\Clawyer\Plugins\Listing_Functions;

?>
<div class="rt-listing-category-box layout-2">
	<?php
        $term_id = $listing_cat_id;
	    $term = get_term( $term_id );
	    if ( ! is_wp_error( $term ) && ! empty( $term ) ){
            $count = Listing_Functions::rt_term_post_count($term_id, 'rtcl_category');
		?>
        <div class="category-box">
            <?php if (!empty( $image['id'] )){ ?>
                <div class="category-box-img">
                    <?php if ( $image['id'] ) { ?>
                        <?php echo wp_get_attachment_image( $image['id'], 'full' ); ?>
                    <?php } ?>
                </div>
            <?php } ?>
            <div class="category-box-content icon-display-<?php echo esc_attr( $icon_display ); ?>">
                <?php if ( $icon_display ){ ?>
                    <div class="category-icon">
                        <?php echo wp_kses_post( Listing_Functions::listing_cat_icon( $term_id, $icon_type ) ); ?>
                    </div>
                 <?php } ?>
                <h3 class="listing-category">
                    <a href="<?php echo esc_url( get_term_link( get_term( $term_id ), get_term( $term_id )->name ) ); ?>">
	                    <?php
                            printf(
                                '%s %s',
                                esc_html( get_term( $term_id )->name ),
                                esc_html( $cat_extra_text )
                            );
	                    ?>
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
    <?php } ?>
</div>