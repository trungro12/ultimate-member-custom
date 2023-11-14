<?php
// NHÀ THUỐC
// $arrBusinessType = umcGetListBusinessType();
// $userId = get_current_user_id();
// $businessType = (int) sanitize_text_field(get_user_meta($userId, 'business_type', true)) ?: 1;
// $businessTypeName = $arrBusinessType[$businessType];
$arrBusinessDocumentType = umcGetListDocumentType2();
$arrCity = UltimateMemberCustom::getCityList();

$business_name = esc_attr(sanitize_text_field(get_user_meta($userId, 'business_name', true)));
$business_user_name = esc_attr(sanitize_text_field(get_user_meta($userId, 'business_user_name', true)));

$document_type = esc_attr(sanitize_text_field(get_user_meta($userId, 'document_type', true)));

$tax_code = esc_attr(sanitize_text_field(get_user_meta($userId, 'tax_code', true)));
$city_code = esc_attr(sanitize_text_field(get_user_meta($userId, 'city_code', true)));
$district_code = esc_attr(sanitize_text_field(get_user_meta($userId, 'district_code', true)));
$ward_code = esc_attr(sanitize_text_field(get_user_meta($userId, 'ward_code', true)));

$billing_address_1 = esc_attr(sanitize_text_field(get_user_meta($userId, 'billing_address_1', true)));

$business_image_file = esc_attr(sanitize_text_field(get_user_meta($userId, 'business_image_file', true)));

$allowFileTypeMsg = 'Chỉ hỗ trợ các file có đuôi ' . implode(", ", ULTIMATEMEMBER_CUSTOM__FILETYPE);

?>

<form action="" method="post" enctype="multipart/form-data">

	<p class="woocommerce-form-row">
		<label><?php esc_html_e('Bạn là', 'woocommerce'); ?></label>
		<input disabled type="text" class="woocommerce-Input woocommerce-Input--text input-text" value="<?php echo esc_attr($businessTypeName); ?>" />
	</p>

	<p class="woocommerce-form-row">
		<label><?php esc_html_e('Tên nhà thuốc/phòng khám', 'woocommerce'); ?></label>
		<input type="text" name="business_name" class="woocommerce-Input woocommerce-Input--text input-text" value="<?php echo esc_attr($business_name); ?>" />
	</p>

	<p class="woocommerce-form-row">
		<label><?php esc_html_e('Tên người đại diện pháp luật', 'woocommerce'); ?></label>
		<input type="text" name="business_user_name" class="woocommerce-Input woocommerce-Input--text input-text" value="<?php echo esc_attr($business_user_name); ?>" />
	</p>

	<?php foreach ($arrBusinessDocumentType as $id => $documentTypeName) : ?>
		<?php
		$name = 'document_type_' . $id . "__";
		$document_type_license_number = esc_attr(sanitize_text_field(get_user_meta($userId, $name . 'license_number', true)));
		$document_type_license_date = (int) esc_attr(sanitize_text_field(get_user_meta($userId, $name . 'license_date', true)));
		$document_type_license_date = $document_type_license_date ? date("d-m-Y", $document_type_license_date) : '';

		$document_type_license_city_code = esc_attr(sanitize_text_field(get_user_meta($userId, $name . 'license_city_code', true)));

		$document_type_license_file = esc_attr(sanitize_text_field(get_user_meta($userId, $name . 'license_file', true)));

		?>
		<fieldset>
			<legend><?php esc_html_e($documentTypeName, 'woocommerce'); ?></legend>
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<div class="row">
				<div class="col-md-4">
					<label><?php esc_html_e('Số giấy phép', 'woocommerce'); ?></label>
					<input type="text" class="woocommerce-Input" name="<?php echo $name . 'license_number'; ?>" value="<?php echo $document_type_license_number; ?>">
				</div>

				<div class="col-md-4">
					<label><?php esc_html_e('Ngày cấp', 'woocommerce'); ?></label>
					<input type="text" class="woocommerce-Input datepicker" name="<?php echo $name . 'license_date'; ?>" value="<?php echo $document_type_license_date; ?>">

				</div>

				<div class="col-md-4">
					<label><?php esc_html_e('Nơi cấp', 'woocommerce'); ?></label>
					<select name="<?php echo $name . 'license_city_code'; ?>">
						<option value="">Chọn Tỉnh/Thành Phố</option>
						<?php foreach ($arrCity as $city) : ?>
							<option <?php echo $document_type_license_city_code == $city->code ? 'selected' : ''; ?> value="<?php echo $city->code; ?>"><?php echo $city->full_name; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>

			</p>
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<?php
				$arrFile = json_decode(base64_decode($document_type_license_file), true);
				?>
				<?php if (empty($document_type_license_file) || empty($arrFile)) : ?>
					<input multiple type="file" class="woocommerce-Input" name="<?php echo $name . 'license_file[]'; ?>" id=""><br>
					<b style="color:red"><?php echo $allowFileTypeMsg; ?></b>
				<?php else : ?>
					<?php foreach ($arrFile as $file) : ?>
						<a style="color:red" rel='noopener noreferrer nofollow' target="_blank" href="<?php echo $file; ?>"><?php echo basename($file); ?></a><br>
					<?php endforeach; ?>
				<?php endif; ?>
			</p>
		</fieldset>
	<?php endforeach; ?>




	<fieldset>
		<legend><?php esc_html_e('Hình ảnh nhà thuốc', 'woocommerce'); ?></legend>
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<?php
			$arrFile = json_decode(base64_decode($business_image_file), true);
			?>
			<?php if (empty($arrFile)) : ?>
				<input multiple type="file" class="woocommerce-Input" name="business_image_file[]" id="">
			<?php else : ?>
				<?php foreach ($arrFile as $file) : ?>
					<img src="<?php echo esc_url($file); ?>" alt="Hình ảnh nhà thuốc">
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

	<p>
		<?php wp_nonce_field('save_business_info'); ?>
		<button type="submit" class="woocommerce-Button button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" name="save_business_info" value="<?php esc_attr_e('Save changes', 'woocommerce'); ?>"><?php esc_html_e('Save changes', 'woocommerce'); ?></button>
		<input type="hidden" name="action" value="save_business_info" />
	</p>


</form>