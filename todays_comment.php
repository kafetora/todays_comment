<?php
/*
Plugin Name: Todays Comment
Description: 今日のひとことを設定・表示するプラグイン
Author: Omipan
Version: 1.0
*/


add_action( 'admin_menu', function () {
  add_menu_page(
    '今日のひとこと',
    '今日のひとこと',
    'edit_posts',
    'todays_comment',
    'show_admin_todays_comment',
    'dashicons-smiley',
    22
  );
});


function show_admin_todays_comment(){

  $html = '';
  $html .= '<div class="wrap">';
  $html .= '<h1 class="wp-heading-inline">今日のひとこと設定</h1>';
  $html .= '<form action="options.php" method="POST">';

  ob_start();
  settings_fields( 'group_todays_comment' );
  do_settings_sections( 'todays_comment' );
  submit_button();
  $html .= ob_get_contents();
  ob_end_clean();

  $html .= '</form>';
  $html .= '</div>';

  print $html;
}


function _init_setting_api_todays_comment(){

  add_settings_section(
    'setting_section_todays_comment',
    '',
    'show_setting_section_text_todays_comment',
    'todays_comment'
  );

  add_settings_field(
    'todays_comment_text',
    '今日のひとこと',
    'show_input_todays_comment',
    'todays_comment',
    'setting_section_todays_comment'
  );

  register_setting( 'group_todays_comment', 'todays_comment_text' );
}

add_action( 'admin_init', '_init_setting_api_todays_comment' );


function show_input_todays_comment(){

  $html = '';
  $html .= sprintf(
    '<textarea id="todays-comment-text" class="regular-text" name="todays_comment_text" rows="3">%s</textarea>',
    get_option( 'todays_comment_text', '' )
  );
  $html .= '<div><input class="btn-reset button button-secondary" type="reset" value="メッセージを削除"></div>';

  print $html;
}


function show_setting_section_text_todays_comment(){
  $html .= '';
  $html .= '<p>今日のひとことを設定しましょう！</p>';
  $html .= '<p>ひとことが入力されている場合のみ、表示されます。</p>';

  print $html;
}

function get_todays_comment_area(){
  $sText = '';
  $sText = get_option( 'todays_comment_text', '' );

  if ( $sText == false ) {
    return;
  }

  $html = '';
  $html .= '<section class="todays-comment">';
  $html .= '<span class="todays-comment-title">今日のひとこと</span>';
  $html .= '<p class="todays-comment-text">' . nl2br($sText) . '</p>';
  $html .= '</section>';

  return $html;
}


add_shortcode( 'show_todays_comment', 'get_todays_comment_area' );

add_action( 'admin_enqueue_scripts', function ( $hook_suffix ) {

  if ( $hook_suffix == 'toplevel_page_todays_comment' ) {
    wp_enqueue_script(
      'todays-comment-admin',
      plugins_url( '/assets/js/todays-comment-admin.js', __FILE__ ),
      array( 'jquery' ),
      array(),
      true
    );
  }
});

add_action( 'wp_enqueue_scripts', function () {

  wp_enqueue_style(
    'today-comment-style',
    plugins_url( '/assets/css/style.css', __FILE__ )
  );
});
