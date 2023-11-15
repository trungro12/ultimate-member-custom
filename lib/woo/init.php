<?php
require_once(ULTIMATEMEMBER_CUSTOM__PLUGIN_DIR . '/lib/woo/myaccount.php');
UltimateMemberCustom_Woo_MyAccount::init();

require_once(ULTIMATEMEMBER_CUSTOM__PLUGIN_DIR . '/lib/woo/coupons.php');
UltimateMemberCustom_Woo_Coupons::init();

require_once(ULTIMATEMEMBER_CUSTOM__PLUGIN_DIR . '/lib/woo/template/search/seachform.php');
UltimateMemberCustom_SearchForm::init();
