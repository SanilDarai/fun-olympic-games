<?php
final class spbsm_db
{
    private $db;
    private $medias;

    private $sqlErrorResponse;
    private $successResponse;

    private static $instance;
    public static function GetInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    public function __construct()
    {
        global $wpdb;
        $this->db = $wpdb;
        $this->table_settings = $this->db->prefix ."spbsm";
        $this->table_positionSettings = $this->db->prefix ."spbsm_position";
        $this->db_version = "1.5";
        $this->medias = include plugin_dir_path(__FILE__).'mediadata.php';
        $this->sqlErrorResponse =  __("Couldn't save settings. Data couldn't be updated.", 'spbsm');
        $this->successResponse =  __("Settings successfully saved!", 'spbsm');
    }

   

    //init
    public function create_table()
    {
        $sql = array();
        $sql[] = "CREATE TABLE $this->table_settings (
            	id TINYINT NOT NULL AUTO_INCREMENT,
                class LONGTEXT NOT NULL,
                follow TINYINT NOT NULL DEFAULT 0,
                follow_url LONGTEXT DEFAULT NULL,
                follow_queue TINYINT NOT NULL DEFAULT 0,
                share TINYINT NOT NULL DEFAULT 0,
                share_queue TINYINT NOT NULL DEFAULT 0,
                PRIMARY KEY (id)
            	);";

        $insertQ = "INSERT INTO $this->table_settings (id,class) VALUES ";
        $i = 1;
        $count = count($this->medias);
        foreach ($this->medias as &$item) {
            $insertQ .= "(".$item['id'].",'".$item['class']."')";
            $insertQ .= $i == $count ? 'ON DUPLICATE KEY UPDATE id=id;':', ';
            $i++;
        }
        $sql[] = $insertQ;
   
