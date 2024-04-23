<?php
    /**
     * The template for the panel footer area.
     * Override this template by specifying the path where it is stored (templates_path) in your Redux config.
     *
     * @author        Redux Framework
     * @package       ReduxFramework/Templates
     * @version:      3.5.8.3
     */
?>
<div id="redux-sticky-padder" style="display: none;">&nbsp;</div>
<div id="redux-footer-sticky">
    <div id="redux-footer">
<?php 
        if ( isset( $this->parent->args['share_icons'] )) { 

            $skip_icons = false;
            if (!$this->parent->args['dev_mode'] && $this->parent->omit_share_icons ) {
                $skip_icons = true;
            }
?>
            <div id="redux-share">
                <?php 
                // nectar addition 
                $ext_links = array(
                  array(
                    'text' => esc_html__('Facebook','salient'),
                    'link' => esc_url('http://www.facebook.com/ThemeNectar-488077244574702/')
                  ),
                  array(
                    'text' => esc_html__('Instagram','salient'),
                    'link' => esc_url('https://www.instagram.com/theme_nectar/')
                  ),
                  array(
                    'text' => esc_html__('Support Forum','salient'),
                    'link' => esc_url('https://themenectar.ticksy.com/')
                  ),
                  array(
                    'text' => esc_html__('Changelog','salient'),
                    'link' => esc_url('http://themenectar.com/changelogs/salient.html')
                  ),
                  array(
                    'text' => esc_html__('Documentation','salient'),
                    'link' => esc_url('http://themenectar.com/docs/salient')
                  ),
                );
                foreach($ext_links as $k => $data) {
                  echo '<a class="salient-options-link" href="'.esc_attr($data['link']).'" rel="noreferrer" target="_blank">'.esc_html($data['text']).'</a>';
                }
                // nectar addition end
                ?>
            </div>
        <?php } ?>

        <div class="redux-action_bar">
            <span class="spinner"></span>
<?php 
            if ( false === $this->parent->args['hide_save'] ) {
                submit_button( __( 'Save Changes', 'redux-framework' ), 'primary', 'redux_save', false );
            }

            if ( false === $this->parent->args['hide_reset'] ) {
                submit_button( __( 'Reset Section', 'redux-framework' ), 'secondary', $this->parent->args['opt_name'] . '[defaults-section]', false, array( 'id' => 'redux-defaults-section' ) );
                submit_button( __( 'Reset All', 'redux-framework' ), 'secondary', $this->parent->args['opt_name'] . '[defaults]', false, array( 'id' => 'redux-defaults' ) );
            } 
?>
        </div>

        <div class="redux-ajax-loading" alt="<?php _e( 'Working...', 'redux-framework' ) ?>">&nbsp;</div>
        <div class="clear"></div>

    </div>
</div>
