<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/infobox/view-1.php
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

use Elementor\Icons_Manager;

$attr = $tf = '';
if ( !empty( $link['url'] ) ) {
	$attr  = 'href="' . $link['url'] . '"';
	$attr .= !empty( $link['url']['is_external'] ) ? ' target="_blank"' : '';
	$attr .= !empty( $link['url']['nofollow'] ) ? ' rel="nofollow"' : '';
}
?>


<div class="moving-text-wrap d-xl-block d-none">
    <div class="text-img">
		<?php
		if ($moving_image['id']) {
			echo wp_get_attachment_image( $moving_image['id'], 'full' );
		}
		if ( $link['url'] && $link_icon ) {
			?>
            <a <?php echo wp_kses_post( $attr ); ?> class="icon-box">
                <div class="icon"><?php Icons_Manager::render_icon( $link_icon ); ?></div>
            </a>
		<?php } ?>
    </div>
</div>