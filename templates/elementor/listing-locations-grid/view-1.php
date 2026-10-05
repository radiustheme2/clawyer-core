<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 * @var $display_arrow              string
 * @var $display_pagination         string
 * @var icon_display             string
 * @var $designation_display        string
 * @var $thumb_display              string
 * @var $layout                     string
 * @var $swiper_data                string
 * @var $items                      string
 * @var $quote_icon                 string
 * @var $quote_display              string
 */

use Elementor\Icons_Manager;
use RT\Clawyer\Plugins\Listing_Functions;

if (!empty($items)) {

	$cols_list = '';
	$cols = [
		'xl' => $desktop_xl,
		'lg' => $desktop_lg,
		'md' => $desktop_md,
		'sm' => $tablet,
		'xs' => $mobile
	];

	foreach( $cols as $key => $value ) {
		$cols_list .= 'col-'.$key.'-'.$value.' ';
	}
?>
<div class="rt-listing-locations-grid-wrapper">
    <div class="row elementor-addon justify-content-center">
        <?php
        foreach ($items as $key => $item) {
            $term_id = $item['listing_loc_id'];
	        $term = get_term( $term_id );
	        if ( ! is_wp_error( $term ) && ! empty( $term ) ){
                $term_icon = get_term_meta( $term_id, '_rtcl_icon', true );
                $count = Listing_Functions::rt_term_post_count($term_id, 'rtcl_location');
            ?>
            <div class="<?php echo esc_attr( $cols_list ); ?>">
                <div class="location-box-2">
	                <?php if ( !empty($item['image']['id'] || $item['icon']) && $icon_display ){ ?>
                        <?php if (!empty($item['image']['id'])){ ?>
                        <div class="location-box-img">
                            <?php if ( $item['image']['id'] ) { ?>
                                <?php echo wp_get_attachment_image( $item['image']['id'], 'full' ); ?>
                            <?php } ?>
                        </div>
                        <?php } ?>
                        <?php if (!empty($item['icon'])){ ?>
                            <div class="location-box-icon">
                                <?php Icons_Manager::render_icon( $item['icon'] ); ?>
                            </div>
                        <?php } ?>
                    <?php } ?>
                    <div class="location-box-content">
                        <h3 class="listing-location">
                            <a href="<?php echo esc_url( get_term_link( get_term( $term_id ), get_term( $term_id )->name ) ); ?>">
                                <?php echo esc_html( get_term( $term_id )->name ); ?>
                            </a>
                        </h3>
                        <?php if ( !empty($count || $item['counter_label']) && $count_display ){ ?>
                            <div class="listing-count">
                                <?php if (!empty( $count )){ ?>
                                    <span class="count"><?php echo esc_html( $count ); ?></span>
                                <?php } if (!empty( $item['counter_label'] )){ ?>
                                    <span class="count-label"><?php echo esc_html( $item['counter_label'] ); ?></span>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php }
        } ?>
    </div>
</div>
<?php } ?>