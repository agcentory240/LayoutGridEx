<?php
$defined_options = array(
	"text_1" => "Demo<br>Company",
	"text_2" => "Company<br>Introduction",
);

$default_options = Nwicode_Json::encode($defined_options);




$datas = array(
    'name' => 'Layout Grid Ex',
    'visibility' => Application_Model_Layout_Homepage::VISIBILITY_HOMEPAGE,
    'code' => 'layout_grid_ex',
    'preview' => '/customization/layout/homepage/layout_grid_ex.png',
	'preview_new' => '/customization/layout/homepage/layout_grid_ex_modal.png',
    'use_more_button' => 1,
    'use_horizontal_scroll' => 0,
    'number_of_displayed_icons' => 198,
    'position' => "bottom",
    "order" => 1201,
    "can_uninstall" => 1,
    "is_active" => 1,
	'options' => $default_options
);

$layout = new Application_Model_Layout_Homepage();
$layout
    ->setData($datas)
    ->insertOrUpdate(array("code"));

Nwicode_Assets::copyAssets("/app/local/modules/LayoutGridEx/resources/var/apps/");