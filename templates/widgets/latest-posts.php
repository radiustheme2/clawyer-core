<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 *
 * @var $content string
 *
 */

use RT\Clawyer\Helpers\Fns;

$post_count = get_query_var( 'post_count' );
$first_post = $post_count == 1 ? 'first-one' : 'normal-post';

if ( ! empty( $meta_style ) ) {
	$_meta_style = $meta_style;
} else {
	if ( ! is_single() ) {
		$_meta_style = clawyer_option( 'rt_blog_meta_style' );
	} else {
		$_meta_style = clawyer_option( 'rt_single_meta_style' );
	}
}

$post_classes = Fns::class_list( [ $_meta_style, $first_post ] );

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $post_classes ); ?>>
    <div class="article-inner-wrapper">
		<?php clawyer_post_thumbnail('thumbnail'); ?>
        <div class="entry-wrapper">
            <header class="entry-header">
				<?php
				if ( ! empty( $meta_list ) ) {
					echo wp_kses_post( clawyer_post_meta( [
						'with_list'     => true,
						'with_icon'     => true,
						'include'       => $meta_list,
					] ) );
				}
				the_title( sprintf( '<h4 class="entry-title default-max-width"><a href="%s">', esc_url( get_permalink() ) ), '</a></h4>' );
				?>
            </header>

			<?php if ( $content ) : ?>
                <div class="entry-content">
					<?php echo esc_html( wp_trim_words( get_the_excerpt(), 15 ) ); ?>
                </div>
			<?php endif; ?>
        </div>
    </div>
</article>