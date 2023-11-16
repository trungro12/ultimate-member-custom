<?php

class UltimateMemberCustom_SearchForm
{
    static function init()
    {
        self::addShortCode();
        self::customQueryPosts();
        self::addNoteInProductAttrAdmin();

        self::customSearchForm();
    }

    static function customSearchForm()
    {
        // add_filter('get_search_form', function($html){
        //     return $html . "<b>asdadad</b>";
        // });
        add_action('wp_footer', function () {
            if (is_admin()) return;
            echo do_shortcode('[umc_search_form_input_type]');
        });
    }


    static function addShortCode()
    {
        $class = 'UltimateMemberCustom_SearchForm';
        add_shortcode('umc_search_form_input_type', $class . "::__htmlSearchFormInputType");
    }
    static function __htmlSearchFormInputType()
    {
?>
        <div style="display:none">
            <select style="display: inline-block; max-width: 25%; position: absolute; right: 50px; border-radius: 0; border-right: 0;" name="search_by" id="search_by">
                <!-- <option value="PRODUCT_AND_ATTR">Thuốc & Hoạt Chất</option> -->
                <option value="PRODUCT">Thuốc</option>
                <option value="ATTR">Hoạt Chất</option>
            </select>
        </div>
        <script>
            (function($) {
                $(function() {
                    $('#search_by').appendTo('form.searchform');
                });
            })(jQuery);
        </script>
        <?php
    }

    static function addNoteInProductAttrAdmin()
    {
        if (!is_admin()) return;
        add_action('woocommerce_product_options_attributes', function () {
        ?>
            <div class="woocommerce-message" style="padding: 10px; font-weight: bold;">
                <p class="help">
                    <?php
                    esc_html_e(
                        'Thêm "Hoạt chất" và nội dung để có thể tìm kiếm bằng Hoạt chất',
                        'woocommerce'
                    );
                    ?>
                </p>
            </div>
<?php
        });
    }



    static function customQueryPosts()
    {
        add_action('pre_get_posts', 'filter_posts_by_custom_search_by', 99, 1);
        function filter_posts_by_custom_search_by($query)
        {
            if (is_admin()) {
                return;
            }

            $searchBy = empty($_GET['search_by']) ? '' : sanitize_text_field($_GET['search_by']);
            if (empty($searchBy)) return;
            $search = empty($_GET['s']) ? '' : sanitize_text_field($_GET['s']);
            $taxQueryFilter = [
                // 'relation' => 'OR',
                [
                    'taxonomy' => 'pa_hoat-chat',
                    'field'    => 'slug',
                    'terms' => sanitize_title($search),
                    'operator' => 'IN'
                ],
            ];
            if ($searchBy === 'PRODUCT_AND_ATTR' || $searchBy === 'PRODUCT') {
                if (!empty($_GET['s'])) $query->set('s', $search);
                if ($searchBy === 'PRODUCT') $taxQueryFilter = [];
            }
            if ($searchBy === 'ATTR') {
                $query->set('s', '');
            }


            if (!empty($taxQueryFilter)) $query->set('tax_query', $taxQueryFilter);

            return;
        }
    }
}
