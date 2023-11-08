<?php
class UltimateMemberCustom
{
    public static function init()
    {
        if (!is_admin()) return;
        self::showUserUploadFile();
    }


    public static function showUserUploadFile()
    {
        add_action('show_user_profile', '__showUserUploadFile', 1);
        add_action('edit_user_profile', '__showUserUploadFile', 1);

        function __showUserUploadFile($user)
        { ?>
            <h3><?php _e("File Upload khi đăng ký (từ plugin Ultimate Member)"); ?></h3>
            <?php
            $userId = $user->ID;
            $uploads = wp_upload_dir();
            $baseDir = str_replace("\\", "/", $uploads['basedir'] . "/ultimatemember/$userId");
            $baseUrl = str_replace("\\", "/", $uploads['baseurl'] . "/ultimatemember/$userId");

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
                                // $fileExt = end(explode(".", $fileName));
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
                .iframeShow{
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
                        if(typeof iframeShow.attr('src') === 'undefined' || iframeShow.attr('src') === ''){
                            iframeShow.attr('src', iframe.attr('src'));
                        }
                        iframeShow.addClass('live');

                        const btnClose =  $('#close-preview');
                        btnClose.data('id', $(this).data('id'));
                        btnClose.addClass('live');
                    });

                    $("#close-preview").click(function(e) {
                        const btnClose =  $('#close-preview');
                        const id = btnClose.data('id');
                        btnClose.removeClass('live');
                        $('.file-prevew[data-id="'+id+'"]').find('.iframeShow').removeClass('live');
                    });

                })(jQuery);
            </script>

<?php
        }
    }
}
