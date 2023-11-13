<?php

class UltimateMemberCustomAdmin_Modal_Info
{
    static function init()
    {
        if (!is_admin()) return;
        add_shortcode('umc_modal_info_admin', 'UltimateMemberCustomAdmin_Modal_Info::__htmlModal');
        self::initAjax();
        self::modifyDefaultModal();
    }

    static function modifyDefaultModal()
    {
        // add_filter( 'um_admin_user_row_actions', function($actions){
        //     // unset($actions['view']);
        //     unset($actions['view_info']);
        //     return $actions;
        // });
        add_action('admin_footer', function () {
?>
            <script>
                (function($) {
                    $(function() {
                        const modalButton = $('a[data-modal="UM_preview_registration"]');
                        modalButton.click(function() {
                            const userId = modalButton.data('arg1');
                            if (typeof userId !== 'undefined' && userId !== '') {
                                setTimeout(() => {
                                    $('#UM_preview_registration .um-admin-infobox .um-row:visible').ready(function() {
                                        $.ajax({
                                            type: "post",
                                            url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
                                            data: {
                                                action: "umc_modal_info_admin", //Tên action
                                                userId: userId,
                                            },
                                            beforeSend: function() {
                                                $('#UM_preview_registration .um-admin-infobox').append('<p style="font-weight:bold;color:red" id="loading">Đang tải thông tin doanh nghiệp...</p>');
                                            },
                                            success: function(response) {
                                                // console.log(response);
                                                const modalAppend = $('#UM_preview_registration .um-admin-infobox');
                                                modalAppend.find('#loading').remove();
                                                modalAppend.append(response);
                                                // if (response.success) {
                                                //     const modalAppend = $('#UM_preview_registration .um-admin-infobox');
                                                //     modalAppend.append(atob(response.data.html));
                                                // } else {
                                                //     alert('Đã có lỗi xảy ra');
                                                // }
                                            },
                                            error: function(jqXHR, textStatus, errorThrown) {
                                                console.log('The following error occured: ' + textStatus, errorThrown);
                                            }
                                        })
                                    });
                                }, 500);
                            }
                        });
                    });
                })(jQuery);
            </script>
        <?php
        });
    }


    static function initAjax()
    {
        add_action('wp_ajax_umc_modal_info_admin', 'umc_modal_info_admin_ajax');
        add_action('wp_ajax_nopriv_umc_modal_info_admin', 'umc_modal_info_admin_ajax');
        function umc_modal_info_admin_ajax()
        {
            $userId = (isset($_POST['userId'])) ? esc_attr($_POST['userId']) : '';
            $html = do_shortcode("[umc_modal_info_admin userid='$userId']");
            echo $html;
            die();
        }
    }



    // function shortcode
    static function __htmlModal($attrs)
    {
        // $attrs = shortcode_atts( ['userid' => ''], $attrs );
        $userId = 1;
        $arrBusinessType = umcGetListBusinessType();
        $businessType = (int) sanitize_text_field(get_user_meta($userId, 'business_type', true)) ?: 1;
        $businessTypeName = $arrBusinessType[$businessType];
        ?>
        <div class="um-row _um_row_last " style="margin: 0 0 30px 0;">
            <p><b style="font-weight: bold;color: red;font-size: 15px;">Thông tin doanh nghiệp</b></p>
            <p><label><?php esc_html_e('Bạn là: '); ?></label><span><?php echo $businessTypeName; ?></span></p>
            <?php
            if (in_array($businessType, umcGetListBusinessTypeId1())) {
                // template 1
                $arrBusinessDocumentType = umcGetListDocumentType();
                $document_type = esc_attr(sanitize_text_field(get_user_meta($userId, 'document_type', true)));
                $document_type_file = esc_attr(sanitize_text_field(get_user_meta($userId, 'document_type_file', true)));

            ?>
                <p><label><?php esc_html_e('Loại giấy tờ: ') ?></label><span><?php echo $arrBusinessDocumentType[$document_type]; ?></span></p>
                <?php if (!empty($arrFile = json_decode(base64_decode($document_type_file)))) : ?>
                    <?php foreach ($arrFile as $file) : ?>
                        <a style="color:red" target="_blank" href="<?php echo $file; ?>"><?php echo basename($file); ?></a><br>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php
            } else {
                // template 2
                $business_name = esc_attr(sanitize_text_field(get_user_meta($userId, 'business_name', true)));
                $business_user_name = esc_attr(sanitize_text_field(get_user_meta($userId, 'business_user_name', true)));
                $arrBusinessDocumentType = umcGetListDocumentType2();
            ?>
                <p><label><?php esc_html_e('Tên nhà thuốc/phòng khám: ') ?></label><span><?php echo $business_name; ?></span></p>
                <p><label><?php esc_html_e('Tên người đại diện pháp luật: ') ?></label><span><?php echo $business_user_name; ?></span></p>

                <?php foreach ($arrBusinessDocumentType as $id => $documentTypeName) : ?>
                    <?php
                    $name = 'document_type_' . $id . "__";
                    $license_number = esc_attr(sanitize_text_field(get_user_meta($userId, $name . 'license_number', true)));
                    $license_date = (int) esc_attr(sanitize_text_field(get_user_meta($userId, $name . 'license_date', true)));
                    $license_date = $license_date ? date('d-m-Y', $license_date) : '';
                    $license_city_code = (int) esc_attr(sanitize_text_field(get_user_meta($userId, $name . 'license_city_code', true)));
                    $city = UltimateMemberCustom::getCity($license_city_code);
                    ?>
                    <p><b style="font-weight: bold;color: red;font-size: 15px;"><?php echo $documentTypeName; ?></b></p>
                    <p><label><?php esc_html_e('Số giấy phép: ') ?></label><span><?php echo $license_number; ?></span></p>
                    <p><label><?php esc_html_e('Ngày cấp: ') ?></label><span><?php echo $license_date; ?></span></p>
                    <p><label><?php esc_html_e('Nơi cấp: ') ?></label><span><?php echo $city->full_name; ?></span></p>

                <?php endforeach; ?>

            <?php
            }
            $tax_code = esc_attr(sanitize_text_field(get_user_meta($userId, 'tax_code', true)));
            $city_code = esc_attr(sanitize_text_field(get_user_meta($userId, 'city_code', true)));
            $city = UltimateMemberCustom::getCity($city_code);

            $district_code = esc_attr(sanitize_text_field(get_user_meta($userId, 'district_code', true)));
            $district = UltimateMemberCustom::getDistrict($district_code);

            $ward_code = esc_attr(sanitize_text_field(get_user_meta($userId, 'ward_code', true)));
            $ward = UltimateMemberCustom::getWard($ward_code);

            $billing_address_1 = esc_attr(sanitize_text_field(get_user_meta($userId, 'billing_address_1', true)));
            ?>

            <p><b style="font-weight: bold;color: red;font-size: 15px;">Thông tin địa chỉ nhà thuốc</b></p>

            <p><label><?php esc_html_e('Mã số thuế: ') ?></label><span><?php echo $tax_code; ?></span></p>
            <p><label><?php esc_html_e('Tỉnh/Thành Phố: ') ?></label><span><?php echo $city->full_name; ?></span></p>
            <p><label><?php esc_html_e('Quận/Huyện: ') ?></label><span><?php echo $district->full_name; ?></span></p>
            <p><label><?php esc_html_e('Phường/Xã: ') ?></label><span><?php echo $ward->full_name; ?></span></p>
            <p><label><?php esc_html_e('Số nhà - tên đường: ') ?></label><span><?php echo $billing_address_1; ?></span></p>

        </div>
<?php
    }
}
