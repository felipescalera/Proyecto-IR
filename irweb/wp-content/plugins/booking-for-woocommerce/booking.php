<?php

/*
 * Plugin Name: MVV WooCommerce Booking Addon (Free)
 * Version: 4.0.1
 * Plugin URI:
 * Description:Booking and Reservation Addon for WooCommerce
 * Author URI:http://mvvapps.com
 * Author:mvvapps
 * Requires at least: 4.0
 * Tested up to: 5.5.3
 * WC requires at least: 3.3.0
 * WC tested up to: 4.6.2
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'mvvwb_fs' ) ) {
    mvvwb_fs()->set_basename( false, __FILE__ );
} else {
    // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
    
    if ( !function_exists( 'mvvwb_fs' ) ) {
        function mvvwb_fs()
        {
            global  $mvvwb_fs ;
            
            if ( !isset( $mvvwb_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $mvvwb_fs = fs_dynamic_init( array(
                    'id'             => '4871',
                    'slug'           => 'booking-for-woocommerce',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_75a0160a689a4f6c76f519b6cdced',
                    'is_premium'     => false,
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'menu'           => array(
                    'slug' => 'mvvwb_admin_ui',
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $mvvwb_fs;
        }
        
        // Init Freemius.
        mvvwb_fs();
        // Signal that SDK was initiated.
        do_action( 'mvvwb_fs_loaded' );
    }
    
    // ... Your plugin's main file logic ...
    define( 'MVVWB_SETTINGS_KEY', 'mvvwb_settings_key' );
    define( 'MVVWB_TOKEN', 'mvvwb' );
    define( 'MVVWB_ITEMS_PT', 'mvvwb_items' );
    define( 'MVVWB_BOOKING_PT', 'mvvwb_bookings' );
    define( 'MVVWB_RESOURCE_PT', 'mvvwb_resource' );
    define( 'MVVWB_RESOURCE_TAX', 'mvvwb_resource_tax' );
    define( 'MVVWB_CART_ITEM_KEY', 'mvvwb_cart_item_key' );
    define( 'MVVWB_ORDER_ITEM_KEY', '_mvvwb_order_item_key' );
    define( 'MVVWB_ORDER_BOOKING_ID_KEY', '_mvvwb_booking' );
    define( 'MVVWB_TRANSIENT_KEY', 'mvvwb_transient_key' );
    define( 'MVVWB_TEMPLATE_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/templates/' );
    define( 'MVVWB_TEMPLATE_PATH_THEME', 'mvv_booking/' );
    define( 'MVVWB_VERSION', '4.0.1' );
    define( 'MVVWB_FILE', __FILE__ );
    define( 'MVVWB_PLUGIN_NAME', 'MVV Booking For WooCommerce' );
    define( 'MVVWB_TEXT_DOMAIN', 'booking-for-woocommerce' );
    require_once realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'include/functions.php';
    if ( !function_exists( 'mvvwb_init' ) ) {
        function mvvwb_init()
        {
            $plugin_rel_path = basename( dirname( __FILE__ ) ) . '/languages';
            /* Relative to WP_PLUGIN_DIR */
            load_plugin_textdomain( 'mvvwb', false, $plugin_rel_path );
        }
    
    }
    if ( !function_exists( 'mvvwb_autoloader' ) ) {
        function mvvwb_autoloader( $class_name )
        {
            
            if ( 0 === strpos( $class_name, 'MVVWB_Email' ) ) {
                $classes_dir = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'emails' . DIRECTORY_SEPARATOR;
                $class_file = 'class-' . str_replace( '_', '-', strtolower( $class_name ) ) . '.php';
                require_once $classes_dir . $class_file;
            } else {
                
                if ( 0 === strpos( $class_name, 'MVVWB' ) ) {
                    $classes_dir = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR;
                    $class_file = 'class-' . str_replace( '_', '-', strtolower( $class_name ) ) . '.php';
                    require_once $classes_dir . $class_file;
                }
            
            }
        
        }
    
    }
    if ( !function_exists( 'MVVWB' ) ) {
        function MVVWB()
        {
            $instance = MVVWB_Backend::instance( __FILE__, MVVWB_VERSION );
            return $instance;
        }
    
    }
    add_action( 'plugins_loaded', 'mvvwb_init' );
    spl_autoload_register( 'mvvwb_autoloader' );
    if ( is_admin() ) {
        MVVWB();
    }
    new MVVWB_Api();
    new MVVWB_Front_End( __FILE__, MVVWB_VERSION );
}
