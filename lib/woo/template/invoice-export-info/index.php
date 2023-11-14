<?php
defined('ABSPATH') || exit;
$userId = get_current_user_id();
$arrInvoiceExportInfo = json_decode(sanitize_text_field(get_user_meta($userId, 'invoice_export_info', true)), true);
$arrInvoiceExportInfo = $arrInvoiceExportInfo ? $arrInvoiceExportInfo : [];
?>

<?php foreach ($arrInvoiceExportInfo as $id => $invoiceExportInfo) : ?>
    <fieldset class="invoiceExportInfoContent" data-id="<?php echo $id; ?>">
        <!-- <legend><?php echo $id; ?></legend> -->
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="bank_account_holder_name"><b>Tên công ty/Nhà thuốc/Quầy thuốc: </b><span class="name"><?php echo esc_html($invoiceExportInfo['name']); ?></span></label>
            <label for="bank_account_holder_name"><b>Mã số thuế: </b><span class="tax_code"><?php echo esc_html($invoiceExportInfo['tax_code']); ?></span></label>
            <label for="bank_account_holder_name"><b>Địa chỉ công ty: </b><span class="address"><?php echo esc_html($invoiceExportInfo['address']); ?></span></label>
        </p>
        <div style="text-align: center;">
            <p class="submit">
                <input type="button" class="editInvoiceExport" data-id="<?php echo $id; ?>" class="button button-primary" value="Chỉnh sửa">
                <input type="button" class="deleteInvoiceExport" data-id="<?php echo $id; ?>" class="button button-danger" value="Xoá">
                <!-- <p class="noticeI" style="color:red;font-weight:bold;display:none">Đang thực hiện...</p> -->
            </p>
        </div>
    </fieldset>
<?php endforeach; ?>



<fieldset>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
    <p style="text-align: center;"><a style="color: white; font-weight: bold; border: 1px solid #fff; padding: 20px; margin: auto; text-align: center; background-color: #61ce70; border-radius: 10px;" id="addShowModal" href="#modal" rel="modal:open">Thêm thông tin xuất hoá đơn</a></p>
    </p>
</fieldset>

<!-- jQuery Modal -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />


<div style="display: none;">
    <?php require_once 'modal.php'; ?>
</div>