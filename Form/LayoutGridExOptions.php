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