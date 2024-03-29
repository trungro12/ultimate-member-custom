<?php


// custom myaccount page
class UltimateMemberCustom_Woo_MyAccount
{
    public static function init()
    {
        self::addBusinessInfo();
        self::addBankInfo();
        self::addInvoiceExportInfo();
        self::redirectRegisterPage();
        self::initAJax();
    }

    static function initAJax()
    {
        add_action('wp_ajax_umc_modal_save_invoice_export_info', 'umc_modal_save_invoice_export_info_ajax');
        add_action('wp_ajax_nopriv_umc_modal_save_invoice_export_info', 'umc_modal_save_invoice_export_info_ajax');
        function umc_modal_save_invoice_export_info_ajax()
        {
            $userId = get_current_user_id();

            if (empty($userId)) {
                wp_send_json_error();
                exit;
            }

            $arrInvoiceExportInfo = sanitize_text_field(get_user_meta($userId, 'invoice_export_info', true));
            $arrInvoiceExportInfo = $arrInvoiceExportInfo ? json_decode($arrInvoiceExportInfo, true) : [];

            $id = (!empty($_POST['id'])) ? sanitize_text_field($_POST['id']) : md5(time());
            $isDelete = (!empty($_POST['actionName']) && $_POST['actionName'] === 'delete') ? 1 : 0;
            if ($isDelete) {
                unset($arrInvoiceExportInfo[$id]);
            } else {
                $name = sanitize_text_field($_POST['name']);
                $tax_code = sanitize_text_field($_POST['tax_code']);
                $address = sanitize_text_field($_POST['address']);
                $arrInvoiceExportInfo[$id] = [
                    'name' => $name,
                    'tax_code' => $tax_code,
                    'address' => $address,
                ];
            }
            update_user_meta($userId, 'invoice_export_info', json_encode($arrInvoiceExportInfo, JSON_UNESCAPED_UNICODE));
            wp_send_json_success();
            die();
        }
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

    public static function addInvoiceExportInfo()
    {
        add_action('init', function () {
            add_rewrite_endpoint('invoice-export-info', EP_ROOT | EP_PAGES);
        });

        add_filter('woocommerce_get_query_vars', function ($vars) {
            $vars['invoice-export-info'] = 'invoice-export-info';
            return $vars;
        });

        add_filter('woocommerce_account_menu_items', function ($items) {
            $items['invoice-export-info'] = __('Thông tin xuất hoá đơn', 'woocommerce');
            return $items;
        });

        add_action('woocommerce_account_invoice-export-info_endpoint', function () {
            require_once ULTIMATEMEMBER_CUSTOM__PLUGIN_DIR . '/lib/woo/template/invoice-export-info/index.php';
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

        $arrFileAllow = ULTIMATEMEMBER_CUSTOM__FILETYPE;
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

        $fileName = self::hashName(basename($fileData['name'])) . "." . $fileExt;

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
        return "document_" . md5($str) . "_" . time();
    }
}
