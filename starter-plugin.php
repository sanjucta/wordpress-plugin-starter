<?php

/**
  * Plugin Name: Starter Plugin
  * Plugin URI: http://brandsoftonline.com/
  * Description: Starter template for a wordpress plugin.
  * Author: Brandsoft
  * Author URI: http://brandsoftonline.com/
  * Text Domain: starter-plugin
  * Version: 1.0.0
  */

if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'StarterPlugin' ) )
{

    class StarterPlugin
    {
        private $settings;

        public function __construct()
        {
            $this->settings = array(
                'starter_plugin_path' => plugin_dir_path( __FILE__ ),
                'starter_plugin_url'  => plugin_dir_url( __FILE__ ),
                'starter_plugin_base' => dirname( plugin_basename( __FILE__ ) ),
                'starter_plugin_file' => __FILE__,
            );

            add_action( 'plugins_loaded', array( $this, 'loadTextdomain' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueuePluginScripts' ) );

            $this->includeClasses();

            $this->dbHelper = new DBHelper();

            register_activation_hook( __FILE__, array( $this, 'pluginActivated' ) );
            register_deactivation_hook( __FILE__, array( $this, 'pluginDeactivated' ) );

            
        }

        private function includeClasses()
        {
            require_once( 'includes/classes/class-db-helper.php' );
            
        }

        
        public function loadTextdomain()
        {
           
            error_log("Loading Text Domain");
            load_plugin_textdomain( 'starter-plugin', false, $this->settings[ 'starter_plugin_base' ] . '/languages' );
        }

        
        public function enqueuePluginScripts()
        {
            wp_enqueue_style( 'starter-plugin-style', plugins_url( 'css/starter-plugin.css', $this->settings[ 'starter_plugin_file' ] ), array(), '1.0.0' );

            wp_enqueue_script( 'jquery' );
           
            wp_enqueue_script( 'starter-plugin-js', plugins_url( 'js/starter-plugin.js', $this->settings[ 'starter_plugin_file' ] ), array( 'jquery' ), '1.0.0' );

            wp_localize_script( 'starter-plugin-js', 'starterAjax', array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'siteUrl' => site_url(),
                'ajax_nonce' => wp_create_nonce( "starter_ajax_nonce" ),
            ) );
        }


        //called when the plugin is activated.Perform tasks like adding custom tables needed 
        //by the plugin
        public function PluginActivated()
        {
            error_log("Plugin Activated");
            $this->dbHelper->addCustomTable();
        }

        //called when the plugin is deactivated.Perform tasks like dropping custom tables needed 
        public function PluginDeactivated()
        {
            error_log("Plugin DeActivated");
            $this->dbHelper->removeCustomTable();
        }
    }

}
$starter_plugin_instance = new StarterPlugin;


