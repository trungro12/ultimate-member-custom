<?php
// NHÀ THUỐC
// $arrBusinessType = umcGetListBusinessType();
// $userId = get_current_user_id();
// $businessType = (int) sanitize_text_field(get_user_meta($userId, 'business_type', true)) ?: 1;
// $businessTypeName = $arrBusinessType[$businessType];
$arrBusinessDocumentType = umcGetListDocumentType();
$arrCity = UltimateMemberCustom::getCityList();


$document_type = esc_attr(sanitize_text_field(get_user_meta($userId, 'document_type', true)));
$document_type_file = esc_attr(sanitize_text_field(get_user_meta($userId, 'document_type_file', true)));

$tax_code = esc_attr(sanitize_text_field(get_user_meta($userId, 'tax_code', true)));
$city_code = esc_attr(sanitize_text_field(get_user_meta($userId, 'city_code', true)));
$district_code = esc_attr(sanitize_text_field(get_user_meta($userId, 'district_code', true)));
$ward_code = esc_attr(sanitize_text_field(get_user_meta($userId, 'ward_code', true)));

$billing_address_1 = esc_attr(sanitize_text_field(get_user_meta($userId, 'billing_address_1', true)));

?>

<form action="" method="post" enctype="multipart/form-data">

	<p class="woocommerce-form-row">
		<label><?php esc_html_e('Bạn là', 'woocommerce'); ?></label>
		<input disabled type="text" class="woocommerce-Input woocommerce-Input--text input-text" value="<?php echo esc_attr($businessTypeName); ?>" />
	</p>

	<fieldset>
		<!-- <legend></legend> -->
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label><?php esc_html_e('Bấm vào để chọn loại giấy tờ', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
			<select name="document_type" id="">
				<?php foreach ($arrBusinessDocumentType as $id => $documentType) : ?>
					<option <?php echo $document_type == $id ? 'selected' : ''; ?> value="<?php echo $id; ?>"><?php echo $documentType; ?></option>
				<?php endforeach; ?>
			</select>
		</p>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<?php
			$arrFile = json_decode(base64_decode($document_type_file), true);
			?>
			<?php if (empty($document_type_file) || empty($arrFile)) : ?>
				<input multiple required type="file" class="woocommerce-Input woocommerce-Input--password input-text" name="document_type_file" id="">
			<?php else : ?>
				<?php foreach ($arrFile as $file) : ?>
					<a style="color:red" target="_blank" href="<?php echo $file; ?>"><?php echo basename($file); ?></a><br>
				<?php endforeach; ?>
			<?php endif; ?>
		</p>
	</fieldset>


	<fieldset>
		<legend><?php esc_html_e('Nhập thông tin địa chỉ nhà thuốc', 'woocommerce'); ?></legend>
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label><?php esc_html_e('Mã số thuế', 'woocommerce'); ?></label>
			<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="tax_code" value="<?php echo $tax_code; ?>" />
		</p>
		<div class="clear"></div>



		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label><?php esc_html_e('Tỉnh/Thành Phố', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
			<select required name="city_code" id="city_code">
				<option value="">Chọn Tỉnh/Thành Phố</option>
				<?php foreach ($arrCity as $city) : ?>
					<option <?php echo $city_code === $city->code ? 'selected' : ''; ?> value="<?php echo $city->code; ?>"><?php echo $city->full_name; ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label><?php esc_html_e('Quận/Huyện', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
			<select required name="district_code" id="district_code">
				<option value="">Chọn Quận/Huyện</option>
			</select>
		</p>
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label><?php esc_html_e('Phường/Xã', 'woocommerce'); ?></label>
			<select name="ward_code" id="ward_code">
				<option value="">Chọn Phường/Xã</option>
			</select>
		</p>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label><?php esc_html_e('Số nhà - tên đường', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
			<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_address_1" value="<?php echo $billing_address_1; ?>" />
		</p>



	</fieldset>










	<div class="clear"></div>


	<!-- 
	<fieldset>
		<legend><?php esc_html_e('Password change', 'woocommerce'); ?></legend>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="password_current"><?php esc_html_e('Current password (leave blank to leave unchanged)', 'woocommerce'); ?></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_current" id="password_current" autocomplete="off" />
		</p>
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="password_1"><?php esc_html_e('New password (leave blank to leave unchanged)', 'woocommerce'); ?></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" autocomplete="off" />
		</p>
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="password_2"><?php esc_html_e('Confirm new password', 'woocommerce'); ?></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" autocomplete="off" />
		</p>
	</fieldset> -->

	<div class="clear"></div>

	<p>
		<?php wp_nonce_field('save_business_info'); ?>
		<button type="submit" class="woocommerce-Button button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" name="save_business_info" value="<?php esc_attr_e('Save changes', 'woocommerce'); ?>"><?php esc_html_e('Save changes', 'woocommerce'); ?></button>
		<input type="hidden" name="action" value="save_business_info" />
	</p>


</form>