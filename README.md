# Layout Grid Ex
Layout Grid Example  - пример макета для NWICODECMS.

Для удобства изучения мы создали минималистичный макет, в котором постараемся разобрать работу с классом HomepageLayout, а также работу с формой настроек макета.

Структура макета похожа на структуру обычного модуля, но имеет несколько ключевых особенностей:
файл **bootstrap.php**, в котором модуль подключается именно как макет и подключает свою форму настроек:
```php
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
```
Подключение происходит коммандой Nwicode_Feature::registerLayoutOptionsCallbacks(....), при выборе макета будет показана его форма настроек, находящаяся в **LayoutGridEx_Form_LayoutGridExOptions** (папка **/app/local/modules/LayoutGridEx/Form/LayoutGridExOptions**):

```php
<?php
class LayoutGridEx_Form_LayoutGridExOptions extends Nwicode_Form_Options_Abstract {


    public function init() {
        parent::init();

		$this->setAction(__path("/layoutgridex/application/formoptions"))
         ->setAttrib("id", "form-options");

        /** Bind as a create form */
        self::addClass("create", $this);
        self::addClass("form-layout-options", $this);

       $this->setDisplayGroupDecorators(array(
            'FormElements',
            'Fieldset'
        ));
		$this->addSimpleText("text_1", __("Title")); 
		$this->addSimpleText("text_2", __("Subtitle")); 
		$this->addNav("submit", __("Save"), false, false);
		self::addClass("btn-sm", $this->getDisplayGroup("submit")->getElement(__("Save")));

    }

	public function populate($values)
	{
		
		//Построим список приложений
		$db =Zend_Db_Table_Abstract::getDefaultAdapter();
		$stmt = $db->query(
			'SELECT a.*, b.* FROM application_option_value a, application_option b WHERE a.app_id = ? and a.option_id = b.option_id',
			array($values['app_id'])
		);
		$options_src = $stmt->fetchAll();
		$options[0]="-";
		foreach ($options_src as $o) {
			if ($o['tabbar_name']!='') $options[$o['value_id']]=$o['tabbar_name']  . " (" . $o['name'] . ")"; else $options[$o['value_id']]=$o['name'];
		}
		//Default settings
		if (!isset($values["text_1"]))  $values["slogan_text_1"]="Layout"; 
		if (!isset($values["text_2"]))  $values["slogan_text_2"]="Grid Ex"; 

		parent::populate($values);
	}
 
}

?>
```
В методе init() форма создается, в методе populate происходит заполнение формы пользовательскими данными, которые не могут быть заполнены в init (например, переназначенными значениями)).

Как видно, в самом начале формы задается ее обработчик **/layoutgridex/application/formoptions** , который находится в** /app/local/modules/LayoutGridEx/controllers/ApplicationController.php**

Сабмит этой формы происходит в данный контроллер в метод formoptionsAction() - форма сохраняет свои настройки. При необходимости, функционал контроллера можно расширить (например для загрузки картинок или обработки richdata).
