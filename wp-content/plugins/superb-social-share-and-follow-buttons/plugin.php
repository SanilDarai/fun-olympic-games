<?php
    final class spbsm
    {
        private $version;
        private $plugin_base;
        private $base_url;
        private $base_dir;
        private $user_caps = 'manage_categories';
        private $page_hook_s;
        private $page_hook_f;
        private $db;
        private $pluginNameComment;

        private $defaultFollowText;
        private $defaultShareText;

        // ajax response msgs
        private $urlErrorResponse;
        private $sidebarErrorResponse;
        private $colorErrorResponse;
        private $genericErrorResponse;

        private static $instance;
        public static function GetInstance($version, $base)
        {
            if (!isset(self::$instance)) {
                self::$instance = new self($version, $base);
            }
            return self::$instance;
        }

        public function __construct($version, $base)
        {
            $this->base_url = plugin_dir_url(__FILE__);
            $this->base_dir = plugin_dir_path(__FILE__);
            $this->version = $version;
            $this->plugin_base = plugin_basename($base);
            require_once $this->base_dir . '/inc/data/db.php';
            $this->db = spbsm_db::GetInstance();
            register_activation_hook($base, array($this, 'initialize'));
            if (get_option('spbsm_db_version') != $this->db->db_version) {
                $this->initialize();
            }
            add_action('init', array($this, 'spbsm_load_textdomain' ));
            add_action('admin_menu', array($this, 'add_menu_items'));
            add_action('admin_enqueue_scripts', array($this, 'backend_enqueue'));
            add_action('wp_enqueue_scripts', array($this, 'frontend_enqueue'));
            add_filter('plugin_row_meta', array($this, 'plugin_row_meta' ), 10, 2);
            add_action('wp_ajax_spbsmAjax', array($this, 'spbsmAjax'));
            add_shortcode('spbsm-share-buttons', array($this, 'frontend_share'));
            add_shortcode('spbsm-follow-buttons', array($this, 'frontend_follow'));
            $this->setPositionSettingFilters();
            $this->pluginNameComment = '<!-- Superb Social Share and Follow Buttons -->';
            $this->localizeStrings();
            add_action('admin_init', array($this, 'spbsm_spbThemesNotification'), 9);
        }
        public function spbsm_spbThemesNotification()
        {
            $notifications = include($this->base_dir.'/admin_notification/Autoload.php');
            $options = array("delay"=> "+2 days");
            $notifications->Add("spbsm_admin_notification", "Unlock All Features with Social Media Buttons Premium", "
		
            Take advantage of the up to <span style='font-weight:bold;'>45% discount</span> and unlock all features for Superb Social Media Buttons Premium. 
            The discount is only available for a limited time.
    
            <div>
            <a style='margin-bottom:15px;' class='button button-large button-secondary' target='_blank' href='https://superbthemes.com/plugins/social-media-share-and-follow-buttons/'>Read more</a> <a style='margin-bottom:15px;' class='button button-large button-primary' target='_blank' href='https://superbthemes.com/plugins/social-media-share-and-follow-buttons/'>Buy now</a>
            </div>
    
            ", "info", $options);
            $notifications->Boot();
        }

        public function spbsm_load_textdomain()
        {
            load_plugin_textdomain('spbsm', false, dirname($this->plugin_base) . '/languages');
        }

        private function localizeStrings()
        {
            $this->localizeFront();
            $this->localizeBack();
        }

        private function localizeFront()
        {
            $this->defaultFollowText = esc_html__("Follow us on Social Media", 'spbsm');
            $this->defaultShareText = esc_html__("Share on Social Media", 'spbsm');
        }

        private function localizeBack()
        {
            $this->queueErrorResponse =  esc_html__("Couldn't save settings. Order data invalid.", 'spbsm');
            $this->urlErrorResponse =  esc_html__("Couldn't save settings. Profile link should be https.", 'spbsm');
            $this->sidebarErrorResponse =  esc_html__("Couldn't save settings. Sidebar setting invalid.", 'spbsm');
            $this->designErrorResponse = esc_html__("Couldn't save settings. Selected button design is invalid.", 'spbsm');
            $this->colorErrorResponse = esc_html__("Couldn't save settings. A selected color is not a valid color.", 'spbsm');
            $this->genericErrorResponse = esc_html__("Couldn't save settings. Data invalid.", 'spbsm');
        }

        public function plugin_row_meta($row_meta, $file)
        {
            if ($this->plugin_base == $file) {
                $row_meta[] = '<a href="'.admin_url('admin.php?page=spbsm').'" aria-label="Options">Options</a>';
            }
            return $row_meta;
        }

        public function add_menu_items()
        {
            $user_caps = apply_filters('spbsm_user_capabilities', $this->user_caps);
            $has_menu = menu_page_url('spbhlpr', false);
            if ($has_menu) {
                $this->page_hook_s = add_submenu_page('spbhlpr', 'Share Buttons', 'Share Buttons', $user_caps, 'spbsm', array($this, 'backend_share'), 1);
                $this->page_hook_f = add_submenu_page('spbhlpr', 'Follow Buttons', 'Follow Buttons', $user_caps, 'spbsm-follow', array($this, 'backend_follow'), 1);
            } else {
                add_menu_page('Superb Social Share and Follow Buttons', 'Superb Social Media Buttons', $user_caps, 'spbsm', array($this, 'backend_share'), $this->base_url . "assets/img/icon.png");
                $this->page_hook_s = add_submenu_page('spbsm', 'Share Buttons', 'Share Buttons', $user_caps, 'spbsm');
                $this->page_hook_f = add_submenu_page('spbsm', 'Follow Buttons', 'Follow Buttons', $user_caps, 'spbsm-follow', array($this, 'backend_follow'));
            }
        }

        public function backend_enqueue($hook)
        {
            if ($hook == $this->page_hook_s || $hook == $this->page_hook_f) {
                wp_enqueue_style('spbsm-backend', $this->base_url . '/assets/css/backend.css', false, $this->version, 'all');
                $this->frontend_enqueue();
                wp_enqueue_script('spbsm-tablednd', $this->base_url . '/js/jquery.tablednd.js');
                wp_enqueue_script('spbsm-script', $this->base_url . '/js/backend.js', array('jquery', 'wp-color-picker'), $this->version);
                wp_enqueue_style('wp-color-picker');
                wp_localize_script('spbsm-script', 'msgs', array(
                'alreadySaved'  => esc_html__("Current settings already saved..", 'spbsm'),
                'error' => esc_html__("Couldn't save settings..", 'spbsm'),
                'fieldErrorMultiple' => esc_html__("fields are not valid profile links..", 'spbsm'),
                'fieldErrorSingle' => esc_html__("field is not a valid profile link..", 'spbsm'),
                'fieldErrorExpected' => esc_html__(" Expected https://(site).com/(profile) or similar.", 'spbsm'),
            ));
            }
        }

        public function frontend_enqueue()
        {
            wp_enqueue_style('spbsm-stylesheet', $this->base_url . '/assets/css/frontend.css', false, $this->version, 'all');
            wp_enqueue_style('spbsm-lato-font', 'https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap', false, $this->version, 'all');
        }

        private function review_banner()
        {
            ?><div class="review-banner"><p><span>&#128075;</span> Hi there! We sincerely hope you're enjoying our superb social media buttons plugin. Please consider <a href="https://wordpress.org/support/plugin/superb-social-share-and-follow-buttons/reviews/" target="_blank">reviewing it here</a>. It means the world to us!</p></div><?php
        }

        public function backend_share()
        {
            $this->review_banner();
            include_once $this->base_dir . "/inc/backend_share.php";
        }

        public function backend_follow()
        {
            $this->review_banner();
            include_once $this->base_dir . "/inc/backend_follow.php";
        }

        public function frontend_share()
        {
            $this->localizeFront();
            $buttons = $this->db->getShareButtons();
            if ($buttons) {
                $permlink = get_permalink();
                $permlink = isset($permlink) && !empty($permlink) ? $permlink : get_home_url();
                $title = get_the_title();
                $title = isset($title) && !empty($title) ? $title : get_bloginfo("name");
                $logo = wp_get_attachment_image_src(get_theme_mod('custom_logo'));
                $logo = (isset($logo) && !empty($logo) && $logo !== false && is_array($logo)) ? $logo[0] : '';
                $thumb = get_the_post_thumbnail_url();
                $thumb = isset($thumb) && !empty($thumb) ? $thumb : $logo;
                $excerpt = apply_filters('the_excerpt', get_post_field('post_excerpt', get_the_ID()));
                $excerpt = isset($excerpt) && !empty($excerpt) ? $excerpt : get_bloginfo("description");
                ob_start();
                echo '<div class="spbsm-sharebuttons-output-wrapper">';
                echo $this->pluginNameComment;
                echo '<div class="spbsm-output-textstring">'.esc_attr($this->defaultShareText).'</div>';
                echo '<div class="spbsm-button-wrapper-flat">';
                foreach ($buttons as &$button) {
                    $link = str_replace(
                        array('{url}','{title}','{img}','{description}'),
                        array(esc_url($permlink),urlencode($title),$thumb,urlencode($excerpt)),
                        $button['share']
                    );
                    echo '<span class="spbsm-share-'.esc_attr($button['class']).'"><a href="'.esc_url($link).'" rel="nofollow" target="_blank">'.$button['icon'];
                    echo esc_attr($button['class']);
                    echo '</a></span>';
                }
                echo '</div></div>';
                return ob_get_clean();
            }
            return "";
        }

        public function frontend_follow()
        {
            $this->localizeFront();
            $buttons = $this->db->getFollowButtons();
            if ($buttons) {
                ob_start();
                echo '<div class="spbsm-followbuttons-output-wrapper">';
                echo $this->pluginNameComment;
                echo '<div class="spbsm-output-textstring">'.esc_attr($this->defaultFollowText).'</div>';
                echo '<div class="spbsm-button-wrapper-flat">';
                foreach ($buttons as &$button) {
                    if ($button['class']=='email') {
                        continue;
                    }
                    $name = isset($button['alt-name']) ? $button['alt-name'] : $button['class'];
                    echo '<span class="spbsm-follow-'.esc_attr($button['class']).'"><a href="'.esc_url($button['follow_url']).'" rel="nofollow" target="_blank">'.$button['icon'];
                    echo esc_html($name);
                    echo '</a></span>';
                }
                echo '</div></div>';
                return ob_get_clean();
            }
            return "";
        }

        public function setPositionSettingFilters()
        {
            add_filter('the_content', array($this, 'addToContent'));
            add_filter('wp_footer', array($this,'addSidebar'));
        }

        public function addToContent($content)
        {
            if (!is_single() && !is_page()) {
                return $content;
            }
            $prepend = "";
            $append = "";
            $scShare;
            $scFollow;
            $shareSettings = $this->db->get_positionSettings(1);
            $followSettings = $this->db->get_positionSettings(2);
            if ($shareSettings['posts_addAtStart']==1||$shareSettings['posts_addAtEnd']==1||
            $shareSettings['pages_addAtStart']==1||$shareSettings['pages_addAtEnd']==1) {
                $scShare = do_shortcode('[spbsm-share-buttons]');
            }

            if ($followSettings['posts_addAtStart']==1||$followSettings['posts_addAtEnd']==1||
            $followSettings['pages_addAtStart']==1||$followSettings['pages_addAtEnd']==1) {
                $scFollow = do_shortcode('[spbsm-follow-buttons]');
            }

            if (!isset($scFollow) && !isset($scShare)) {
                return $content;
            }

            if (is_single()) {
                if ($shareSettings['posts_addAtStart']==1) {
                    $prepend .= $scShare;
                }
                if ($shareSettings['posts_addAtEnd']==1) {
                    $append .= $scShare;
                }
        
                if ($followSettings['posts_addAtStart']==1) {
                    $prepend .= $scFollow;
                }
                if ($followSettings['posts_addAtEnd']==1) {
                    $append .= $scFollow;
                }
            } else {
                if ($shareSettings['pages_addAtStart']==1) {
                    $prepend .= $scShare;
                }
                if ($shareSettings['pages_addAtEnd']==1) {
                    $append .= $scShare;
                }
    
                if ($followSettings['pages_addAtStart']==1) {
                    $prepend .= $scFollow;
                }
                if ($followSettings['pages_addAtEnd']==1) {
                    $append .= $scFollow;
                }
            }

            return $prepend.$content.$append;
        }

        public function addSidebar()
        {
            $settings = $this->db->getSidebarSettings();
            if ($settings) {
                if ($settings[0]['sidebar'] == $settings[1]['sidebar']) {
                    $hide = $settings[0]['hide'] == 1 || $settings[1]['hide'] == 1 ? ' spbsm-hideonmobile' : '';
                    $class = 'spbsm-sidebar-wrapper';
                    if ($settings[0]['sidebar']==1) {
                        $class.='-leftcenter';
                    }
                    if ($settings[0]['sidebar']==2) {
                        $class.='-rightcenter';
                    }
                    if ($settings[0]['sidebar']==3) {
                        $class.='-bottomright';
                    }
                    if ($settings[0]['sidebar']==4) {
                        $class.='-bottomleft';
                    }
                    echo '<div class="spbsm-sidebar-wrapper '.$class.$hide.'">';
                    echo do_shortcode('[spbsm-share-buttons]');
                    echo do_shortcode('[spbsm-follow-buttons]');
                    echo '</div>';
                } else {
                    if ($settings[0]['sidebar'] > 0) {
                        $hide = $settings[0]['hide'] == 1 ? ' spbsm-hideonmobile' : '';
                        $class = 'spbsm-sidebar-wrapper';
                        if ($settings[0]['sidebar']==1) {
                            $class.='-leftcenter';
                        }
                        if ($settings[0]['sidebar']==2) {
                            $class.='-rightcenter';
                        }
                        if ($settings[0]['sidebar']==3) {
                            $class.='-bottomright';
                        }
                        if ($settings[0]['sidebar']==4) {
                            $class.='-bottomleft';
                        }
                        echo '<div class="spbsm-sidebar-wrapper '.$class.$hide.'">';
                        echo do_shortcode('[spbsm-share-buttons]');
                        echo '</div>';
                    }
                    if ($settings[1]['sidebar'] > 0) {
                        $hide = $settings[1]['hide'] == 1 ? ' spbsm-hideonmobile' : '';
                        $class = 'spbsm-sidebar-wrapper';
                        if ($settings[1]['sidebar']==1) {
                            $class.='-leftcenter';
                        }
                        if ($settings[1]['sidebar']==2) {
                            $class.='-rightcenter';
                        }
                        if ($settings[1]['sidebar']==3) {
                            $class.='-bottomright';
                        }
                        if ($settings[1]['sidebar']==4) {
                            $class.='-bottomleft';
                        }
                        echo '<div class="spbsm-sidebar-wrapper '.$class.$hide.'">';
                        echo do_shortcode('[spbsm-follow-buttons]');
                        echo '</div>';
                    }
                }
            }
        }

        public function spbsmAjax()
        {
            if (!isset($_POST['cmd']) && !isset($_POST['_wpnonce']) && !wp_verify_nonce($_POST['_wpnonce'])) {
                exit();
            }
            $result = array();
            try {
                switch ($_POST['cmd']) {
                case 'save':
                    $form = array();
                    parse_str($_POST['form'], $form);
                    $sanitizedDto = $this->sanitize_form($form);
                    if (!isset($sanitizedDto) || ($_POST['page'] != 'follow' && $_POST['page'] != 'share')) {
                        throw new Exception($this->genericErrorResponse);
                    }
                    $response = $_POST['page'] == 'share' ? $this->db->update_share($sanitizedDto) : $this->db->update_follow($sanitizedDto);
                    $result['type'] = $response[0];
                    $result['msg'] = $response[1];
                    break;
                
                default:
                    $result['type'] = "error";
                    $result['result'] = "invalid cmd";
                    break;
            }
            } catch (Exception $ex) {
                $result['type'] = 'error';
                $result['msg'] = $ex->getMessage();
            }
            wp_send_json($result);
        }


        ///sanitize user input
        private function sanitize_form($form)
        {
            try {
                //create Dto
                $sanitized = array();
                foreach ($form as $key => &$value) {
                    $key = esc_sql(sanitize_text_field($key));
                    switch ($key) {
                case 'floatingSidebar':
                    $sanitized['floatingSidebar'] = $this->sanitize($form['floatingSidebar'], 'sidebar');
                break;
                case 'general':
                    foreach ($form['general'] as $key => $value) {
                        $sanitized['general'][esc_sql(sanitize_text_field($key))] = $this->sanitize($value, 'bit');
                    }
                break;
                case '_wpnonce':
                case '_wp_http_referer':
                case 'tabs':
                    //no need to include these fields in Dto.
                break;
                default:
                    $share = $form[$key]['share'];
                    $share_queue = $form[$key]['share_queue'];
                    $follow = $form[$key]['follow'];
                    $follow_url = $form[$key]['follow_url'];
                    $follow_queue = $form[$key]['follow_queue'];
                    if (isset($share)) {
                        $sanitized[$key]['share'] = $this->sanitize($share, 'bit');
                    }
                    if (isset($follow)) {
                        $sanitized[$key]['follow'] = $this->sanitize($follow, 'bit');
                    }
                    if (isset($follow_url)) {
                        $sanitized[$key]['follow_url'] = $this->sanitize($follow_url, 'url');
                    }
                    if (isset($share_queue)) {
                        $sanitized[$key]['share_queue'] = $this->sanitize($share_queue, 'queue');
                    }
                    if (isset($follow_queue)) {
                        $sanitized[$key]['follow_queue'] = $this->sanitize($follow_queue, 'queue');
                    }
                break;
            }
                }
                return $sanitized;
            } catch (Exception $ex) {
                throw new Exception($ex->getMessage());
            }
        }

        private function sanitize($value, $type = '')
        {
            switch ($type) {
            case 'bit':
            return $value == 1 || $value == 'on' ? 1 : 0;
            break;

            case 'url':
            if (empty($value)) {
                return null;
            }
            $url = esc_url_raw($value, ['https']);
            if (empty($url)) {
                throw new Excepton($this->urlErrorResponse);
            }
            return esc_sql($url);
            break;

            case 'sidebar':
            if (!is_int(intval($value))) {
                throw new Exception($this->sidebarErrorResponse);
            }
            return ($value<0 || $value>4) ? 0 : $value;
            break;
            
            case 'queue':
                $queue = intval($value);
                if ($queue < 0 || $queue > 200) {
                    throw new Exception($this->queueErrorResponse);
                }
                return $queue;
                break;

            default:
            return esc_sql(sanitize_text_field($value));
            break;
        }
        }




        public function initialize()
        {
            $this->db->create_table();
        }
    }
