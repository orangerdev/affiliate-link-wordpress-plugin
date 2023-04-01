<?php

namespace SDBAL\Admin;

use Carbon_Fields\Container;
use Carbon_Fields\Field;


/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://ridwan-arifandi.com
 * @since      1.0.0
 *
 * @package    Wswp
 * @subpackage Wswp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wswp
 * @subpackage Wswp/admin
 * @author     Ridwan Arifandi <orangerdigiart@gmail.com>
 */
class User
{

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param      string    $plugin_name       The name of this plugin.
   * @param      string    $version    The version of this plugin.
   */
  public function __construct($plugin_name, $version)
  {
    $this->plugin_name = $plugin_name;
    $this->version = $version;
  }

  /**
   * Register user roles for
   * Hooked via action init, priority 1
   * @since   1.0.0
   * @return  void
   */
  public function register_roles()
  {
    global $wp_roles;

    if (!isset($wp_roles)) :
      $wp_roles = new \WP_Roles();
    endif;


    /**
     * Create Agent role
     */

    $agent = $wp_roles->get_role('subscriber');

    $wp_roles->add_role(SDBAL_ROLE_AGENT, 'Agent', $agent->capabilities);

    $wp_roles->add_rolw(SDBAL_ROLE_AGENT, 'manage_own_affiliate_link');
  }

  /**
   * Register custom fields for user
   * Hooked via action carbon_fields_register_fields, priority 10
   * @author  Ridwan Arifandi
   * @since   1.0.0
   * @return  void  
   */
  public function register_fields()
  {
    Container::make('user_meta', __('Agent Detail', 'sdbal'))
      ->where('user_role', '=', SDBAL_ROLE_AGENT)
      ->add_fields([
        Field::make('text', 'affiliate', 'Affiliate ID')
          ->set_required('true')
          ->set_help_text('Make sure the affiliate ID is unique'),
        Field::make('text', 'phone_number', 'Whatsapp Number')
          ->set_required(true)
          ->set_help_text('Make sure to use whatsapp number format, e.g. 6281234567890'),
      ]);
  }

  /**
   * Modify user columns in admin page
   * Hooked via filter manage_users_columns, priority 10
   * @since   1.0.0
   * @param   array $columns
   * @return  array
   */
  public function set_columns(array $columns)
  {
    unset($columns['posts'], $columns['email']);

    $position = 2;

    $columns = array_slice($columns, 0, $position, true) +
      array(
        'contact' => __('Contact', 'sdbal'),
        'affiliate' => __('Affiliate', 'sdbal'),
      ) +
      array_slice($columns, $position, count($columns) - 1, true);

    return $columns;
  }

  /**
   * Display user column values
   * Hooked via action manage_users_custom_column, priority 10
   * @since   1.0.0
   * @param   string $value
   * @param   string $column_name
   * @param   integer $user_id
   * @return  string
   */
  public function display_column_values($value, $column_name, $user_id)
  {
    $user = get_userdata($user_id);

    switch ($column_name):

      case 'contact':
        $phone = carbon_get_user_meta($user_id, 'phone_number');
        $email = $user->user_email;

        $value = $phone . '<br />' . $email;
        break;

      case 'affiliate':
        $value = carbon_get_user_meta($user_id, 'affiliate');
        break;

    endswitch;

    return $value;
  }
}
