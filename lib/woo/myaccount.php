<?php


// custom myaccount page
class UltimateMemberCustom_Woo_MyAccount
{
    public static function init()
    {
        self::addBusinessInfo();
    }

    public static function addBusinessInfo()
    {
        add_action('init', function () {
            add_rewrite_endpoint('business-info', EP_ROOT | EP_PAGES);
        });

        add_filter( 'woocommerce_get_query_vars', function( $vars ) {
            $vars['business-info'] = 'business-info';
            return $vars;
        } );

        // add_filter('query_vars', function () {
        //     $vars[] = 'business-info';
        //     return $vars;
        // });

        add_filter('woocommerce_account_menu_items', function ($items) {
            $items['business-info'] = __('Thông tin doanh nghiệp', 'woocommerce');
            return $items;
        });

        
        add_action('woocommerce_account_business-info_endpoint', function () {
            require_once ULTIMATEMEMBER_CUSTOM__PLUGIN_DIR . '/lib/woo/template/business-info/index.php';
        });
    }
}
