<?php
/**
* Video Post Format Template 
*
* Used when "Classic" standard style is selected.
*
* @version 10.5
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

global $post;
global $nectar_options;

$use_excerpt = ( ! empty( $nectar_options['blog_auto_excerpt'] ) && $nectar_options['blog_auto_excerpt'] === '1' ) ? 'true' : 'false';

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>  
  
  <div class="inner-wrap animated">
    
    <div class="post-content classic">
      
      <?php get_template_part( 'includes/partials/blog/styles/standard-classic/post-meta' ); ?>
      
      <div class="content-inner">
        
        <?php
        // Output Video.
        get_template_part( 'includes/partials/blog/media/video-player' );
        ?>
        
        <div class="article-content-wrap">
          
          <div class="post-header">
            
            <h2 class="title"><a href="<?php the_permalink(); ?>"> <?php the_title(); ?></a></h2>
            
            <span class="meta-author">
              <span><?php echo esc_html__( 'By', 'salient' ); ?></span> <?php the_author_posts_link(); ?>
            </span> 
            <span class="meta-category"><?php the_category( ', ' ); ?>
            </span><?php if ( comments_open() ) { ?>
              <span class="meta-comment-count"> <a href="<?php comments_link(); ?>">
                <?php comments_number( esc_html__( 'No Comments', 'salient' ), esc_html__( 'One Comment', 'salient' ), esc_html__( '% Comments', 'salient' ) ); ?></a>
              </span>
            <?php } ?>
            
          </div><!--/post-header-->
          
          <?php 
          
          // Full content.
          if ( empty( $post->post_excerpt ) && $use_excerpt != 'true'  ) {
            the_content( '<span class="continue-reading">' . esc_html__( 'Read More', 'salient' ) . '</span>' );
          }
          
          // Excerpt.
          else {
            
            echo '<div class="excerpt">';
            the_excerpt();
            echo '</div>';

            do_action('nectar_after_archive_post_item_content');
            
            echo '<a class="more-link" href="' . esc_url( get_permalink() ) . '"><span class="continue-reading">' . esc_html__( 'Read More', 'salient' ) . '</span></a>';
          } 
          
          ?>
          
        </div><!--article-content-wrap-->
        
      </div><!--content-inner-->
      
    </div><!--/post-content-->
    
  </div><!--/inner-wrap-->
  
</article>