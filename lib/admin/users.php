<?php

class UltimateMemberCustom_Admin_Users
{
    static function init()
    {
        self::showUserVerifyFieldInEditUsers();
        self::showUserUploadFileFieldInEditUsers();
        self::showCustomFieldInUserAdminPanel();
    }


    public static function showUserVerifyFieldInEditUsers()
    {
        add_action('show_user_profile', '__showUserVerifyField', 1);
        add_action('edit_user_profile', '__showUserVerifyField', 1);

        add_action('show_user_profile_update', '__updateUserVerifyField', 10);
        add_action('profile_update', '__updateUserVerifyField', 10);
        add_action('edit_user_profile_update', '__updateUserVerifyField', 10);
        function __updateUserVerifyField($user_id)
        {
            if (isset($_POST['is_verified'])) {
                $isVerify = (int) $_POST['is_verified'];
                update_user_meta($user_id, 'is_verified', $isVerify);
            }
        }

        function __showUserVerifyField()
        {
            $userId = !empty($_GET['user_id']) ? (int) $_GET['user_id'] : get_current_user_id();
            $isVerify = (int) get_user_meta($userId, 'is_verified', true);
?>
            <h3><?php _e('Tình trạng'); ?></h3>
            <tr class="user-display-name-wrap">
                <th>
                    <label for="is_verify">Tình trạng xác minh</label>
                </th>
                <td>
                    <select name="is_verified" id="is_verified">
                        <option value="0" <?php echo $isVerify === 0 ? 'selected' : '' ?>>Chưa xác minh</option>
                        <option value="1" <?php echo $isVerify === 1 ? 'selected' : '' ?>>Đã xác minh</option>
                    </select>
                </td>
            </tr>
        <?php
        }
    }

    public static function showUserUploadFileFieldInEditUsers()
    {
        add_action('show_user_profile', '__showUserUploadFileField', 1);
        add_action('edit_user_profile', '__showUserUploadFileField', 1);

        function __showUserUploadFileField($user)
        {
            $title = 'File Upload, hỗ trợ các file có đuôi ' . '<b style="color:red">' . implode(", ", ULTIMATEMEMBER_CUSTOM__FILETYPE) . '</b>';
        ?>
            <h3><?php _e($title); ?></h3>
            <?php
            $userId = $user->ID;
            $uploads = wp_upload_dir();
            $baseDir = str_replace("\\", "/", $uploads['basedir'] . "/ultimatemembercustom/$userId");
            $baseUrl = str_replace("\\", "/", $uploads['baseurl'] . "/ultimatemembercustom/$userId");

            $files = glob($baseDir . "/*");
            wp_enqueue_script('jquery');
            wp_enqueue_media();
            wp_enqueue_script('media-grid');
            wp_enqueue_script('media');
            ?>
            <table class="form-table">
                <div class="wp-core-ui">
                    <div class="attachments-wrapper">
                        <ul tabindex="-1" class="attachments ui-sortable ui-sortable-disabled" id="__attachments-view-47">


                            <?php foreach ($files as $index => $file) : ?>
                                <?php
                                $id = $index + 1;
                                $file = str_replace("\\", "/", $file);
                                $fileName = end(explode("/", $file));
                                $fileExt = strtolower(trim(end(explode(".", $fileName))));

                                if (!in_array($fileExt, ULTIMATEMEMBER_CUSTOM__FILETYPE)) continue;

                                $fileUrl = esc_url(str_replace($baseDir, $baseUrl, $file));

                                ?>
                                <div class="block-item">

                                    <li data-id="<?php echo $id; ?>" data-url="<?php echo $fileUrl; ?>" tabindex="0" role="checkbox" aria-checked="false" class="attachment file-prevew">
                                        <iframe src="" frameborder="0" class="iframeShow"></iframe>
                                        <div class="attachment-preview js--select-attachment type-application subtype-pdf landscape">
                                            <div class="thumbnail">
                                                <div class="centered">
                                                    <iframe style="height: 100%;" src="<?php echo $fileUrl; ?>" class="preview icon image" frameborder="0"></iframe>
                                                </div>
                                                <div class="filename">
                                                    <div><?php echo $fileName; ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </div>

                            <?php endforeach; ?>



                        </ul>
                    </div>
                </div>

                </tr>
            </table>
            <input class="button button-primary" type="button" value="Close" id="close-preview">
            <style>
                .image {
                    transform: translate(-50%, -50%);
                    position: absolute;
                    top: 0;
                    left: 0;
                    max-height: 100%;
                    border: none;
                }

                .hide {
                    display: none !important;
                }

                .iframeShow {
                    display: none;
                }

                .iframeShow.live {
                    display: block;
                    position: fixed;
                    top: 1%;
                    bottom: 5%;
                    height: 85%;
                    left: 0;
                    right: 0;
                    margin: auto;
                    width: 85%;
                    z-index: 9999;
                }

                #close-preview {
                    display: none;
                }

                #close-preview.live {
                    display: block;
                    position: fixed;
                    top: 78%;
                    bottom: 15%;
                    height: 10%;
                    left: 0;
                    right: 0;
                    margin: auto;
                    width: 7%;
                    z-index: 9999;
                    font-weight: bold;
                    font-size: 15px;
                }
            </style>
            <script>
                (function($) {

                    $('.file-prevew').click(function() {
                        // const fileUrl = $(this).data('url');
                        // window.open(fileUrl, '_blank');
                        const iframe = $(this).find('iframe.preview');
                        const iframeShow = $(this).find('.iframeShow');
                        if (typeof iframeShow.attr('src') === 'undefined' || iframeShow.attr('src') === '') {
                            iframeShow.attr('src', iframe.attr('src'));
                        }
                        iframeShow.addClass('live');

                        const btnClose = $('#close-preview');
                        btnClose.data('id', $(this).data('id'));
                        btnClose.addClass('live');
                    });

                    $("#close-preview").click(function(e) {
                        const btnClose = $('#close-preview');
                        const id = btnClose.data('id');
                        btnClose.removeClass('live');
                        $('.file-prevew[data-id="' + id + '"]').find('.iframeShow').removeClass('live');
                    });

                })(jQuery);
            </script>

