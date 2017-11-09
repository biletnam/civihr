<?php

class CRM_HRCore_Listener_Form_Admin_Localization extends CRM_HRCore_Listener_AbstractListener {

  protected $objectClass = 'CRM_Admin_Form_Setting_Localization';

  public function onBuildForm() {
    if (!$this->canHandle()) {
      return;
    }

    $this->object->removeElement('makeMultilingual');
  }
}
