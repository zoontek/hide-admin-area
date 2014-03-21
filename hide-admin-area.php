<?php
/**
 * Plugin Name:   Hide Admin Area
 * Description:   Let's play with curious visitors.
 * Version:       1.1
 * Author:        Zoontek
 * Author URI:    http://twitter.com/zoontek
 * Text Domain:   haa
 * Domain Path:   /lang
 * 
 * License:       DBAD
 * License URI:   http://www.dbad-license.org/
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
  die;
}

class Hide_Admin_Area {
  /**
   * Instance of this class.
   *
   * @since    1.1
   * @var      object
   */
  protected static $instance = null;

  /**
   * 
   * 
   * @since    1.1
   * @var      string
   */
  protected $admin_area;
  protected $secret_key;

  /**
   * Initialize the plugin by setting localization and loading public scripts and styles.
   *
   * @since    1.1
   */
  private function __construct() {
    $this->admin_area = get_option('haa_admin_area', 'hidden-admin');
    $this->secret_key = get_option('haa_secret_key', 'no_secret_key');

    add_action('login_head', array($this, 'redirect_wp_login'));
    add_action('wp', array($this, 'redirect_with_secret_key'));

    add_filter('admin_init', array($this ,'register_haa_settings'));
  }

  /**
   * Return an instance of this class.
   *
   * @since    1.1
   * @return   object   A single instance of this class.
   */
  public static function get_instance() {
    // If the single instance hasn't been set, set it now.
    if ( null == self::$instance ) {
      self::$instance = new self;
    }

    return self::$instance;
  }

  /**
   * Fired when the plugin is activated.
   *
   * @since    1.1
   */
  public static function activate() {
    $random = substr(str_shuffle(MD5(microtime())), 0, 16);

    add_option('haa_admin_area', 'hidden-admin');
    add_option('haa_secret_key', $random);
  }

  /**
   * Fired when the plugin is deactivated.
   *
   * @since    1.1
   */
  public static function deactivate() {
    delete_option('haa_admin_area');
    delete_option('haa_secret_key');

    unregister_setting('permalink', 'haa_admin_area');
  }

  /**
   * Register settings in admin area.
   *
   * @since    1.1
   */
  public function register_haa_settings() {
    add_settings_section(
      'haa_settings_section',
      'Hide Admin Area',
      '',
      'general'
    );

    add_settings_field( 
      'haa_admin_area',
      '<label for="haa_admin_area">Admin URL slug</label>',
      array($this, 'display_settings_field'),
      'general',
      'haa_settings_section'
    );

    register_setting(
      'general',
      'haa_admin_area',
      'sanitize_key'
    );
  }

  /**
   * Display the admin setting field.
   *
   * @since    1.1
   */
  public function display_settings_field($args) {
    $value = get_option('haa_admin_area', 'hidden-admin');

    echo '<input name="haa_admin_area" type="text" id="haa_admin_area" value="' . $value . '" class="regular-text">';
    echo '<p class="description">Use alpha-numeric characters, dashes and underscores.<br>Avoid existing posts/pages slugs.</p>';
  }

  /**
   * Avoid access to classics login pages without the secret_key
   *
   * @since    1.0
   */
  public function redirect_wp_login() {
    if (is_user_logged_in()) {
      wp_redirect(get_admin_url());
    }
    elseif (!$_GET['secret_key'] == $this->secret_key) {
      wp_redirect(home_url());
    }
  }
  
  /**
   * Redirect the user to the login page with secret key.
   *
   * @since    1.0
   */
  public function redirect_with_secret_key() {
    $page_url = $this->get_current_page_url();
    $admin_page = get_site_url() . '/' . $this->admin_area;

    if (($page_url == $admin_page) || ($page_url == $admin_page . '/')) {
      wp_redirect(home_url() . '/wp-login.php?secret_key=' . $this->secret_key);
    }
  }

  /**
   * Get the current url to compare with the chosen admin url.
   *
   * @since    1.0
   * @return   string   Complete url of the page.
   */
  private function get_current_page_url() {
    $url  = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on') ? 'https://' : 'http://';
    $url .= $_SERVER['SERVER_NAME'];
    $url .= $_SERVER['SERVER_PORT'] == 80 ? $_SERVER['REQUEST_URI'] : ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];

    return $url;
  }
}

register_activation_hook(__FILE__, array('Hide_Admin_Area', 'activate'));
register_deactivation_hook(__FILE__, array('Hide_Admin_Area', 'deactivate'));

add_action('plugins_loaded', array('Hide_Admin_Area', 'get_instance'));
