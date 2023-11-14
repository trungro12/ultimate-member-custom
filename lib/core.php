<?php
class UltimateMemberCustom
{
    public static function init()
    {
        require_once(ULTIMATEMEMBER_CUSTOM__PLUGIN_DIR . '/lib/define.php');



        self::provinceAjaxInit();



        // register page
        require_once(ULTIMATEMEMBER_CUSTOM__PLUGIN_DIR . '/lib/register.php');
        UltimateMemberCustom_Register::init();

        // woo
        require_once(ULTIMATEMEMBER_CUSTOM__PLUGIN_DIR . '/lib/woo/myaccount.php');
        UltimateMemberCustom_Woo_MyAccount::init();


        require_once(ULTIMATEMEMBER_CUSTOM__PLUGIN_DIR . '/lib/woo/coupons.php');
        UltimateMemberCustom_Coupons::init();
        self::checkUserIsVerifiedBeforeCheckout();

        if (!is_admin()) return;
        UltimateMemberCustom_Coupons::init_admin();

        require_once(ULTIMATEMEMBER_CUSTOM__PLUGIN_DIR . '/lib/admin/users.php');
        UltimateMemberCustom_Admin_Users::init();

        // init modal info user 
        require_once(ULTIMATEMEMBER_CUSTOM__PLUGIN_DIR . '/lib/admin/template/modal/info.php');
        UltimateMemberCustomAdmin_Modal_Info::init();
    }

    public static function checkUserIsVerifiedBeforeCheckout()
    {
        // add_action('woocommerce_before_cart', 'customer_is_verify_displaying_message');
        // function customer_is_verify_displaying_message()
        // {
        //     $userId = get_current_user_id();
        //     $isVerify = (int) get_user_meta($userId, 'is_verified', true);
        //     if ($isVerify < 1) {
        //         $message = __('Tài khoản của bạn chưa được xác minh nên không thể mua hàng');
        //         wc_add_notice($message, 'error');
        //     }
        // }


        add_action('template_redirect', 'customer_is_verify_redirect');
        function customer_is_verify_redirect()
        {
            $userId = get_current_user_id();
            $isVerify = (int) get_user_meta($userId, 'is_verified', true);
            if (is_checkout() && $isVerify < 1) {
                $message = __('Tài khoản của bạn chưa được xác minh nên không thể mua hàng');
                wc_add_notice($message, 'error');
                wp_safe_redirect(esc_url(wc_get_cart_url()));
                exit;
            }
        }
    }


    static function pluginActivation()
    {
        self::migrateDB();
    }
    static function pluginDeactivation()
    {
    }

    static function runSQL($sqlFile, $isDrop = false)
    {
        // WP_Filesystem();
        // global $wp_filesystem;
        // global $wpdb;
        // if(!is_readable($sqlFile)) {
        //     return 'File not found or not readable '.$sqlFile;
        // }
        // $sql = $wp_filesystem->get_contents( $sqlFile );
        $sql = file_get_contents($sqlFile);
        $data = [];
        if ($isDrop) {
            global $wpdb;
            if (!empty($sql)) {
                foreach (explode(";", $sql) as $q) {
                    $q = trim($q);
                    if (empty($q)) continue;
                    $data[] = $wpdb->query($q . ";");
                }
            }
            return $data;
        }
        // $rowsAffected = $wpdb->query( $sql );
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        return dbDelta($sql);
    }

    static function migrateDB()
    {
        $sqlFile = (ULTIMATEMEMBER_CUSTOM__PLUGIN_DIR . '/database/vietnamese-provinces/drop.sql');
        $result[] = self::runSQL($sqlFile, true);

        // insert database 
        $sqlFile = (ULTIMATEMEMBER_CUSTOM__PLUGIN_DIR . '/database/vietnamese-provinces/create.sql');
        $result[] = self::runSQL($sqlFile);

        $sqlFile = (ULTIMATEMEMBER_CUSTOM__PLUGIN_DIR . '/database/vietnamese-provinces/insert.sql');
        $result[] = self::runSQL($sqlFile);
    }




    // vietnam province
    static function provinceAjaxInit()
    {
        add_action('wp_ajax_districtList', 'districtListAjax');
        add_action('wp_ajax_nopriv_districtList', 'districtListAjax');
        function districtListAjax()
        {
            $cityId = (isset($_POST['cityId'])) ? esc_attr($_POST['cityId']) : '';
            $arrCityList = UltimateMemberCustom::getDistrictList($cityId);
            wp_send_json_success($arrCityList);
            die();
        }


        add_action('wp_ajax_wardList', 'wardListAjax');
        add_action('wp_ajax_nopriv_wardList', 'wardListAjax');
        function wardListAjax()
        {
            $districtId = (isset($_POST['districtId'])) ? esc_attr($_POST['districtId']) : '';
            $arrDistrictList = UltimateMemberCustom::getWardList($districtId);
            wp_send_json_success($arrDistrictList);
            die();
        }
    }

    static function getCity($code){
        global $wpdb;
        $table = 'provinces';
        $query = $wpdb->prepare("SELECT * FROM $table WHERE code='$code';");
        $results = $wpdb->get_row( $query );
        return $results;
    }

    static function getCityList(){
        global $wpdb;
        $table = 'provinces';
        $query = $wpdb->prepare("SELECT * FROM $table;");
        $results = $wpdb->get_results( $query );
        return $results;
    }

    static function getDistrictList($cityId){
        global $wpdb;
        $table = 'districts';
        $query = $wpdb->prepare("SELECT * FROM $table WHERE province_code=%s;", $cityId );
        $results = $wpdb->get_results( $query );
        return $results;
    }
    static function getDistrict($code){
        global $wpdb;
        $table = 'districts';
        $query = $wpdb->prepare("SELECT * FROM $table WHERE code=%s;", $code );
        $results = $wpdb->get_row( $query );
        return $results;
    }

    static function getWardList($districtId){
        global $wpdb;
        $table = 'wards';
        $query = $wpdb->prepare("SELECT * FROM $table WHERE district_code=%s;", $districtId );
        $results = $wpdb->get_results( $query );
        return $results;
    }

    static function getWard($code){
        global $wpdb;
        $table = 'wards';
        $query = $wpdb->prepare("SELECT * FROM $table WHERE code=%s;", $code );
        $results = $wpdb->get_row( $query );
        return $results;
    }
    
}
