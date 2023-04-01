<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://ridwan-arifandi.com
 * @since      1.0.0
 *
 * @package    Sdbal
 * @subpackage Sdbal/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Sdbal
 * @subpackage Sdbal/includes
 * @author     Ridwan Arifandi <orangerdigiart@gmail.com>
 */
class Sdbal
{

  /**
   * The loader that's responsible for maintaining and registering all hooks that power
   * the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      Sdbal_Loader    $loader    Maintains and registers all hooks for the plugin.
   */
  protected $loader;

  /**
   * The unique identifier of this plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $plugin_name    The string used to uniquely identify this plugin.
   */
  protected $plugin_name;

  /**
   * The current version of the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $version    The current version of the plugin.
   */
  protected $version;

  /**
   * Define the core functionality of the plugin.
   *
   * Set the plugin name and the plugin version that can be used throughout the plugin.
   * Load the dependencies, define the locale, and set the hooks for the admin area and
   * the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function __construct()
  {
    if (defined('SDBAL_VERSION')) {
      $this->version = SDBAL_VERSION;
    } else {
      $this->version = '1.0.0';
    }
    $this->plugin_name = 'sdbal';

    $this->load_dependencies();
    $this->set_locale();
    $this->define_admin_hooks();
    $this->define_public_hooks();
  }

  /**
   * Load the required dependencies for this plugin.
   *
   * Include the following files that make up the plugin:
   *
   * - Sdbal_Loader. Orchestrates the hooks of the plugin.
   * - Sdbal_i18n. Defines internationalization functionality.
   * - Sdbal_Admin. Defines all hooks for the admin area.
   * - Sdbal_Public. Defines all hooks for the public side of the site.
   *
   * Create an instance of the loader which will be used to register the hooks
   * with WordPress.
   *
   * @since    1.0.0
   * @access   private
   */
  private function load_dependencies()
  {

    /**
     * The class responsible for orchestrating the actions and filters of the
     * core plugin.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-sdbal-loader.php';

    /**
     * The class responsible for defining internationalization functionality
     * of the plugin.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-sdbal-i18n.php';

    /**
     * The class responsible for defining all actions that occur in the admin area.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-sdbal-admin.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-sdbal-campaign.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-sdbal-user.php';

    /**
     * The class responsible for defining all actions that occur in the public-facing
     * side of the site.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-sdbal-public.php';

    $this->loader = new Sdbal_Loader();
  }

  /**
   * Define the locale for this plugin for internationalization.
   *
   * Uses the Sdbal_i18n class in order to set the domain and to register the hook
   * with WordPress.
   *
   * @since    1.0.0
   * @access   private
   */
  private function set_locale()
  {

    $plugin_i18n = new Sdbal_i18n();

    $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
  }

  /**
   * Register all of the hooks related to the admin area functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_admin_hooks()
  {

    $admin = new SDBAL\Admin($this->get_plugin_name(), $this->get_version());

    $this->loader->add_action('after_setup_theme', $admin, 'load_carbon_fields');

    $campaign = new SDBAL\Admin\Campaign($this->get_plugin_name(), $this->get_version());

    $this->loader->add_action('init',   $campaign, 'register_post_type', 10);
    $this->loader->add_action('carbon_fields_register_fields', $campaign, 'register_fields', 10);
    $this->loader->add_filter('manage_' . SDBAL_CPT_CAMPAIGN . '_posts_columns', $campaign, 'set_columns', 10);
    $this->loader->add_action('manage_' . SDBAL_CPT_CAMPAIGN . '_posts_custom_column', $campaign, 'display_column_values', 10, 2);

    $user = new SDBAL\Admin\User($this->get_plugin_name(), $this->get_version());

    $this->loader->add_action('init',                           $user, 'register_roles', 10);
    $this->loader->add_action('carbon_fields_register_fields',  $user, 'register_fields', 10);
    $this->loader->add_filter('manage_users_columns',           $user, 'set_columns', 10);
    $this->loader->add_filter('manage_users_custom_column',     $user, 'display_column_values', 10, 3);
  }

  /**
   * Register all of the hooks related to the public-facing functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_public_hooks()
  {

    $public = new SDBAL\Front($this->get_plugin_name(), $this->get_version());

    $this->loader->add_action('template_redirect',  $public, 'redirect_to_whatsapp', 10);
  }

  /**
   * Run the loader to execute all of the hooks with WordPress.
   *
   * @since    1.0.0
   */
  public function run()
  {
    $this->loader->run();
  }

  /**
   * The name of the plugin used to uniquely identify it within the context of
   * WordPress and to define internationalization functionality.
   *
   * @since     1.0.0
   * @return    string    The name of the plugin.
   */
  public function get_plugin_name()
  {
    return $this->plugin_name;
  }

  /**
   * The reference to the class that orchestrates the hooks with the plugin.
   *
   * @since     1.0.0
   * @return    Sdbal_Loader    Orchestrates the hooks of the plugin.
   */
  public function get_loader()
  {
    return $this->loader;
  }

  /**
   * Retrieve the version number of the plugin.
   *
   * @since     1.0.0
   * @return    string    The version number of the plugin.
   */
  public function get_version()
  {
    return $this->version;
  }
}
