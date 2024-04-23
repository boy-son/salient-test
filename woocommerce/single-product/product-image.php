<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 7.8.0
 */

defined( 'ABSPATH' ) || exit;

global $post, $product, $woocommerce, $nectar_options;

$product_gallery_style = (!empty($nectar_options['single_product_gallery_type'])) ? $nectar_options['single_product_gallery_type'] : 'default';

if( in_array($product_gallery_style, array('left_thumb_sticky','two_column_images')) ) {
	wp_enqueue_script('stickykit');
}

if( in_array($product_gallery_style, array('ios_slider','left_thumb_sticky')) ) {
	wp_enqueue_script('flickity');
}

// In case product is called from product_page shortcode
wp_enqueue_style( 'nectar-woocommerce-single' ); 

wp_enqueue_script('nectar-single-product');

if( isset( $nectar_options['product_reviews_style'] ) && 
    'off_canvas' === $nectar_options['product_reviews_style'] ) {
  wp_enqueue_script('nectar-single-product-reviews');
}

$gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
$gallery_thumbnail_size = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );


if( in_array($product_gallery_style, array('ios_slider','left_thumb_sticky','left_thumb_slider')) ) {

	if( $woocommerce && version_compare( $woocommerce->version, "3.0", ">=" ) ) {
		$product_attach_ids = $product->get_gallery_image_ids();
	} else {
		$product_attach_ids = $product->get_gallery_attachment_ids();
	}

	$has_gallery_imgs = ($product_attach_ids) ? 'true' : 'false';

	$slider_container_class       = 'flickity';
	$slider_thumb_container_class = 'flickity';
	$slider_wrap_class            = 'slider';
	$slider_slide_class           = 'slide';

	$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
		'woocommerce-product-gallery',
		'woocommerce-product-gallery--' . ( has_post_thumbnail() ? 'with-images' : 'without-images' ),
		'images'
	) );
	
	?>
    <div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-has-gallery-imgs="<?php echo esc_attr( $has_gallery_imgs ); ?>">

    	<div class="<?php echo esc_attr($slider_container_class); ?> product-slider">

				<div class="<?php echo esc_attr($slider_wrap_class); ?> generate-markup">

					<?php if (has_post_thumbnail()) {

						$img_link = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );

						$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
						$thumbnail_size    = apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' );
						$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, $thumbnail_size );
						$thumbnail_post    = get_post( $post_thumbnail_id );
						$image_title       = get_post_field( 'post_excerpt', $post_thumbnail_id );

						$attributes = array(
							'title'                   => get_post_field( 'post_title', $post_thumbnail_id ),
							'data-caption'            => get_post_field( 'post_excerpt', $post_thumbnail_id ),
							'data-src'                => $full_size_image[0],
							'data-large_image'        => $full_size_image[0],
							'data-large_image_width'  => $full_size_image[1],
							'data-large_image_height' => $full_size_image[2],
						);

					?>

	        <div class="<?php echo esc_attr($slider_slide_class); ?>">
	        	<?php 
						$main_image_markup = '<div data-thumb="'. get_the_post_thumbnail_url( $post->ID, $gallery_thumbnail_size ) .'" class="woocommerce-product-gallery__image easyzoom">
	          	<a href="' . esc_url( $img_link ). '" class="no-ajaxy">'.get_the_post_thumbnail( $post->ID, 'shop_single', $attributes ) . '</a>
	          </div>';
						echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $main_image_markup, $product->get_image_id() );
						?>
	        </div>

					<?php 
					} 
					else {
						echo '<div class="'.esc_attr($slider_slide_class).'">'.apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src( 'woocommerce_single' ), __( 'Placeholder', 'woocommerce' ) ), $post->ID ) .'</div>';
					}

					if ( $product_attach_ids ) {

						foreach ($product_attach_ids as $product_attach_id) {

							$img_link = wp_get_attachment_url( $product_attach_id );

							if (!$img_link) {
								continue;
							}


								$full_size_image   = wp_get_attachment_image_src( $product_attach_id, 'full' );
								$attributes = array(
									'data-caption'            => get_post_field( 'post_excerpt', $product_attach_id ),
									'data-src'                => $full_size_image[0],
									'data-large_image'        => $full_size_image[0],
									'data-large_image_width'  => $full_size_image[1],
									'data-large_image_height' => $full_size_image[2],
								);

							echo '<div class="'.esc_attr($slider_slide_class).'"><div class="woocommerce-product-gallery__image easyzoom" data-thumb="'. get_the_post_thumbnail_url( $post->ID, $gallery_thumbnail_size ) .'"><a href="'. wp_get_attachment_url($product_attach_id) .'" class="no-ajaxy">';
							echo wp_get_attachment_image($product_attach_id, 'shop_single', false, $attributes);
							echo '</a></div></div>';

						}
					}
				?>

			</div>
		</div>
		
		<?php if ( $product_attach_ids ) {  ?>
	
				<?php if('left_thumb_slider' === $product_gallery_style) {
					$visible_class = (sizeof($product_attach_ids) > 4) ? ' visible' : '';
					echo '<div class="product-thumbs-wrap'. esc_attr($visible_class) .'"><div class="button-prev"><i class="fa fa-angle-up" aria-hidden="true"></i></div>';
				} ?>
	
				<div class="<?php echo esc_attr($slider_thumb_container_class); ?> product-thumbs">
					<div class="<?php echo esc_attr($slider_wrap_class); ?> generate-markup">
							<?php
							if ( has_post_thumbnail() ) { ?>
								<div class="<?php echo esc_attr($slider_slide_class); ?> thumb active"><div class="thumb-inner"><?php echo get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_small_thumbnail_size', $gallery_thumbnail_size ) ) ?></div></div>
							<?php }
	
							foreach ( $product_attach_ids as $product_attach_id) {
	
								$img_link = wp_get_attachment_url($product_attach_id);
	
								if ( !$img_link ) {
									continue;
								}
	
								$img_size    = wp_get_attachment_image($product_attach_id, apply_filters('single_product_small_thumbnail_size', $gallery_thumbnail_size));
								$classes     = array();
								$image_class = esc_attr( implode(' ', $classes));
	
								echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<div class="thumb %s"><div class="thumb-inner">%s</div></div>', $slider_slide_class, $img_size ), $product_attach_id, $post->ID, $image_class );
	
	
							} ?>
				</div>
			</div>
			<?php 
			
			if('left_thumb_slider' === $product_gallery_style) {
				echo '<div class="button-next"><i class="fa fa-angle-down" aria-hidden="true"></i></div></div>';
			} 
	
		}
		?>
	</div>

