<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 *
 * @var $data             array
 *
 */
use RT\ClawyerCore\Helper\Fns;
?>

<div class="rt-teams-wrapper <?php echo esc_attr( $data['layout'] ) ?>">

    <div class="team-block">
        <div class="item-img">
	        <?php echo wp_kses_post( \Elementor\Group_Control_Image_Size::get_attachment_image_html( $data, 'thumbnail', 'image' ) ); ?>

			<?php if ( $data['thumb_label'] ) : ?>
                <div class="thumb-label">
                    <?php echo esc_html( $data['thumb_label'] ) ?>
                </div>
			<?php endif; ?>
        </div>

        <div class="item-content">
            <div class="item-title">
                <h3 class="team-name">
                    <?php echo esc_html( $data['team_name'] ) ?>
                </h3>
                <span class="designation"><?php echo esc_html( $data['team_designation'] ) ?></span>
	            <?php if ( $data['company_name'] ) :
		            $target   = $data['company_url']['is_external'] ? ' target="_blank"' : '';
		            $nofollow = $data['company_url']['nofollow'] ? ' rel="nofollow"' : '';
		            ?>
                    <h4 class="company-name">
			            <?php if ( ! empty( $data['company_url']['url'] ) ) : ?>
                        <a href="<?php echo esc_url( $data['company_url']['url'] ); ?>"<?php echo esc_attr( $target ); ?><?php echo esc_attr( $nofollow ); ?>>
				            <?php endif; ?>
				            <?php echo esc_html( $data['company_name'] ); ?>
				            <?php if ( ! empty( $data['company_url']['url'] ) ) : ?>
                        </a>
		            <?php endif; ?>
                    </h4>
	            <?php endif; ?>
            </div>

			<?php if ( $data['team_phone'] ) : ?>
                <div class="item-contact">
                    <div class="item-phn-no">
						<?php printf( "%s %s", esc_html__( 'Call:', 'clawyer-core' ), esc_html( $data['team_phone'] ) ); ?>
                    </div>
                </div>
			<?php endif; ?>
	        <?php
                if ( ! empty( $data['social_icon_list'] ) ) {
	                Fns::get_user_social_info( $data['social_icon_list'] );
                }
	        ?>
        </div>
    </div>
</div>
