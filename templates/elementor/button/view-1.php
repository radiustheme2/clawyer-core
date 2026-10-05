<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 * @var $btnlink         array
 * @var $icon_position   string
 * @var $style           string
 * @var $icon            array
 * @var $btntext         string
 */

use Elementor\Icons_Manager;

$attr = '';
if ( !empty( $btnlink['url'] ) ) {
	$attr  = 'href="' . $btnlink['url'] . '"';
	$attr .= !empty( $btnlink['url']['is_external'] ) ? ' target="_blank"' : '';
	$attr .= !empty( $btnlink['url']['nofollow'] ) ? ' rel="nofollow"' : '';
}

?>

<div class="button-arapper icon-position-<?php echo esc_attr( $icon_position ); ?>">
    <a <?php echo wp_kses_post( $attr ); ?> class="button-style-<?php echo esc_attr( $style ); ?>">
		<?php
            if ($icon_position == 'before') {
                Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] );
            }
            echo esc_html( $btntext );
            if ($icon_position == 'after') {
                Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] );
            }
		?>
    </a>
</div>

