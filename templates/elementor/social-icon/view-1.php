<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 * @var $label              string
 * @var $title_tag          array
 * @var $description        array
 * @var $social_icon        array
 */

?>
<div class="rt-social-icon">
	<?php if ( $label ): ?>
        <label><?php echo esc_html( $label ); ?></label>
	<?php endif; ?>
    <div class="rt-social-item">
		<?php foreach ( $social_icon as $social_icons ):
			$attr = '';
			if ( !empty( $social_icons['link']['url'] ) ) {
				$attr  = 'href="' . $social_icons['link']['url'] . '"';
				$attr .= !empty( $social_icons['link']['is_external'] ) ? ' target="_blank"' : '';
				$attr .= !empty( $social_icons['link']['nofollow'] ) ? ' rel="nofollow"' : '';
			}
			?>
            <a class="elementor-repeater-item-<?php echo esc_attr($social_icons['_id']) ?>" <?php echo wp_kses_post( $attr ); ?> ><?php \Elementor\Icons_Manager::render_icon( $social_icons['social_icon'] ) ; ?></a>
		<?php endforeach; ?>
    </div>
</div>