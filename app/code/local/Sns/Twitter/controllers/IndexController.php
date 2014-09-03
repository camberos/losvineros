<?php

class Sns_Twitter_IndexController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {
	  $this->loadLayout();
      $this->renderLayout();
    }
}