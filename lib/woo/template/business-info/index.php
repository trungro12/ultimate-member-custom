<?php


defined('ABSPATH') || exit;
$arrBusinessType = umcGetListBusinessType();
$userId = get_current_user_id();
$businessType = (int) sanitize_text_field(get_user_meta($userId, 'business_type', true)) ?: 1;
$businessTypeName = $arrBusinessType[$businessType];

if (!empty($_POST['action']) && !empty($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], $_POST['action'])) {
    unset($_POST['action']);
    unset($_POST['_wpnonce']);
    unset($_POST['_wp_http_referer']);
    $_POST  += $_FILES;
    // var_dump($_POST);die;
    foreach ($_POST as $key => $value) {
        $value = sanitize_text_field($value);

        if (strpos($key, "_file") !== false) {
            if (!is_array($_FILES[$key])) $_FILES[$key] = [$_FILES[$key]];
            $arrFileName = [];
            foreach ($_FILES[$key] as $fileData) {
                $fileName = UltimateMemberCustom_Woo_MyAccount::uploadFile($fileData);
                if ($fileName) $arrFileName[] = $fileName;
            }
            if (!empty($arrFileName))
                update_user_meta($userId, $key, serialize($arrFileName));
            continue;
        }

        update_user_meta($userId, $key, $value);
    }
}

if ($businessType === UMC_BUSINESS_TYPE_PHARMACY) require_once 'PHARMACY.php';
if ($businessType === UMC_BUSINESS_TYPE_DRUGSTORE) require_once 'PHARMACY.php';
if ($businessType === UMC_BUSINESS_TYPE_PHARMA_COMPANY) require_once 'PHARMACY.php';


if ($businessType === UMC_BUSINESS_TYPE_CLINIC) require_once 'CLINIC.php';
if ($businessType === UMC_BUSINESS_TYPE_HOSPITAL) require_once 'CLINIC.php';
if ($businessType === UMC_BUSINESS_TYPE_HEALTH_CENTER) require_once 'CLINIC.php';
if ($businessType === UMC_BUSINESS_TYPE_DENTISTRY) require_once 'CLINIC.php';
if ($businessType === UMC_BUSINESS_TYPE_BEAUTY_SALON) require_once 'CLINIC.php';

?>


<script src="<?php echo ULTIMATEMEMBER_CUSTOM__PLUGIN_URL . "lib/assets/js/provinces.js"; ?>"></script>
<script>
    const adminAjaxUrl = '<?php echo esc_url(admin_url('admin-ajax.php')); ?>';

    const districtCode = '<?php echo empty($district_code) ? '' : $district_code; ?>';
    const wardCode = '<?php echo empty($ward_code) ? '' : $ward_code; ?>';

    Provinces.districtCodeSelected = districtCode;
    Provinces.wardCodeSelected = wardCode;
    Provinces.districtList();
    Provinces.wardList();
    (function($) {
        $(function() {
            setTimeout(() => {
                $('#city_code').trigger('change');
            }, 500);
        })
    })(jQuery);
</script>