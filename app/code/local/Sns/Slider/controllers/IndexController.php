<?php

class Sns_Slider_IndexController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {
	  $this->loadLayout();
      $this->renderLayout();
    }
}