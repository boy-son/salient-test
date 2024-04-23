<?php
/**
* Nectar Quick Search
*
*
* @package Salient WordPress Theme
* @version 13.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
* Nectar Quick Search.
*/
if ( !class_exists( 'NectarQuickSearch' ) ) {

    class NectarQuickSearch {

        private static $instance;
        public static $version    = '1.0';
        public static $post_type  = 'any';
        public static $style      = 'classic';
        public static $ajax_style = 'default';

        private function __construct() {

            $this->setup_style();
            $this->hooks();

        }

        /**
        * Initiator.
        */
        public static function get_instance() {
            if ( !self::$instance ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        /**
        * Item Style.
        */

        public function setup_style() {

            global $nectar_options;

            $post_types_list = array( 'post', 'product', 'portfolio' );

            if ( isset( $nectar_options[ 'header-search-limit' ] ) && in_array( $nectar_options[ 'header-search-limit' ], $post_types_list ) ) {
                self::$post_type = esc_attr( $nectar_options[ 'header-search-limit' ] );
            }

            // Store actual item style.
            // So far, only the product will use the actual style.
            if ( 'product' === self::$post_type ) {

                $product_styles = array( 'classic', 'text_on_hover', 'material', 'minimal' );
                $product_style = ( isset( $nectar_options[ 'product_style' ] ) ) ? $nectar_options[ 'product_style' ] : 'classic';

                if ( in_array( $product_style, $product_styles ) ) {
                    self::$style = esc_html( $product_style );
                }

            }

            // AJAX style.
            if ( isset( $nectar_options[ 'header-ajax-search-style' ] ) ) {
                $ajax_styles = array( 'default', 'extended' );
                if ( in_array( $nectar_options[ 'header-ajax-search-style' ], $ajax_styles ) ) {
                    self::$ajax_style = $nectar_options[ 'header-ajax-search-style' ];
                }

            }

        }

        /**
        * Action hooks.
        */

        public function hooks() {

            add_action( 'wp_ajax_nectar_ajax_ext_search_results', array( $this, 'get_results' ) );
            add_action( 'wp_ajax_nopriv_nectar_ajax_ext_search_results', array( $this, 'get_results' ) );

        }

        /**
        * AJAX callback to load results.
        */

        public function get_results() {

            if ( !isset( $_POST[ 'search' ] ) ) {
                wp_die();
            }

            $search_term = sanitize_text_field( $_POST[ 'search' ] );
            $search_term = apply_filters( 'get_search_query', $search_term );

            $content = '';

            // Set up query
            $nectar_search_query_args = array(
                'posts_per_page' => 6,
                'post_status'    => 'publish',
                'ignore_sticky_posts' => true,
                'no_found_rows'  => true,
                'has_password'   => false,
                's'              => $search_term,
                'post_type'      => self::$post_type
            );

			if( 'product' === self::$post_type ) {
				$nectar_search_query_args['tax_query'] = array(
					array(
						'taxonomy' => 'product_visibility',
						'field'    => 'name',
						'terms'    => 'exclude-from-catalog',
						'operator' => 'NOT IN',
					),
				);
			}

			// Allow user filtering.
            $nectar_search_query_args = apply_filters( 'nectar_quick_search_query', $nectar_search_query_args );

            $search_query = new WP_Query( $nectar_search_query_args );

            if ( $search_query->have_posts() ) : while( $search_query->have_posts() ) : $search_query->the_post();

            global $post;

            if ( is_callable( array( $this, self::$post_type . '_markup' ) ) ) {
                $content .= call_user_func( array( $this, self::$post_type . '_markup' ), self::$style, $post );
            }

            endwhile;
            endif;

            // Finalize Markup.
            if ( !empty( $content ) ) {

                $content_start = '';
                $content_end   = '';

                if ( 'product' === self::$post_type && 'extended' === self::$ajax_style ) {
                    $content_start = '<div class="woocommerce"><ul class="products columns-4" data-rm-m-hover="on" data-n-desktop-columns="5" data-n-desktop-small-columns="5" data-n-tablet-columns="2" data-n-phone-columns="2" data-product-style="classic">';
                    $content_end   = '</ul></div>';
                } else {
                    $content_start = '<div class="nectar-search-results">';
                    $content_end   = '</div>';
                }

                wp_send_json( array(
                    'content' => $content_start . $content . $content_end
                ) );

            }

            wp_die();

        }

        /**
        * Product Markup when using extended display.
        */

        public function extended_product_markup( $style, $post ) {

            global $product;

            ob_start();
            ?>

            <li <?php wc_product_class( $style, $product );
            ?> >

            <?php

            do_action( 'woocommerce_before_shop_loop_item' );
            do_action( 'woocommerce_before_shop_loop_item_title' );

            if ( 'classic' === $style ) {
                do_action( 'woocommerce_shop_loop_item_title' );
                do_action( 'woocommerce_after_shop_loop_item_title' );
            }

            do_action( 'woocommerce_after_shop_loop_item' );

            $content = ob_get_clean();

            $content .= '</li>';

            return $content;

        }

        /**
        * Simple Markup.
        */

        public function simple_markup( $post, $post_type = 'post' ) {

            // Skip posts with no name.
            if ( empty( get_the_title() ) ) {
                return;
            }

            ob_start();
            ?>
            <div class = 'search-post-item'>
            <?php

            $cat_markup = '';
            $categories = '';

            // Link start.
            echo '<a href="'.esc_url( get_permalink() ). '">';

            // Categories.
            if ( 'any' !== $post_type ) {
                if ( 'portfolio' === $post_type ) {
                    $categories = get_the_terms( $post->id, 'project-type' );
                } else if ( 'product' !== $post_type ) {
                    $categories = get_the_category();
                }

                if ( ! empty( $categories ) ) {
                    foreach ( $categories as $category ) {
                        $cat_markup .= esc_html( $category->name );
                        break;
                    }
                    $cat_markup = trim( $cat_markup );
                }
            }

            // Featured Image.
            $featured_image_size = ( 'extended' === self::$ajax_style ) ? 'large' : 'medium';

            if ( 'portfolio' === $post_type ) {
                $custom_thumbnail = get_post_meta( $post->ID, '_nectar_portfolio_custom_thumbnail', true );

                if ( !empty( $custom_thumbnail ) ) {
                    echo '<span class="post-featured-img" style="background-image: url(' . nectar_ssl_check( esc_url( $custom_thumbnail ) ) .');"></span>';
                } else if ( has_post_thumbnail() ) {
                    echo '<span class="post-featured-img" style="background-image: url(' . get_the_post_thumbnail_url( $post->ID, $featured_image_size, array( 'title' => '' ) ) . ');"></span>';
                } else {
                    echo '<span class="post-featured-img"></span>';
                }

            } else {
                if ( has_post_thumbnail() ) {
                    echo '<span class="post-featured-img" style="background-image: url(' . get_the_post_thumbnail_url( $post->ID, $featured_image_size, array( 'title' => '' ) ) . ');"></span>';
                } else {
                    echo '<span class="post-featured-img"></span>';
                }
            }

            ?>
            <div class = 'header'>
            <?php if ( !empty( $cat_markup ) ) {
                echo '<span class="meta meta-category">'. $cat_markup . '</span>';
            }
            ?>
            <h5 class = 'title'><?php echo get_the_title();
            ?></h5>
            <?php if ( 'product' === $post_type ) {
                echo '<span class="meta meta-price">';
                woocommerce_template_loop_price();
                echo '</span>';
            }

            // Post type label.
            if ( 'any' === $post_type && isset( $post->post_type ) ) {
                $pt_obj = get_post_type_object( $post->post_type );
                if ( $pt_obj && isset( $pt_obj->labels->singular_name ) ) {
                    echo '<span class="meta meta-type">'.esc_html( $pt_obj->labels->singular_name ).'</span>';
                }
            }
            ?>
            </div>
            </a>
            </div>
            <?php
            $content = ob_get_clean();
            return $content;

        }

        /**
        * Limited to post types.
        */

        public function portfolio_markup( $style, $post ) {
            return $this->simple_markup( $post, 'portfolio' );
        }

        public function post_markup( $style, $post ) {
            return $this->simple_markup( $post, 'post' );
        }

        public function any_markup( $style, $post ) {
            return $this->simple_markup( $post, 'any' );
        }

        public function product_markup( $style, $post ) {

            if ( 'extended' === self::$ajax_style ) {
                return $this->extended_product_markup( $style, $post );
            }

            return $this->simple_markup( $post, 'product' );

        }

    }

    /**
    * Initialize the NectarElAssets class
    */
    NectarQuickSearch::get_instance();

}
