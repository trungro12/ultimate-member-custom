<?php

// custom register form
class UltimateMemberCustom_Register
{

    public static function init()
    {
        add_shortcode('umc_business_type', 'UltimateMemberCustom_Register::__htmlBusinessType');
        self::__addBusinessTypeUserMetaSubmit();
        self::redirectUserAfterRegisterandLogin();
    }


    public static function __htmlBusinessType()
    {
        $arrBusinessType = umcGetListBusinessType();
?>
        <div id="business_type" class="um-field um-field-radio  um-field-business_type um-field-radio um-field-type_radio" aria-invalid="false">
            <div class="um-field-label"><label>Tên loại hình</label>
                <div class="um-clear"></div>
            </div>
            <div class="um-field-area">
                <?php foreach ($arrBusinessType as $id => $name) : ?>
                    <label class="um-field-radio  um-field-half <?php echo $id == 1 ? 'active' : '' ?>"><input type="radio" name="business_type" value="<?php echo $id; ?>"><span class="um-field-radio-state"><i class="um-icon-android-radio-button-<?php echo $id == 1 ? 'on' : 'off' ?>"></i></span><span class="um-field-radio-option"><?php echo $name; ?></span></label>
                <?php endforeach; ?>

            </div>
        </div>
        <script>
            (function($) {
                $(function() {
                    $("#business_type").appendTo(".um-row._um_row_1 .um-col-1");
                });
            })(jQuery)
        </script>
<?php
    }

    public static function __addBusinessTypeUserMetaSubmit()
    {
        add_action("user_register", "umc_business_type_add_metadata", 1, 1);
        function umc_business_type_add_metadata($userId)
        {
            if (empty($userId)) return;
            $business_type = empty($_POST['business_type']) ? UMC_BUSINESS_TYPE_PHARMACY : sanitize_text_field($_POST['business_type']);
            update_user_meta($userId, 'business_type', $business_type);
        }
    }

    static function redirectUserAfterRegisterandLogin()
    {
        if (is_user_logged_in()) return;

        add_action('user_register', '__redirectUserRegisterToBusinessInfo');
        function __redirectUserRegisterToBusinessInfo($user_id)
        {
            wp_safe_redirect(esc_url(wc_get_account_endpoint_url('business-info')));
            die;
        }
    }
}