        $sql[] = "CREATE TABLE $this->table_positionSettings (
        		  id TINYINT NOT NULL AUTO_INCREMENT,
        		  posts_addAtStart TINYINT NOT NULL DEFAULT 0,
        		  posts_addAtEnd TINYINT NOT NULL DEFAULT 0,
                  pages_addAtStart TINYINT NOT NULL DEFAULT 0,
                  pages_addAtEnd TINYINT NOT NULL DEFAULT 0,
        		  floatingSidebar TINYINT NOT NULL DEFAULT 0,
                  floatingSidebar_hideOnMobile TINYINT NOT NULL DEFAULT 0,
        		  PRIMARY KEY (id)
        		  );";
        $sql[] = "INSERT INTO $this->table_positionSettings (id) VALUES (1),(2) ON DUPLICATE KEY UPDATE id=id;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        add_option('spbsm_db_version', $this->db_version);
        update_option('spbsm_db_version', $this->db_version);
    }

    //getters
    public function getMediaList($selection)
    {
        if ($selection==1) {
            $arr = array();
            foreach ($this->medias as &$media) {
                if ($media['share']!='') {
                    $arr[$media['id']-1] = $media;
                }
            }
            return $arr;
        }

        if ($selection==2) {
            return $this->medias;
        }
    }

    //selects
    public function get_settings($selection)
    {
        $orderColumn = $selection== 1 ? 'share_queue' : 'follow_queue';
        $query = "SELECT * FROM $this->table_settings ORDER BY $orderColumn ASC";
        $results = $this->db->get_results($query, ARRAY_A);
        if ($results) {
            if ($selection == 1) {
                $arr = $this->getMediaList(1);
                foreach ($results as &$value) {
                    foreach ($arr as &$media) {
                        if ($value['id'] == $media['id']) {
                            $media = $value;
                        }
                    }
                }
                $results = $arr;
                usort($results, function (&$first, &$second) {
                    return $first['share_queue'] > $second['share_queue'];
                });
            } else {
                $arr = $this->getMediaList(2);
                foreach ($results as &$value) {
                    foreach ($arr as &$media) {
                        if ($value['id'] == $media['id']) {
                            if (isset($media['alt-name'])) {
                                $value['alt-name'] = $media['alt-name'];
                            }
                            if (isset($media['alt-link'])) {
                                $value['alt-link'] = $media['alt-link'];
                            }
                            
                            $media = $value;
                        }
                    }
                }
                $results = $arr;
            }
            return $results;
        }
        return false;
    }

    public function get_positionSettings($selection)
    {
        $query = "SELECT * FROM $this->table_positionSettings WHERE `id`=".$selection.";";
        $results = $this->db->get_results($query, ARRAY_A);
        if ($results) {
            return $results[0];
        }
        return false;
    }

    public function getSidebarSettings()
    {
        $query = "SELECT `floatingSidebar` AS `sidebar`, `floatingSidebar_hideOnMobile` AS `hide` FROM $this->table_positionSettings;";
        $results = $this->db->get_results($query, ARRAY_A);
        if ($results) {
            return $results[0]['sidebar'] == 0 && $results[1]['sidebar'] == 0 ? false : $results;
        }
        return false;
    }

    public function getShareButtons()
    {
        $query = "SELECT id,class FROM $this->table_settings WHERE `share`=1 ORDER BY `share_queue` ASC;";
        $results = $this->db->get_results($query, ARRAY_A);
        if ($results) {
            foreach ($results as &$value) {
                foreach ($this->getMediaList(1) as &$media) {
                    if ($value['id'] == $media['id']) {
                        $value['icon'] = $media['icon'];
                        $value['share'] = $media['share'];
                    }
                }
            }
            return $results;
        }
        return false;
    }

    public function getFollowButtons()
    {
        $query = "SELECT id,class,follow_url FROM $this->table_settings WHERE `follow`=1 AND (`follow_url` != '' AND `follow_url` IS NOT NULL) ORDER BY `follow_queue` ASC;";
        $results = $this->db->get_results($query, ARRAY_A);
        if ($results) {
            foreach ($results as &$value) {
                foreach ($this->medias as &$media) {
                    if ($value['id'] == $media['id']) {
                        $value['icon'] = $media['icon'];
                        if (isset($media['alt-name'])) {
                            $value['alt-name'] = $media['alt-name'];
                        }
                    }
                }
            }
            return $results;
        }
        return false;
    }

    //ajax calls
    public function update_share($data)
    {
        try {
            $this->db->query('start transaction;');
            foreach ($this->getMediaList(1) as &$item) {
                $this->db->query(
                    "UPDATE `$this->table_settings` SET 
                `share` = ".$data[$item['class']]['share'].", 
                `share_queue` = ".$data[$item['class']]['share_queue']." 
        		WHERE `id`=".intval($item['id']).";"
                );
            }

            foreach ($data['general'] as $key => $value) {
                $this->db->query("UPDATE `$this->table_positionSettings` SET `".$key."` = ".$value." WHERE `id`=1; ");
            }

            $this->db->query("UPDATE `$this->table_positionSettings` SET `floatingSidebar` = ".$data['floatingSidebar']." WHERE `id`=1; ");

            if ($this->db->last_error != '') {
                $this->db->query("rollback;");
                return ['error',$this->sqlErrorResponse];
            }
            $this->db->query("commit;");
            return ['success',$this->successResponse];
        } catch (Exception $ex) {
            $this->db->query("rollback;");
            return ['error',$this->sqlErrorResponse];
        }
    }

    public function update_follow($data)
    {
        try {
            $this->db->query('start transaction;');
            foreach ($this->medias as &$item) {
                $url = $data[$item['class']]['follow_url'];
                $active = empty($url) ? 0 : $data[$item['class']]['follow'];
                $queue = $data[$item['class']]['follow_queue'];
                $queue = $queue == null ? 0 : $queue;
                $this->db->query(
                    "UPDATE `$this->table_settings` SET 
                    `follow` = ".$active.", 
                    `follow_url` = '".$url."', 
                    `follow_queue` = ".$queue." 
                    WHERE `id`=".intval($item['id']).";"
                );
            }

            foreach ($data['general'] as $key => $value) {
                $this->db->query("UPDATE `$this->table_positionSettings` SET `".esc_sql($key)."` = ".$value." WHERE `id`=2; ");
            }

            $this->db->query("UPDATE `$this->table_positionSettings` SET `floatingSidebar` = ".$data['floatingSidebar']." WHERE `id`=2; ");

            if ($this->db->last_error != '') {
                $this->db->query("rollback;");
                return ['error',$this->sqlErrorResponse];
            }
            $this->db->query("commit;");
            return ['success',$this->successResponse];
        } catch (Exception $ex) {
            $this->db->query("rollback;");
            return ['error',$this->sqlErrorResponse];
        }
    }

    ///rollback plugin
    public function drop_table()
    {
        $query = "DROP TABLE $this->table_settings;
                  DROP TABLE $this->table_positionSettings";
        return $this->db->query($query);
    }
}
