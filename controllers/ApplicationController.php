<?php

class LayoutGridEx_ApplicationController extends Application_Controller_Default
{
  
  protected $_logger;

  // controller actions that trigger clearing of cache
  public $cache_triggers = array(
                            "formoptions" => array(
                                "tags" => array("app_#APP_ID#")
                              )
                            );


  // edit module option via nwicode
  public function formoptionsAction()
  { 
  
	if ($datas = $this->getRequest()->getPost()) {
		
      $application = $this->getApplication();
      $layout_id = $application->getLayoutId();
      $layout_model = new Application_Model_Layout_Homepage();
      $layout = $layout_model->find($layout_id);
      $layout_code = 'layout_grid_ex';

      if ($options = Nwicode_Feature::getLayoutOptionsCallbacks($layout_code)) {
        $options = Nwicode_Feature::getLayoutOptionsCallbacks($layout_code);
        $form_class = $options["form"];
        $form = new $form_class($layout);
      } else {
        $form = new Nwicode_Form_Options($layout);
      }


		
		$application->setLayoutOptions(Nwicode_Json::encode($datas,JSON_UNESCAPED_UNICODE));
		$application->save();

		$html = array(
		"success" => 1,
		"message" => __("Options saved"),

		);
      } else {
        $html = array(
          "error" => 1,
          "message" => $form->getTextErrors(),
          "errors" => $form->getTextErrors(true)
        );
      }

      $this->_sendHtml($html);
  }
}
