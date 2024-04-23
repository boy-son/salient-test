<?php
/**
* Post meta under post title partial
*
* Used when "Classic" masonry style is selected.
*
* @version 10.5
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

global $post;

?>

<span class="meta-author">
  <span><?php echo esc_html__( 'By', 'salient' ); ?></span> <?php the_author_posts_link(); ?>
</span><span class="meta-category"><?php the_category( ', ' ); ?>
</span><?php if ( comments_open() ) { ?>
<span class="meta-comment-count"><a href="<?php comments_link(); ?>"><?php comments_number( esc_html__( 'No Comments', 'salient' ), esc_html__( 'One Comment ', 'salient' ), esc_html__( '% Comments', 'salient' ) ); ?></a>
</span>
<?php } ?>
