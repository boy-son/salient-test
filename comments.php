<?php
/**
 * @package Salient WordPress Theme
 * @version 15.5
 */

if ( post_password_required() ) {
	return;
}

$nectar_options     = get_nectar_theme_options(); 
$fw_class           = (!empty($nectar_options['theme-skin']) && 'ascend' === $nectar_options['theme-skin']) ? 'full-width-section custom-skip': null; 
$comments_open_attr = (comments_open() || have_comments()) ? 'true' : 'false';

?>

<div class="comment-wrap <?php echo esc_attr($fw_class); ?>" data-midnight="dark" data-comments-open="<?php echo esc_attr($comments_open_attr); ?>">

<?php if ( have_comments() ) : ?>
	<h3 id="comments" class="<?php echo apply_filters('nectar_comments_title_class', 'nectar-comments-title'); ?>"><?php if(!empty($nectar_options['theme-skin']) && $nectar_options['theme-skin'] === 'ascend') echo '<span><i>'. esc_html__("Join the discussion", 'salient').'</i></span>' ?> <?php comments_number(esc_html__('No Comments','salient'), esc_html__('One Comment', 'salient'), esc_html__('% Comments', 'salient') );?></h3>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>

	<ul class="comment-list <?php echo esc_attr($fw_class); ?>">
		<?php wp_list_comments(
			array(
				'avatar_size' => 60,
				'short_ping'  => true
			)
		); ?>
	</ul>

 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ( comments_open() ) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<!--<p class="nocomments">Comments are closed.</p>-->

	<?php endif; ?>
<?php endif; ?>


<?php if ( comments_open() ) : 

$required_text = null;
$form_style    = (!empty($nectar_options['form-style'])) ? $nectar_options['form-style'] : 'default'; 
$comment_label = ($form_style === 'minimal') ? '<label for="comment">' . esc_html__('My comment is..', 'salient') . '</label>' : null;
$consent       = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';

$args = array(
  'id_form'           => 'commentform',
  'id_submit'         => 'submit',
  'title_reply'       => __( 'Leave a Reply', 'salient' ),
  'title_reply_to'    => __( 'Leave a Reply to %s', 'salient' ),
  'cancel_reply_link' => __( 'Cancel Reply', 'salient' ),
  'label_submit'      => __( 'Submit Comment', 'salient' ),

  'comment_field' =>  '<div class="row"><div class="col span_12">'.$comment_label.'<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></div></div>',

  'must_log_in' => '<p class="must-log-in">' .
    sprintf(
      __( 'You must be <a href="%s">logged in</a> to post a comment.', 'salient' ),
      wp_login_url( apply_filters( 'the_permalink', esc_url(get_permalink()) ) )
    ) . '</p>',

  'logged_in_as' => '<p class="logged-in-as">' .
    sprintf(
    __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'salient' ),
      esc_url(admin_url( 'profile.php' )),
      $user_identity,
      wp_logout_url( apply_filters( 'the_permalink', esc_url(get_permalink()) ) )
    ) . '</p>',

  'comment_notes_before' => '',

  'comment_notes_after' => '',
    
  'title_reply_before' => '<h3 id="reply-title" class="'.apply_filters('nectar_comments_title_class', 'comment-reply-title').'">',
  'title_reply_after' => '</h3>',
  'fields' => apply_filters( 'comment_form_default_fields', array(

    'author' =>
      '<div class="row"> <div class="col span_4">' .
      '<label for="author">' . __( 'Name', 'salient' ) .
      ' <span class="required">*</span></label> ' .
      '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
      '" size="30" /></div>',

    'email' =>
      '<div class="col span_4"><label for="email">' . __( 'Email', 'salient' ) .
      ' <span class="required">*</span></label>' .
      '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
      '" size="30" /></div>',

    'url' =>
      '<div class="col span_4 col_last"><label for="url">' .
      __( 'Website', 'salient' ) . '</label>' .
      '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
      '" size="30" /></div></div>',
			
			'cookies' => '<p class="comment-form-cookies-consent"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' />' .
						 '<label for="wp-comment-cookies-consent">' . __( 'Save my name, email, and website in this browser for the next time I comment.', 'salient' ) . '</label></p>'
    )
  ),
);

comment_form($args);

endif; // if you delete this the sky will fall on your head ?>

</div>