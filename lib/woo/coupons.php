<?php

// WooCommerce Coupon
class UltimateMemberCustom_Woo_Coupons
{
    public static function init()
    {
        self::checkCoupon();
    }
    public static function init_admin()
    {
        self::addOptionsToPageWoo();
    }

    public static function checkCoupon()
    {
        add_action('woocommerce_applied_coupon', 'check_applied_coupon', 1);
        function check_applied_coupon($coupon_code)
        {
            global $woocommerce;
            $the_coupon = new WC_Coupon($coupon_code);

            $arrRoleIdAllowed = [];
            foreach ($the_coupon->get_meta_data() as $cf_value) {
                if (empty($cf_value->value)) continue;
                $arrRoleIdAllowed[] = $cf_value->value;
            }
            global $current_user;
            if (!empty($arrRoleIdAllowed) && !empty($current_user->roles) && is_array($current_user->roles)) {
                foreach ($current_user->roles as $roleId) {
                    if (in_array($roleId, $arrRoleIdAllowed)) continue;
                    wc_clear_notices();
                    $the_coupon->add_coupon_message(WC_Coupon::E_WC_COUPON_NOT_YOURS_REMOVED);
                    $woocommerce->cart->remove_coupons();
                    break;
                }
            }
        }
    }

    public static function addOptionsToPageWoo()
    {
        // Add a custom field to Admin coupon settings pages
        // woocommerce_coupon_options
        // woocommerce_coupon_options_usage_restriction
        add_action('woocommerce_coupon_options_usage_restriction', 'add_coupon_option_field', 10);
        function add_coupon_option_field()
        {
            global $wp_roles;
            $options = [];
            $options[''] = 'Tất cả';
            foreach ($wp_roles->roles as $key => $value) {
                $options[$key] = $value['name'];
            }
            woocommerce_wp_select(array(
                'id'                => 'role_id',
                'label'             => __('Chỉ dùng cho Role:'),
                'description'       => __('Chỉ những người dùng nào có role này thì mới được khuyến mãi'),
                'desc_tip'    => true,
                'options' => $options,
                'class' => 'admin-multiselect',

            ));
        }

        // Save the custom field value from Admin coupon settings pages
        add_action('woocommerce_coupon_options_save', 'save_coupon_text_field', 10, 2);
        function save_coupon_text_field($post_id, $coupon)
        {
            if (isset($_POST['role_id'])) {
                $coupon->update_meta_data('role_id', sanitize_text_field($_POST['role_id']));
                $coupon->save();
            }
        }


        // Adding a new column to admin orders list
        // add_filter('manage_edit-shop_order_columns', 'custom_shop_order_column');
        // function custom_shop_order_column($columns)
        // {
        //     $reordered_columns = array();

        //     // Inserting columns to a specific location
        //     foreach ($columns as $key => $column) {
        //         $reordered_columns[$key] = $column;
        //         if ($key ==  'order_status') {
        //             // Inserting after "Status" column
        //             $reordered_columns['coupons'] = __('Coupon', 'theme_domain');
        //         }
        //     }
        //     return $reordered_columns;
        // }

        // Adding used coupon codes
        // add_action('manage_shop_order_posts_custom_column', 'custom_orders_list_column_content', 10, 2);
        // function custom_orders_list_column_content($column, $post_id)
        // {
        //     global $the_order;

        //     if ($column == 'coupons') {
        //         $coupons = (array) $the_order->get_used_coupons();
        //         $dealers = [];

        //         foreach ($coupons as $coupon_code) {
        //             $coupon    = new WC_Coupon($coupon_code);
        //             $dealers[] = $coupon->get_meta('role_id');
        //         }

        //         if (count($coupons) > 0)
        //             echo implode(', ', $coupons);

        //         if (count($dealers) > 0)
        //             echo '<br><small>(' . implode(', ', $dealers) . ')</small>';
        //     }
        // }


    }
}
