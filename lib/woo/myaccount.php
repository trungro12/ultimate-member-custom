<?php


// custom myaccount page
class UltimateMemberCustom_Woo_MyAccount
{
    public static function init()
    {
        self::addBusinessInfo();
        self::addBankInfo();
        self::redirectRegisterPage();
    }

    static function redirectRegisterPage()
    {
        add_action('woocommerce_before_customer_login_form', function () {
            if (!empty($_GET['action']) && $_GET['action'] === 'register' && !is_user_logged_in()) {
                wp_safe_redirect(home_url('/register'));
                exit;
            }
        });
    }

    public static function addBusinessInfo()
    {
        add_action('init', function () {
            add_rewrite_endpoint('business-info', EP_ROOT | EP_PAGES);
        });

        add_filter('woocommerce_get_query_vars', function ($vars) {
            $vars['business-info'] = 'business-info';
            return $vars;
        });

        // add_filter('query_vars', function () {
        //     $vars[] = 'business-info';
        //     return $vars;
        // });

        $userId = get_current_user_id();
        $businessType = (int) sanitize_text_field(get_user_meta($userId, 'business_type', true)) ?: 1;
        if ($businessType !== UMC_BUSINESS_TYPE_PATIENT) {
            add_filter('woocommerce_account_menu_items', function ($items) {
                $items['business-info'] = __('Thông tin doanh nghiệp', 'woocommerce');
                return $items;
            });
        }


        add_action('woocommerce_account_business-info_endpoint', function () {
            require_once ULTIMATEMEMBER_CUSTOM__PLUGIN_DIR . '/lib/woo/template/business-info/index.php';
        });
    }

    public static function addBankInfo()
    {
        add_action('init', function () {
            add_rewrite_endpoint('bank-info', EP_ROOT | EP_PAGES);
        });

        add_filter('woocommerce_get_query_vars', function ($vars) {
            $vars['bank-info'] = 'bank-info';
            return $vars;
        });

        add_filter('woocommerce_account_menu_items', function ($items) {
            $items['bank-info'] = __('Tài khoản ngân hàng', 'woocommerce');
            return $items;
        });

        add_action('woocommerce_account_bank-info_endpoint', function () {
            require_once ULTIMATEMEMBER_CUSTOM__PLUGIN_DIR . '/lib/woo/template/bank-info/index.php';
        });
    }

    static function uploadFile($fileData)
    {

        $arrFileName = [];
        // format file data 
        if (is_array($fileData['name'])) {
            foreach ($fileData['name'] as $key => $value) {
                $name = $fileData['name'][$key];
                $tmpName = $fileData['tmp_name'][$key];

                $data = [
                    'name' => $name,
                    'tmp_name' => $tmpName,
                ];

                $fileName = self::__uploadFile($data);
                if ($fileName) $arrFileName[] = $fileName;
            }
            return $arrFileName;
        }

        $fileName = self::__uploadFile($fileData);

        return $fileName ? [$fileName] : [];
    }


    static function __uploadFile($fileData)
    {
        if (empty($fileData['name']) || empty($fileData['tmp_name'])) return null;

        $arrFileAllow = array_merge(ULTIMATEMEMBER_CUSTOM__FILETYPE, ULTIMATEMEMBER_CUSTOM__FILETYPE_IMAGE);
        // check file 
        $fileExt = strtolower(end(explode(".", $fileData['name'])));
        if (!in_array($fileExt, $arrFileAllow)) return null;

        $uploads = wp_upload_dir();
        $uploadDir = $uploads['basedir'];
        $uploadUrl = $uploads['baseurl'];
        $userId = (int) get_current_user_id();

        if (empty($userId)) return null;
        $uploadDir .= "/ultimatemembercustom/$userId/";
        $uploadUrl .= "/ultimatemembercustom/$userId/";

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = self::hashName(basename($fileData['name']));

        $uploadFile = $uploadDir . $fileName;
        $fileUrl = $uploadUrl . $fileName;
        if (@move_uploaded_file($fileData['tmp_name'], $uploadFile)) {
            return $fileUrl;
        } else {
            return '';
        }
    }


    static function hashName($str)
    {
        return "document_" . md5($str . rand(1, 100)) . "_" . $str;
    }
}