        <?php
        }
    }



    static function showCustomFieldInUserAdminPanel()
    {
        function new_modify_user_table($column)
        {
            $column['is_verified'] = 'Tình trạng xác minh';
            return $column;
        }
        add_filter('manage_users_columns', 'new_modify_user_table');

        function new_modify_user_table_row($val, $column_name, $user_id)
        {
            switch ($column_name) {
                case 'is_verified':
                    return (int) sanitize_text_field(get_the_author_meta('is_verified', $user_id)) ? '<b style="color:green">✅Đã xác minh</b>' : '<b style="color:red">Chưa xác minh</b>';
                default:
                    return $val;
            }
            return $val;
        }
        add_filter('manage_users_custom_column', 'new_modify_user_table_row', 10, 3);

        // function new_contact_methods( $contactmethods ) {
        //     $contactmethods['phone'] = 'Phone Number';
        //     return $contactmethods;
        // }
        // add_filter( 'user_contactmethods', 'new_contact_methods', 10, 1 );


        // filter 
        add_action('manage_users_extra_tablenav', 'render_custom_filter_options');
        function render_custom_filter_options()
        {
            $isVerified = sanitize_text_field($_GET['is_verified']);
        ?>

            <form action="" method="get">
                <select name="is_verified">
                    <option value="">Tình trạng xác minh</option>
                    <option <?php echo $isVerified == 1 ? 'selected' : ''; ?> value="1">✅Đã xác minh</option>
                    <option <?php echo $isVerified == 0 ? 'selected' : ''; ?> value="0">Chưa xác minh</option>
                </select>

                <input type="submit" class="button action" value="Filter">
            </form>

<?php
        }


        add_action('pre_get_users', 'filter_users_by_is_verified', 99, 1);
        function filter_users_by_is_verified($query)
        {
            if (!is_admin()) {
                return;
            }
            global $pagenow;
       
            if ('users.php' === $pagenow && isset($_GET['is_verified'])) {
               
                $isVerified = sanitize_text_field($_GET['is_verified']);
                if ($isVerified !== '') {
                    if ((int) $isVerified !== 1) {
                        $meta_query = [
                            'relation' => 'OR',
                            [
                                'key' => 'is_verified',
                                'value' => 0,
                                'compare' => '='
                            ],
                            [
                                'key' => 'is_verified',
                                'compare' => 'NOT EXISTS'
                            ],
                        ];
                    } else {
                        $meta_query = [
                            [
                                'key' => 'is_verified',
                                'value' => 1,
                                'compare' => '='
                            ]
                        ];
                    }

                    $query->set('meta_query', $meta_query);
                }
            }

            // return;
        }
    }
}
