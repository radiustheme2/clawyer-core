<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 *
 * @var $bg_title string
 * @var $bg_title_style string
 * @var $top_sub_title string
 * @var $top_title_icon string
 * @var $icon_position string
 * @var $title_custom_html string
 * @var $main_title_tag string
 * @var $description string
 *
 */

?>

<div class="section-title-wrapper writing-title">
    <!--Background Title-->
	<?php if ( $bg_title ): ?>
        <div class="bg-title-wrap">
            <span class="background-title <?php echo esc_attr( $bg_title_style ); ?>">
                <?php echo esc_html( $bg_title ); ?>
            </span>
        </div>
	<?php endif; ?>

    <div class="title-inner-wrapper">
        <!--Top Sub Title-->
		<?php if ( $top_sub_title ): ?>
            <div class="top-sub-title-wrap">
                <span class="top-sub-title">
                    <?php
                    if ( $top_title_icon && ( 'left' == $icon_position || 'both' == $icon_position ) ) {
	                    echo '<i style="margin-right:5px" class="' . esc_attr( $top_title_icon ) . '" aria-hidden="true"></i>';
                    }
                    echo esc_html( $top_sub_title );
                    if ( $top_title_icon && ( 'right' == $icon_position || 'both' == $icon_position ) ) {
	                    echo '<i style="margin-left:5px;transform:scaleX(-1)" class="' . esc_attr( $top_title_icon ) . '" aria-hidden="true"></i>';
                    }
                    ?>
                </span>
            </div>
		<?php endif; ?>


        <!--Main Title-->
		<?php if ( $title_custom_html ): ?>
            <<?php echo esc_attr( $main_title_tag ); ?> class="main-title cd-headline clip"><?php echo wp_kses_post( $title_custom_html ); ?></<?php echo esc_attr( $main_title_tag ); ?>>
        <?php endif; ?>

        <!--Description-->
        <?php if ( $description ): ?>
            <div class="description"><?php echo wp_kses_post( $description ); ?></div>
        <?php endif; ?>
    </div>
</div>