<?php 
} // End custom bottom thumbnail slider/left thumbnail.

// Two column gallery markup.
else if( 'two_column_images' === $product_gallery_style ) {
	
	global $product;
	
	$post_thumbnail_id  = $product->get_image_id();
	$product_attach_ids = $product->get_gallery_image_ids();
	$has_gallery_imgs   = false;
	
	if ( $product_attach_ids ) {
		foreach ($product_attach_ids as $product_attach_id) {
			$img_link = wp_get_attachment_url( $product_attach_id );
			if (!$img_link) {
				continue;
			}
			$has_gallery_imgs = true;
			break;
		}
	}
	
	$wrapper_classes    = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
		'woocommerce-product-gallery',
		'woocommerce-product-gallery--' . ( has_post_thumbnail() ? 'with-images' : 'without-images' ),
		'images',
		'woocommerce-product-gallery-with-' . ( true === $has_gallery_imgs ? 'multiple-images' : 'single-image')
	) ); ?>
	
	<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>">
		<div class="woocommerce-product-gallery__wrapper">
		<?php 
		// Featured Image.
		if ( has_post_thumbnail() ) {
			$main_image_markup = wc_get_gallery_image_html( $post_thumbnail_id, true );
			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $main_image_markup, $product->get_image_id() );
		} else {
			echo '<div class="woocommerce-product-gallery__image--placeholder">';
			echo sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
			echo '</div>';
		}
		// Gallery Images.
		if ( true === $has_gallery_imgs ) {
			
			foreach ($product_attach_ids as $product_attach_id) {
				
				$img_link = wp_get_attachment_url( $product_attach_id );
				
				if (!$img_link) {
					continue;
				}
				
				$full_size_image   = wp_get_attachment_image_src( $product_attach_id, 'full' );
				$attributes = array(
					'data-caption'            => get_post_field( 'post_excerpt', $product_attach_id ),
					'data-src'                => $full_size_image[0],
					'data-large_image'        => $full_size_image[0],
					'data-large_image_width'  => $full_size_image[1],
					'data-large_image_height' => $full_size_image[2],
				);
				
				echo '<div class="woocommerce-product-gallery__image"><a href="'. wp_get_attachment_url($product_attach_id) .'" class="no-ajaxy">';
				
				$gallery_img_size = 'shop_single';
				if( isset( $nectar_options['single_product_custom_image_dimensions'] ) && 
					'1' === $nectar_options['single_product_custom_image_dimensions'] ) {

					$gallery_img_size = 'woocommerce_single';
				}
	
				echo wp_get_attachment_image($product_attach_id, $gallery_img_size, false, $attributes);
				echo '</a></div>';
				
			}
		}
		?>
	</div>
	</div>
	
	<?php 
} // End two column gallery markup.

