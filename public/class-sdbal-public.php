<?php

namespace SDBAL;

use WP_User_Query;

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
   * The cookie key to store affiliate id
   * @since   1.0.0
   * @var     string
   * @access  protected
   */
  protected $cookie_key = 'sdbal_affiliate';

  /**
   * Initialize the class and set its properties.
   *
   * @since   1.0.0
   * @param   string    $plugin_name       The name of the plugin.
   * @param   string    $version    The version of this plugin.
   */
  public function __construct($plugin_name, $version)
  {

    $this->plugin_name = $plugin_name;
    $this->version = $version;
  }

  /**
   * Set affiliate id to cookie
   * @since   1.0.0
   * @access  protected
   * @param   int   $affiliate_id
   * @return  void
   */
  protected function set_cookie(int $affiliate_id)
  {
    setcookie($this->cookie_key, $affiliate_id, 0, COOKIEPATH, COOKIE_DOMAIN);
  }

  /**
   * Check if affiliate cookie exists
   * @since   1.0.0
   * @access  protected
   * @return  false | WP_User
   */
  protected function check_cookie()
  {
    if (isset($_COOKIE[$this->cookie_key])) :

      $query = new WP_User_Query(array(
        'number' => 1,
        'include' => array($_COOKIE[$this->cookie_key]),
        'count_total' => false,
      ));

      $users = $query->get_results();

      if (count($users) > 0) :
        return $users[0];
      endif;
    endif;

    return false;
  }

  /**
   * Check if user visits with affiliate param
   * Hooked via action template_redirect, priority 1
   * @author  Ridwan Arifandi
   * @since   1.0.0
   * @return  void
   */
  public function check_affiliate()
  {
    if (
      isset($_GET['ref']) &&
      !empty($_GET['ref'])
    ) :
      $query = new WP_User_Query(array(
        'number' => 1,
        'meta_key' => '_affiliate',
        'meta_value' => $_GET['ref'],
        'count_total' => false,
        'fields' => 'ID'
      ));

      $users = $query->get_results();

      if (count($users) > 0) :
        $affiliate_id = (int) $users[0];
        $this->set_cookie($affiliate_id);
      endif;

    endif;
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

      $campaign_id  = $post->ID;
      $phone_number = carbon_get_post_meta($campaign_id, 'admin_phone');
      $message      = carbon_get_post_meta($campaign_id, 'whatsapp_message');
      $affiliate    = $this->check_cookie();

      if (is_a($affiliate, 'WP_User')) :
        $phone_number = carbon_get_user_meta($affiliate->ID, 'phone_number');
      endif;

      $whatsapp_url = add_query_arg(array(
        'phone' => $phone_number,
        'text' => rawurlencode($message)
      ), 'https://api.whatsapp.com/send');

      wp_redirect($whatsapp_url);
      exit;
    endif;
  }
}
