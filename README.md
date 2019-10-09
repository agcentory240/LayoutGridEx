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

Как видно, в самом начале формы задается ее обработчик **/layoutgridex/application/formoptions** , который находится в **/app/local/modules/LayoutGridEx/controllers/ApplicationController.php**

Сабмит этой формы происходит в данный контроллер в метод formoptionsAction() - форма сохраняет свои настройки. При необходимости, функционал контроллера можно расширить (например для загрузки картинок или обработки richdata). В нашем случае мы сохраняем переменные text_1 и text_2, которые будем использовать в макете.

------------
При установке макета, макет должен прописать свои данные в системные таблицы. Мы это делаем через файл **resources/db/data/layout_grid_ex.php** :
```php
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
    'use_more_button' => 0,
    'use_horizontal_scroll' => 0,
    'number_of_displayed_icons' => 198,
    'position' => "bottom",
    "order" => 1201,
    "can_uninstall" => 1,
    "is_active" => 1,
	'options' => $default_options
);

$layout = new Application_Model_Layout_Homepage();
$layout->setData($datas)    ->insertOrUpdate(array("code"));
Nwicode_Assets::copyAssets("/app/local/modules/LayoutGridEx/resources/var/apps/");
```
Так же , мы прописали начальные значения полей text_1 и text_2. 
Значения **use_more_button**, **number_of_displayed_icons** и **use_horizontal_scroll** работают, но являются устаревшими. Мы рекомендуем все рассчеты, а также управление выводом делать именно в самом макете.
Значения **preview** и **preview_new** должны содержать путь к примерным обложкам макета, которые будут показаны в списке макетов. Они должны находиться в папке **resources/design/desktop/flat/images/customization/layout/homepage**

------------

Файлы переводов работают так же, как и в модулях - в папке translations/default находятся csv и lst файл переводов.
layout_grid_ex.csv соджержит все фразы и их переводы, а в mobile.lst те фразы, переводы которых необходимо вынести в приложение.

------------
### Макет
Макет должен состоять из двух файлов view.html (та часть, что выводится на экран), style.css (необязательный) и hook.js (обработчик макета). Файлы макеты должны находиться в папке **resources/var/apps/modules/layout/home/layout_grid_ex/**, они загружаются автоматически при старте приложения.
В hook.js обязательно создать сервис, который будет выполняться при подготовке к запуску макета:
```javascript
/**
 *
 * Layout_Grid_Ex example
 *
 * All the following functions are required in order for the Layout to work
 */
App.service('layout_grid_ex', function ($rootScope, HomepageLayout) {

    var service = {};

    /**
     * Must return a valid template
     *
     * @returns {string}
     */
    service.getTemplate = function() {
        return "modules/layout/home/layout_grid_ex/view.html";
    };

    /**
     * Must return a valid template
     *
     * @returns {string}
     */
    service.getModalTemplate = function() {
        return "templates/home/l10/modal.html";
    };

    /**
     * onResize is used for css/js callbacks when orientation change
     */
    service.onResize = function() {
        /** Do nothing for this particular one */
    };

    /**
     * Manipulate the features objects
     *
     * Examples:
     * - you can re-order features
     * - you can push/place the "more_button"
     *
     * @param features
     * @param more_button
     * @returns {*}
     */
    service.features = function(features, more_button) {
        /** Place more button at the end */
        features.overview.options.push(more_button);

        return features;
    };

    return service;

});
```
Так же, если макет должен обладать какой то логикой (как в нашем примере), то прописать дополнительные задачи, которые необходимо совершить при старте макета (у нас это запуск контроллера **agc242templatecontroller**), в который и обернут наш макет, находящийся в view.html.

```javascript

App.controller('agc242templatecontroller', function($scope,Url,$ionicSideMenuDelegate,$rootScope, $timeout, $translate, $location, $compile, $sce, $window, Application, Customer,   Dialog,  HomepageLayout, $log, $http)
{
	$scope.is_loading = true;
	$scope.is_logged_in = Customer.isLoggedIn();
	$scope.avatar_url = null;
	$scope.options = {};
	$scope.features_all = [];
	$scope.features_options = [];
	HomepageLayout.getFeatures().then(function(features) {
		console.log("Get layout options:");
		console.log(features);
		$scope.options = features.layoutOptions;
		features.options.forEach(function(element) {
			if (element.is_visible) $scope.features_all.push(element);
		});
		$scope.features_options = $scope.chunkArray($scope.features_all,3);
		console.log($scope.features_options);
	});
	
	
	
	$scope.chunkArray = function(myArray, chunk_size){
		var index = 0;
		var arrayLength = myArray.length;
		var tempArray = [];
		
		for (index = 0; index < arrayLength; index += chunk_size) {
			myChunk = myArray.slice(index, index+chunk_size);
			// Do something if you want with the group
			tempArray.push(myChunk);
		}

		return tempArray;
	}
	
	$scope.openFeature = function(feature) {
		console.log("openFeature clicked:");
		console.log(feature);
		HomepageLayout.openFeature(feature, $scope);

	};	
	
});
```

Так как мы выводим иконки в три столбца, то нам удобнее сначала сформировать массив нужных модулей (учтите, что некотрые модули имет атрибут is_visible=false), после чего разбить его на части по три модуля в каждой.
Еще мы прописали свою функицию модуля.

Все настройки макета находятся в объекте HomepageLayout в layoutOptions. Мы назначаем их нашему массиву $scope.options = features.layoutOptions; после чего уже в шаблоне обращаемся к нашим данным так:
`<ion-text color="primary" text-right ng-bind-html="options.text_1"></ion-text>`
и так 
`<div class="title"><ion-text color="primary">{{item.name}}<ion-text></div>`
согласно правилам AngularJS.

Сам список модулей мы выводим двумя циклами из нашего массива массивов модулей **$scope.features_options**:
```html
	  <ion-row ng-repeat="row in features_options">
		<ion-col ng-repeat="item in row" text-center>
			<div class="item_feature" ng-click="openFeature(item)">
				<img src="{{item.icon_url}}" ng-src="{{item.icon_url}}">
				<div class="title"><ion-text color="primary">{{item.name}}<ion-text></div>
			</div>
		</ion-col>
	  </ion-row>
```

В силу простоты макета мы не использовали файл стилей style.css, а указали стили в самом макете.