// Default WooCommerce gallery functionality.
else { ?>

	<div class="images">

	<?php
	// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
	if( function_exists( 'wc_get_gallery_image_html' ) ) {

		global $product;

		$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
		$post_thumbnail_id = $product->get_image_id();
		$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
			'woocommerce-product-gallery',
			'woocommerce-product-gallery--' . ( has_post_thumbnail() ? 'with-images' : 'without-images' ),
			'woocommerce-product-gallery--columns-' . absint( $columns ),
			'default-woo-gallery',
			'images',
		) );
		?>
		<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
			<div class="woocommerce-product-gallery__wrapper">
				<?php
				if ( has_post_thumbnail() ) {
					$html = wc_get_gallery_image_html( $post_thumbnail_id, true );
				} else {
					$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
					$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
					$html .= '</div>';
				}

				echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id );

				do_action( 'woocommerce_product_thumbnails' );
				?>
			</div>
		</div>


		<?php

	}
	else if( $woocommerce && version_compare( $woocommerce->version, "3.0", ">=" ) ) {

		global $post, $product;
		$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
		$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
		$thumbnail_size    = apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' );
		$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, $thumbnail_size );
		$image_title       = get_post_field( 'post_excerpt', $post_thumbnail_id );
		$placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
		$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
			'woocommerce-product-gallery',
			'woocommerce-product-gallery--' . $placeholder,
			'woocommerce-product-gallery--columns-' . absint( $columns ),
			'images',
		) );
		?>
		<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
			<div class="woocommerce-product-gallery__wrapper">
				<?php
				$attributes = array(
					'title'                   => get_post_field( 'post_title', $post_thumbnail_id ),
					'data-caption'            => get_post_field( 'post_excerpt', $post_thumbnail_id ),
					'data-src'                => $full_size_image[0],
					'data-large_image'        => $full_size_image[0],
					'data-large_image_width'  => $full_size_image[1],
					'data-large_image_height' => $full_size_image[2],
				);

				if ( has_post_thumbnail() ) {
					$html  = '<div data-thumb="' . get_the_post_thumbnail_url( $post->ID, 'shop_thumbnail' ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_size_image[0] ) . '">';
					$html .= get_the_post_thumbnail( $post->ID, 'shop_single', $attributes );
					$html .= '</a></div>';
				} else {
					$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
					$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
					$html .= '</div>';
				}

				echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id( $post->ID ) );

				do_action( 'woocommerce_product_thumbnails' );
				?>
			</div>
		</div>



<?php } ?>

	 </div><!--images-->

 <?php } ?>
