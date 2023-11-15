<?php

require_once(ULTIMATEMEMBER_CUSTOM__PLUGIN_DIR . '/lib/admin/users.php');
UltimateMemberCustom_Admin_Users::init();
// init modal info user 
require_once(ULTIMATEMEMBER_CUSTOM__PLUGIN_DIR . '/lib/admin/template/modal/info.php');
UltimateMemberCustomAdmin_Modal_Info::init();
