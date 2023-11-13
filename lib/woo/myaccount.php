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

        add_filter('woocommerce_get_query_vars', function ($vars) {
            $vars['business-info'] = 'business-info';
            return $vars;
        });

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

    static function uploadFile($fileData)
    {

        $arrFileName = [];
        // format file data 
        if (is_array($fileData['name'])) {
            foreach ($fileData['name'] as $key => $value) {
                $data = [
                    'name' => $fileData['name'][$key],
                    'tmp_name' => $fileData['tmp_name'][$key],
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

        // check file 
        $fileExt = end(explode(".", $fileData['name']));
        if (!in_array($fileExt, ULTIMATEMEMBER_CUSTOM__FILETYPE)) return null;

        $uploads = wp_upload_dir();
        $uploadDir = $uploads['basedir'];
        $uploadUrl = $uploads['baseurl'];
        $userId = (int) get_current_user_id();

        if (empty($userId)) return null;
        $uploadDir .= "/ultimatemembercustom/$userId/";
        $uploadUrl .= "/ultimatemembercustom/$userId/";

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0644, true);
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
