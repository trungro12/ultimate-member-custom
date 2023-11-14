<div id="modal" class="modal">
    <form id="frm" action="" method="post">
        <fieldset>
            <legend><?php esc_html_e('Thêm thông tin xuất hoá đơn', 'woocommerce'); ?></legend>
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="bank_account_holder_name">Tên công ty/Nhà thuốc/Quầy thuốc <span class="required">*</span></label>
                <input type="text" required class="woocommerce-Input" name="name" value="">

                <label for="bank_account_holder_name">Mã số thuế <span class="required">*</span></label>
                <input type="text" class="woocommerce-Input" name="tax_code" value="">

                <label for="bank_account_holder_name">Địa chỉ công ty <span class="required">*</span></label>
                <input type="text" class="woocommerce-Input" name="address" value="">
            </p>
            <input type="hidden" class="woocommerce-Input" name="id" value="">
            <input type="hidden" name="action" value="">
            <div style="text-align: center;">
                <p class="submit"><input type="button" id="saveInvoiceExport" class="button button-primary" value="Lưu"></p>
                <p id="notice" style="color:red;font-weight:bold;display:none">Đang thực hiện...</p>
            </div>
        </fieldset>
    </form>
</div>

<script>
    const ajaxUrl = '<?php echo esc_url(admin_url('admin-ajax.php')); ?>';
    (function($) {

        $('#addShowModal').click(function() {
            $('#frm').find('[name="name"]').val('');
            $('#frm').find('[name="tax_code"]').val('');
            $('#frm').find('[name="address"]').val('');
            $('#frm').find('[name="action"]').val('');
            $('#frm').find('[name="id"]').val('');
        });
        $('.editInvoiceExport').click(function() {
            $('#addShowModal').click();
            const id = $(this).data('id').trim();
            const _parent = $('.invoiceExportInfoContent[data-id="' + id + '"]');
            $('#frm').find('[name="name"]').val(_parent.find('.name').text().trim());
            $('#frm').find('[name="tax_code"]').val(_parent.find('.tax_code').text().trim());
            $('#frm').find('[name="address"]').val(_parent.find('.address').text().trim());
            $('#frm').find('[name="id"]').val(id);
        });

        $('.deleteInvoiceExport').click(function() {
            const check = confirm('Bạn có chắc muốn xoá?');
            if (check) {
                const id = $(this).data('id');
                $.ajax({
                    type: "post",
                    url: ajaxUrl,
                    data: {
                        action: "umc_modal_save_invoice_export_info",
                        actionName: 'delete',
                        id: id
                    },
                    beforeSend: function() {
                    },
                    success: function(response) {
                        if (response.success) {
                            // alert('Thành công!');
                            location.reload();

                        } else {
                            alert('Đã có lỗi xảy ra');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        // notice.show();
                        // notice.text('The following error occured: ' + textStatus, errorThrown);
                    }
                });
            }
        });


        $('#saveInvoiceExport').click(function() {
            const name = $('#frm').find('[name="name"]').val();
            const tax_code = $('#frm').find('[name="tax_code"]').val();
            const address = $('#frm').find('[name="address"]').val();
            const id = $('#frm').find('[name="id"]').val();

            if (!name || !tax_code || !address) {
                alert('Vui lòng nhập đầy đủ thông tin!');
                return;
            }

            const notice = $('#frm #notice');
            const action = $('#frm').find('[name="action"]').val();

            $.ajax({
                type: "post",
                url: ajaxUrl,
                data: {
                    action: "umc_modal_save_invoice_export_info",
                    name: name,
                    tax_code: tax_code,
                    address: address,
                    actionName: action,
                    id: id
                },
                beforeSend: function() {
                    notice.text('Đang thực hiện...');
                    notice.show();
                },
                success: function(response) {
                    notice.hide();
                    if (response.success) {
                        // alert('Thành công!');
                        location.reload();

                    } else {
                        alert('Đã có lỗi xảy ra');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    notice.show();
                    notice.text('The following error occured: ' + textStatus, errorThrown);
                }
            });
        });

    })(jQuery);
</script>