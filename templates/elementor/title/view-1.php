<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 *
 * @var $title string
 * @var $bg_title string
 * @var $bg_title_style string
 * @var $top_sub_title string
 * @var $main_title_tag string
 * @var $description string
 *
 */

?>
<div class="section-title-wrapper">
	<!--Background Title-->
	<?php if ( $bg_title ): ?>
		<div class="bg-title-wrap">
            <span class="background-title <?php echo esc_attr( $bg_title_style ) ?>">
                <?php echo esc_html( $bg_title ); ?>
            </span>
		</div>
	<?php endif; ?>

	<div class="title-inner-wrapper">
		<!--Top Sub Title-->
		<?php if ( $top_sub_title ): ?>
			<div class="top-sub-title-wrap">
                <span class="top-sub-title">
                    <?php echo esc_html( $top_sub_title ); ?>
                </span>
			</div>
		<?php endif; ?>

		<!--Main Title-->
		<?php if ( $title ): ?>
            <<?php echo esc_attr( $main_title_tag ) ?> class="main-title"><?php echo wp_kses_post( $title ); ?></<?php echo esc_attr( $main_title_tag ) ?>>
        <?php endif; ?>

        <!--Description-->
        <?php if ( $description ): ?>
            <div class="description"><?php echo wp_kses_post( $description ); ?></div>
        <?php endif; ?>
    </div>
</div>