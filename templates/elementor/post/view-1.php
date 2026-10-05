<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 *
 * @var $thumbnail_visibility       string
 * @var $post_thumbnail_size        string
 * @var $cat_visibility             string
 * @var $content_visibility         string
 * @var $readmore_visibility        string
 * @var $readmore_text              string
 * @var $author_visibility          string
 * @var $date_visibility            string
 * @var $comment_visibility         string
 * @var $reading_visibility         string
 * @var $content_limit              string
 */


$has_entry_meta  = ( $author_visibility || $date_visibility || $comment_visibility || $reading_visibility ) ? true : false;

$content = wp_trim_words( get_the_excerpt(), $content_limit, '.' );
$content = "<p>$content</p>";

$comments_number = get_comments_number();
// translators: %s is the number of views.
$comments_text   = sprintf( _n( 'Comment: %s', 'Comments: %s', $comments_number, 'clawyer-core' ), number_format_i18n( $comments_number ) );

?>
    <div class="article-inner-wrapper">
	    <?php if( 'visible' === $thumbnail_visibility ) { ?>
        <div class="thumbnai-wrapper">
	        <?php if ( $cat_visibility ) { ?>
                <div class="separate-meta title-above-meta"><?php echo wp_kses_post( clawyer_posted_in() ); ?></div>
	        <?php } ?>
            <?php clawyer_post_thumbnail( $post_thumbnail_size ); ?>
        </div>
	    <?php } ?>
        <div class="entry-wrapper">
            <header class="entry-header">
	            <?php if ( $has_entry_meta ){ ?>
                    <div class="clawyer-post-meta">
                        <ul class="entry-meta">
				            <?php if ( $author_visibility ) { ?>
                                <li><i class="icon-author-2"></i><?php echo wp_kses_post( clawyer_posted_by() ); ?></li>
				            <?php } if ( $date_visibility ) { ?>
                                <li><i class="icon-calander"></i><?php echo wp_kses_post( clawyer_posted_on() ); ?></li>
				            <?php } if ( $comment_visibility ) { ?>
                                <li><i class="icon-comments"></i><a href="<?php echo esc_url( get_comments_link( get_the_ID() ) ); ?>"><?php echo wp_kses( $comments_text , 'allowed_html' );?></a></li>
				            <?php } if ( $reading_visibility ) { ?>
                                <li><i class="icon-clock"></i><?php echo wp_kses_post( clawyer_reading_time() ); ?></li>
				            <?php } ?>
                        </ul>
                    </div>
	            <?php } ?>
				<?php the_title( sprintf( '<h3 class="entry-title default-max-width"><a href="%s">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
            </header>
	        <?php if( 'visible' === $content_visibility ) { ?>
            <div class="entry-content">
                <?php echo wp_kses_post( $content );?>
            </div>
	        <?php } ?>
            <?php if( 'visible' === $readmore_visibility ) { ?>
            <div class="clawyer-button entry-footer">
                <a class="btn button-2" href="<?php the_permalink();?>">
                    <div class="btn-wrap">
                        <span class="btn-text1"><?php echo esc_html( $readmore_text );?><i class="icon-rt-arrow-right-1"></i></span>
                        <span class="btn-text2"><?php echo esc_html( $readmore_text );?><i class="icon-rt-arrow-right-1"></i></span>
                    </div>
                </a>
            </div>
            <?php } ?>
        </div>
    </div>
