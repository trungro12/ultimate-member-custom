<?php


defined( 'ABSPATH' ) || exit;
$arrBusinessType = umcGetListBusinessType();
$userId = get_current_user_id();
$businessType = (int) sanitize_text_field(get_user_meta($userId, 'business_type', true)) ?: 1;
$businessTypeName = $arrBusinessType[$businessType];

if($businessType === 1) require_once 'PHARMACY.php';
?>
