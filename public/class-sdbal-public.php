<?php

namespace SDBAL;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://ridwan-arifandi.com
 * @since      1.0.0
 *
 * @package    Sdbal
 * @subpackage Sdbal/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Sdbal
 * @subpackage Sdbal/public
 * @author     Ridwan Arifandi <orangerdigiart@gmail.com>
 */
class Front
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
   * @param      string    $plugin_name       The name of the plugin.
   * @param      string    $version    The version of this plugin.
   */
  public function __construct($plugin_name, $version)
  {

    $this->plugin_name = $plugin_name;
    $this->version = $version;
  }

  /**
   * Redirect visitor to whatsapp when visit a campaign link
   * Hooked via action template_redirect, priority 10
   * @author  Ridwan Arifandi
   * @since   1.0.0
   * @return  void
   */
  public function redirect_to_whatsapp()
  {

    if (is_singular(SDBAL_CPT_CAMPAIGN)) :
      global $post;
      $campaign_id = $post->ID;
      $phone_number = carbon_get_post_meta($campaign_id, 'admin_phone');
      $message = carbon_get_post_meta($campaign_id, 'whatsapp_message');

      $whatsapp_url = add_query_arg(array(
        'phone' => $phone_number,
        'text' => rawurlencode($message)
      ), 'https://api.whatsapp.com/send');

      wp_redirect($whatsapp_url);
      exit;
    endif;
  }
}
