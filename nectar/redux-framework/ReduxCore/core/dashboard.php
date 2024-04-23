<?php

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    
    if (!class_exists('reduxDashboardWidget')) {
        class reduxDashboardWidget {
            
            public function __construct ($parent) {
                $fname = Redux_Functions::dat( 'add_redux_dashboard', $parent->args['opt_name'] );

                add_action('wp_dashboard_setup', array($this, $fname));
            }
            
            public function add_redux_dashboard() {
                /* nectar addition - remove metabox */
                /* nectar addition end */
            }
            
            public function dat() {
                return;
            }
            
            public function redux_dashboard_widget() {
                echo '<div class="rss-widget">';
                wp_widget_rss_output(array(
                     'url'          => 'http://reduxframework.com/feed/',
                     'title'        => 'REDUX_NEWS',
                     'items'        => 3,
                     'show_summary' => 1,
                     'show_author'  => 0,
                     'show_date'    => 1
                ));
                echo '</div>';
            }
        }
    }
