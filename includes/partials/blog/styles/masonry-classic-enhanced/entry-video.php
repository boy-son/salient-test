<?php
/**
 * Default Post Format Template 
 *
 * Used when "Classic Enhanced" masonry style is selected.
 *
 * @version 12.5
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;
global $nectar_options;

$masonry_size_pm             = get_post_meta( $post->ID, '_post_item_masonry_sizing', true );
$masonry_item_sizing         = ( ! empty( $masonry_size_pm ) ) ? $masonry_size_pm : 'regular';
$nectar_post_class_additions = $masonry_item_sizing . ' masonry-blog-item';
$excerpt_length              = ( ! empty( $nectar_options['blog_excerpt_length'] ) ) ? intval( $nectar_options['blog_excerpt_length'] ) : 15;

$date_functionality = (isset($nectar_options['post_date_functionality']) && !empty($nectar_options['post_date_functionality'])) ? $nectar_options['post_date_functionality'] : 'published_date';

if( 'last_editied_date' === $date_functionality ) {
  $date = get_the_modified_date();
} else {
  $date = get_the_date();
}

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $nectar_post_class_additions ); ?>>  
    
  <div class="inner-wrap animated">
    
    <div class="post-content">
      
      <?php
        // Featured image.
        get_template_part( 'includes/partials/blog/styles/masonry-classic-enhanced/post-image' );
      ?>
      
      <div class="content-inner">
        
        <a class="entire-meta-link" href="<?php the_permalink(); ?>" aria-label="<?php the_title(); ?>"></a>
        
        <?php 
        
          // Output categories.
          get_template_part( 'includes/partials/blog/styles/masonry-classic-enhanced/post-categories' );
        
        ?>
        
        <div class="article-content-wrap">
          
          <?php 
          if( ! has_post_thumbnail() ) {
            get_template_part( 'includes/partials/blog/media/play-button' );
          }
          ?>
          
          <div class="post-header">
            <?php echo '<span>' . esc_html($date) . '</span>'; ?>
            <h3 class="title"><a href="<?php the_permalink(); ?>"> <?php the_title(); ?></a></h3>
          </div>
          
          <?php 

            // Excerpt.
            echo '<div class="excerpt">';
            
              if ( $masonry_item_sizing === 'wide_tall' && ! empty( $post->post_excerpt ) ) {
                echo the_excerpt();
              } elseif ( $masonry_item_sizing === 'large_featured' ) {
                echo nectar_excerpt( $excerpt_length * 2 );
              } else {
                echo nectar_excerpt( $excerpt_length );
              }
              
            echo '</div>';

            do_action('nectar_after_archive_post_item_content');

          ?>

        </div><!--article-content-wrap-->
        
      </div><!--/content-inner-->
      
      <?php get_template_part( 'includes/partials/blog/styles/masonry-classic-enhanced/post-bottom-meta' ); ?>
        
    </div><!--/post-content-->
      
  </div><!--/inner-wrap-->
    
  </article>