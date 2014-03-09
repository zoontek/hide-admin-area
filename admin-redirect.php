<?php
/**
 * Plugin Name: Admin Redirect
 * Description: Let's play with curious visitors.
 * Version:     1.0
 * Author:      Zoontek
 * Author URI:  http://twitter.com/zoontek
 * License:     DBAD
 * License URI: http://www.dbad-license.org/
 */

define('ADMIN_PAGE', 'this-is-an-exemple');
define('SECRET_KEY', 'q6wuChuSEbr7spa7');

function redirect_wp_login() {
  if (is_user_logged_in()) {
    wp_redirect(get_admin_url());
  }
  elseif (!$_GET['secret_key'] == SECRET_KEY) {
    wp_redirect(home_url());
  }
}
add_action('login_head', 'redirect_wp_login');

function redirect_with_secret_key() {
  $page_url = get_current_page_url();
  $admin_page = get_site_url() . '/' . ADMIN_PAGE;

  if (($page_url == $admin_page) || ($page_url == $admin_page . '/')) {
    wp_redirect(home_url() . '/wp-login.php?secret_key=' . SECRET_KEY);
  }
}
add_action('wp', 'redirect_with_secret_key');

function get_current_page_url() {
  $url  = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on') ? $url = 'https://' : $url = 'http://';
  $url .= $_SERVER['SERVER_NAME'];
  $url .= ($_SERVER['SERVER_PORT'] == 80) ? $_SERVER['REQUEST_URI'] : ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];

  return $url;
}
