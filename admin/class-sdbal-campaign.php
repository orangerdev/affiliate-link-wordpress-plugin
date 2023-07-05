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
 * @package    Sdbal
 * @subpackage Sdbal/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Sdbal
 * @subpackage Sdbal/admin
 * @author     Ridwan Arifandi <orangerdigiart@gmail.com>
 */
class Campaign
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
   * Register campaign post type
   * Hooked via action init, priority 10
   * @author  Ridwan Arifandi
   * @since   1.0.0
   * @return  void
   */
  public function register_post_type()
  {
    $labels = array(
      'name'                  => _x('Campaigns', 'Post type general name', 'wswp'),
      'singular_name'         => _x('Campaign', 'Post type singular name', 'wswp'),
      'menu_name'             => _x('Campaigns', 'Admin Menu text', 'wswp'),
      'name_admin_bar'        => _x('Campaign', 'Add New on Toolbar', 'wswp'),
      'add_new'               => __('Add New', 'wswp'),
      'add_new_item'          => __('Add New Campaign', 'wswp'),
      'new_item'              => __('New Campaign', 'wswp'),
      'edit_item'             => __('Edit Campaign', 'wswp'),
      'view_item'             => __('View Campaign', 'wswp'),
      'all_items'             => __('All Campaigns', 'wswp'),
      'search_items'          => __('Search Campaigns', 'wswp'),
      'parent_item_colon'     => __('Parent Campaigns:', 'wswp'),
      'not_found'             => __('No campaigns found.', 'wswp'),
      'not_found_in_trash'    => __('No campaigns found in Trash.', 'wswp'),
      'filter_items_list'     => _x('Filter campaigns list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'wswp'),
      'items_list_navigation' => _x('Campaigns list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'wswp'),
      'items_list'            => _x('Campaigns list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'wswp'),
    );

    $args = array(
      'labels'             => $labels,
      'public'             => true,
      'publicly_queryable' => true,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'show_in_nav_menu'   => false,
      'show_in_rest'       => false,
      'exclude_from_search' => true,
      'query_var'          => true,
      'capability_type'    => 'post',
      'has_archive'        => false,
      'hierarchical'       => false,
      'menu_position'      => 7,
      'rewrite'            => array('slug' => 'c'),
      'supports'           => array('title'),
    );

    register_post_type(SDBAL_CPT_CAMPAIGN, $args);
  }

  /**
   * Register custom fields for campaign post type
   * Hooked via action carbon_fields_register_fields, priority 10
   * @author  Ridwan Arifandi
   * @since   1.0.0
   * @return  void
   */
  public function register_fields()
  {
    Container::make('post_meta', __('Campaign Setup'))
      ->where('post_type', '=', SDBAL_CPT_CAMPAIGN)
      ->add_fields([
        Field::make('text', 'admin_phone', __('Default phone number'))
          ->set_required(true)
          ->set_help_text('Make sure to use whatsapp number format, e.g. 6281234567890'),

        Field::make('textarea', 'whatsapp_message', 'WhatsApp Message')
          ->set_help_text('Message that will be sent to WhatsApp')
          ->set_required(true),

        Field::make('text', 'zapier_link', 'Zapier Link')
          ->set_help_text('Zapier link to send data to')
          ->set_attribute('type', 'url'),
      ]);
  }

  /**
   * Modify columns form server post type
   * Hooked via filter manage_SDBAL_CPT_CAMPAIGN_posts_columns, priority 10
   * @since   1.0.0
   * @param   array $columns
   * @return  array
   */
  public function set_columns(array $columns)
  {

    unset($columns['date']);

    $columns['id'] = 'ID';
    $columns['whatsapp_number'] = 'Default Number';

    return $columns;
  }

  /**
   * Display column values
   * Hooked via action manage_SDBAL_CPT_CAMPAIGN_posts_custom_column, priority 10
   * @since   1.0.0
   * @param   string $column
   * @param   int $post_id
   * @return  void
   */
  public function display_column_values(string $column, int $post_id)
  {
    switch ($column):
      case 'id':
        echo $post_id;
        break;

      case 'whatsapp_number':
        $number = carbon_get_post_meta($post_id, 'admin_phone');
        echo $number;
        break;
    endswitch;
  }
}
