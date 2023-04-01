<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://ridwan-arifandi.com
 * @since             1.0.0
 * @package           Sdbal
 *
 * @wordpress-plugin
 * Plugin Name:       SDB - Affiliate Link Plugin
 * Plugin URI:        https://ridwan-arifandi.com
 * Description:       Generate affiliate links. Integrated with WPForm
 * Version:           1.0.0
 * Author:            Ridwan Arifandi
 * Author URI:        https://ridwan-arifandi.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sdbal
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
  die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('SDBAL_VERSION', '1.0.0');
define('SDBAL_ROLE_AGENT', 'agent');
define('SDBAL_CPT_CAMPAIGN', 'sdbal-campaign');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sdbal-activator.php
 */
function activate_sdbal()
{
  require_once plugin_dir_path(__FILE__) . 'includes/class-sdbal-activator.php';
  Sdbal_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sdbal-deactivator.php
 */
function deactivate_sdbal()
{
  require_once plugin_dir_path(__FILE__) . 'includes/class-sdbal-deactivator.php';
  Sdbal_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_sdbal');
register_deactivation_hook(__FILE__, 'deactivate_sdbal');

require plugin_dir_path(__FILE__) . 'vendor/autoload.php';

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-sdbal.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sdbal()
{

  $plugin = new Sdbal();
  $plugin->run();
}
run_sdbal();
