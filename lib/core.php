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
        self::showUserUploadFileField();
        self::showUserVerifyField();
        UltimateMemberCustom_Coupons::init_admin();

        // init modal info user 
        require_once(ULTIMATEMEMBER_CUSTOM__PLUGIN_DIR . '/lib/admin/template/modal/info.php');
        UltimateMemberCustomAdmin_Modal_Info::init();
    }

    public static function checkUserIsVerifiedBeforeCheckout()
    {
        add_action('woocommerce_before_cart', 'customer_is_verify_displaying_message');
        function customer_is_verify_displaying_message()
        {
            $userId = get_current_user_id();
            $isVerify = (int) get_user_meta($userId, 'is_verified', true);
            if ($isVerify < 1) {
                $message = __('Tài khoản của bạn chưa được kích hoạt nên không thể mua hàng');
                wc_add_notice($message, 'error');
            }
        }


        add_action('template_redirect', 'customer_is_verify_redirect');
        function customer_is_verify_redirect()
        {
            $userId = get_current_user_id();
            $isVerify = (int) get_user_meta($userId, 'is_verified', true);
            if (is_checkout() && $isVerify < 1) {
                wp_safe_redirect(esc_url(wc_get_cart_url()));
                exit;
            }
        }
    }


    public static function showUserUploadFileField()
    {
        add_action('show_user_profile', '__showUserUploadFileField', 1);
        add_action('edit_user_profile', '__showUserUploadFileField', 1);

        function __showUserUploadFileField($user)
        {
            $title = 'File Upload, hỗ trợ các file có đuôi ' . '<b style="color:red">' . implode(", ", ULTIMATEMEMBER_CUSTOM__FILETYPE) . '</b>';
?>
            <h3><?php _e($title); ?></h3>
            <?php
            $userId = $user->ID;
            $uploads = wp_upload_dir();
            $baseDir = str_replace("\\", "/", $uploads['basedir'] . "/ultimatemembercustom/$userId");
            $baseUrl = str_replace("\\", "/", $uploads['baseurl'] . "/ultimatemembercustom/$userId");

            $files = glob($baseDir . "/*");
            wp_enqueue_script('jquery');
            wp_enqueue_media();
            wp_enqueue_script('media-grid');
            wp_enqueue_script('media');
            ?>
            <table class="form-table">
                <div class="wp-core-ui">
                    <div class="attachments-wrapper">
                        <ul tabindex="-1" class="attachments ui-sortable ui-sortable-disabled" id="__attachments-view-47">


                            <?php foreach ($files as $index => $file) : ?>
                                <?php
                                $id = $index + 1;
                                $file = str_replace("\\", "/", $file);
                                $fileName = end(explode("/", $file));
                                $fileExt = strtolower(trim(end(explode(".", $fileName))));

                                if (!in_array($fileExt, ULTIMATEMEMBER_CUSTOM__FILETYPE)) continue;

                                $fileUrl = esc_url(str_replace($baseDir, $baseUrl, $file));

                                ?>
                                <div class="block-item">

                                    <li data-id="<?php echo $id; ?>" data-url="<?php echo $fileUrl; ?>" tabindex="0" role="checkbox" aria-checked="false" class="attachment file-prevew">
                                        <iframe src="" frameborder="0" class="iframeShow"></iframe>
                                        <div class="attachment-preview js--select-attachment type-application subtype-pdf landscape">
                                            <div class="thumbnail">
                                                <div class="centered">
                                                    <iframe style="height: 100%;" src="<?php echo $fileUrl; ?>" class="preview icon image" frameborder="0"></iframe>
                                                </div>
                                                <div class="filename">
                                                    <div><?php echo $fileName; ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </div>

                            <?php endforeach; ?>



                        </ul>
                    </div>
                </div>

                </tr>
            </table>
            <input class="button button-primary" type="button" value="Close" id="close-preview">
            <style>
                .image {
                    transform: translate(-50%, -50%);
                    position: absolute;
                    top: 0;
                    left: 0;
                    max-height: 100%;
                    border: none;
                }

                .hide {
                    display: none !important;
                }

                .iframeShow {
                    display: none;
                }

                .iframeShow.live {
                    display: block;
                    position: fixed;
                    top: 1%;
                    bottom: 5%;
                    height: 85%;
                    left: 0;
                    right: 0;
                    margin: auto;
                    width: 85%;
                    z-index: 9999;
                }

                #close-preview {
                    display: none;
                }

                #close-preview.live {
                    display: block;
                    position: fixed;
                    top: 78%;
                    bottom: 15%;
                    height: 10%;
                    left: 0;
                    right: 0;
                    margin: auto;
                    width: 7%;
                    z-index: 9999;
                    font-weight: bold;
                    font-size: 15px;
                }
            </style>
            <script>
                (function($) {

                    $('.file-prevew').click(function() {
                        // const fileUrl = $(this).data('url');
                        // window.open(fileUrl, '_blank');
                        const iframe = $(this).find('iframe.preview');
                        const iframeShow = $(this).find('.iframeShow');
                        if (typeof iframeShow.attr('src') === 'undefined' || iframeShow.attr('src') === '') {
                            iframeShow.attr('src', iframe.attr('src'));
                        }
                        iframeShow.addClass('live');

                        const btnClose = $('#close-preview');
                        btnClose.data('id', $(this).data('id'));
                        btnClose.addClass('live');
                    });

                    $("#close-preview").click(function(e) {
                        const btnClose = $('#close-preview');
                        const id = btnClose.data('id');
                        btnClose.removeClass('live');
                        $('.file-prevew[data-id="' + id + '"]').find('.iframeShow').removeClass('live');
                    });

                })(jQuery);
            </script>

        <?php
        }
    }

    public static function showUserVerifyField()
    {
        add_action('show_user_profile', '__showUserVerifyField', 1);
        add_action('edit_user_profile', '__showUserVerifyField', 1);

        add_action('show_user_profile_update', '__updateUserVerifyField', 10);
        add_action('profile_update', '__updateUserVerifyField', 10);
        add_action('edit_user_profile_update', '__updateUserVerifyField', 10);
        function __updateUserVerifyField($user_id)
        {
            if (isset($_POST['is_verified'])) {
                $isVerify = (int) $_POST['is_verified'];
                update_user_meta($user_id, 'is_verified', $isVerify);
            }
        }

        function __showUserVerifyField()
        {
            $userId = !empty($_GET['user_id']) ? (int) $_GET['user_id'] : get_current_user_id();
            $isVerify = (int) get_user_meta($userId, 'is_verified', true);
        ?>
            <h3><?php _e('Tình trạng'); ?></h3>
            <tr class="user-display-name-wrap">
                <th>
                    <label for="is_verify">Tình trạng xác minh</label>
                </th>
                <td>
                    <select name="is_verified" id="is_verified">
                        <option value="0" <?php echo $isVerify === 0 ? 'selected' : '' ?>>Chưa xác minh</option>
                        <option value="1" <?php echo $isVerify === 1 ? 'selected' : '' ?>>Đã xác minh</option>
                    </select>
                </td>
            </tr>
<?php
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
