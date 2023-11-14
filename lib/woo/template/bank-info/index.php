<?php
defined('ABSPATH') || exit;
$userId = get_current_user_id();
$bankList = UltimateMemberCustom::getBankList();

$bank_account_holder_name = sanitize_text_field(get_user_meta($userId, 'bank_account_holder_name', true));
$bank_account_number = sanitize_text_field(get_user_meta($userId, 'bank_account_number', true));
$bank_code = sanitize_text_field(get_user_meta($userId, 'bank_code', true));
$errorMsg = [];
if (!empty($_POST['action']) && !empty($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], $_POST['action'])) {
    $bank_account_holder_name = sanitize_text_field($_POST['bank_account_holder_name']);
    $bank_account_number = sanitize_text_field($_POST['bank_account_number']);
    $bank_code = sanitize_text_field($_POST['bank_code']);


    // check account number 
    if (!is_numeric($bank_account_number)) $errorMsg[] = '"Số tài khoản" phải là số';

    // check bank code 
    $isBankValid = false;
    foreach ($bankList as $bank) {
        if ($bank->code == $bank_code) {
            $isBankValid = true;
            break;
        }
    }
    if (!$isBankValid) $errorMsg[] = '"Chọn ngân hàng" không hợp lệ';

    if (empty($errorMsg)) {
        update_user_meta($userId, 'bank_account_holder_name', $bank_account_holder_name);
        update_user_meta($userId, 'bank_account_number', $bank_account_number);
        update_user_meta($userId, 'bank_code', $bank_code);
    }
}

?>
<ul class="woocommerce-error" role="alert" style="<?php echo $errorMsg ? '' : 'display: none;'; ?>">
    <?php foreach($errorMsg as $e): ?>
        <li><?php echo $e; ?></li>
    <?php endforeach; ?>
</ul>
<form action="" method="post">
    <fieldset>
        <legend><?php esc_html_e('Tài khoản ngân hàng', 'woocommerce'); ?></legend>
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="bank_account_holder_name">Chủ tài khoản <span class="required">*</span></label>
            <input type="text" required class="woocommerce-Input" name="bank_account_holder_name" placeholder="NGUYEN VAN A" value="<?php echo $bank_account_holder_name; ?>">

            <label for="bank_account_holder_name">Số tài khoản <span class="required">*</span></label>
            <input type="text" required="woocommerce-Input" name="bank_account_number" placeholder="123456789" value="<?php echo $bank_account_number; ?>">

            <label for="bank_account_holder_name">Chọn ngân hàng <span class="required">*</span></label>
            <input type="text" required value="<?php echo $bank_code; ?>" class="woocommerce-Input" name="bank_code" list="banks">
            <datalist id="banks">
                <?php foreach ($bankList as $bank) : ?>
                    <option value="<?php echo esc_attr($bank->code); ?>"><?php echo esc_html($bank->name . " [$bank->short_name]"); ?></option>
                <?php endforeach; ?>
            </datalist>
            <input type="hidden" name="action" value="save_bank_info">
            <?php
            wp_nonce_field('save_bank_info');
            ?>
        </p>
        <div style="text-align: center;">
            <?php
            submit_button('Lưu');
            ?></div>
    </fieldset>
</form>