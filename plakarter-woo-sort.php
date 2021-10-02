<?php 

defined('ABSPATH') or die('dont direct access please');

/*
    Plugin Name: Plakarter-woo-sort
    Plugin URI: http://www.hannan.me/
    Description: this plugin is for woocomerce custom sorting
    Author: Hannan
    Author URI: http://www.hannan.me/
    Version: 1.0

*/

class PlakarterWooSort{

    public  static function custom_woocommerce_get_catalog_ordering_args($args){
        $orderby_value = isset($_GET['orderby']) ?
        wc_clean($_GET['orderby']) :
        apply_filters(
            'woocommerce_default_catalog_orderby',
            get_option('woocommerce_default_catalog_orderby')
        );

        if ('reverse_list' == $orderby_value) {
            $args['orderby'] = 'title';
            $args['order'] = 'desc';
        } else if ('alpha_list' == $orderby_value) {
            $args['orderby'] = 'title';
            $args['order'] = 'asc';
        }

       return $args;
    }

    public static function custom_woocommerce_catalog_orderby($sortby)
    {
        $sortby = [
            'menu_order' => __('Default', 'woocommerce'),
            'alpha_list' => __('A - Z', 'woocommerce'),
            'reverse_list' => __('Z - A', 'woocommerce'),
            'popularity' => __('Popularity', 'woocommerce'),
            'rating'     => __('Average rating', 'woocommerce'),
            'date'       => __('Latest', 'woocommerce'),
            'price'      => __('Price: low to high', 'woocommerce'),
            'price-desc' => __('Price: high to low', 'woocommerce'),
        ];

        return $sortby;
    }


    public static function custom_products_per_page($per_page)
    {

        $count = (int) get_query_var('show', 16);

        switch ($count) {
            case 4:
            case 8:
            case 16:
            case 32:
            case -1:
                $per_page = $count;
                break;
            default:
                $per_page = 16;
                break;
        }

        return $per_page;
    }

    public static function template_products_per_page()
    {
        wc_get_template('products-per-page.php', array(), '', plugin_dir_path(__FILE__) . 'templates/');
    }

    public static function add_query_vars_products_per_page($vars)
    {
        $vars[] = 'show';

        return $vars;
    }

    public static function add_assets()
    {
        wp_enqueue_style('vg-sort-css', plugin_dir_url(__FILE__) .  'assets/css/main.css');
    }




}


// add filters 

add_filter('woocommerce_get_catalog_ordering_args', array('PlakarterWooSort', 'custom_woocommerce_get_catalog_ordering_args'));
add_filter('woocommerce_default_catalog_orderby_options', array('PlakarterWooSort', 'custom_woocommerce_catalog_orderby'));
add_filter('woocommerce_catalog_orderby', array('PlakarterWooSort', 'custom_woocommerce_catalog_orderby'));
add_filter('loop_shop_per_page', array('PlakarterWooSort', 'custom_products_per_page'));
add_action('woocommerce_before_shop_loop', array('PlakarterWooSort', 'template_products_per_page'), 30);
add_filter('query_vars', array('PlakarterWooSort', 'add_query_vars_products_per_page'));
add_action('wp_enqueue_scripts', array('PlakarterWooSort', 'add_assets'));