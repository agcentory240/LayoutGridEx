<?php 
class LayoutGridEx_Bootstrap {

    public static function init($bootstrap) {
        # Register assets
        Nwicode_Assets::registerAssets("LayoutGridEx", "/app/local/modules/LayoutGridEx/resources/var/apps/");
        Nwicode_Assets::addJavascripts(array(
            "modules/layout/home/layout_grid_ex/hooks.js",
        ));
        Nwicode_Assets::addStylesheets(array(
            "modules/layout/home/layout_grid_ex/style.css",
        ));

		
		Nwicode_Feature::registerLayoutOptionsCallbacks("layout_grid_ex", "LayoutGridEx_Form_LayoutGridExOptions", function($datas) {
			$options = array();

			return $options;
		});
	}
}
