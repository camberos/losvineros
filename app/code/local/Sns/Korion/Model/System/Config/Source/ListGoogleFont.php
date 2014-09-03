<?php

class Sns_Korion_Model_System_Config_Source_ListGoogleFont
{
	public function toOptionArray()
	{
		return array(
			array('value'=>'', 'label'=>Mage::helper('korion')->__('No select')),
			array('value'=>'Anton', 'label'=>Mage::helper('korion')->__('Anton')),
			array('value'=>'Roboto+Condensed', 'label'=>Mage::helper('korion')->__('Roboto Condensed')),
			array('value'=>'Port+Lligat+Slab', 'label'=>Mage::helper('korion')->__('Port Lligat Slab')),
			array('value'=>'Questrial', 'label'=>Mage::helper('korion')->__('Questrial')),
			array('value'=>'Kameron', 'label'=>Mage::helper('korion')->__('Kameron')),
			array('value'=>'Oswald', 'label'=>Mage::helper('korion')->__('Oswald')),
			array('value'=>'Open+Sans:400,300,600,700,800', 'label'=>Mage::helper('korion')->__('Open Sans')),
			array('value'=>'Open+Sans+Condensed:300,300italic,700', 'label'=>Mage::helper('korion')->__('Open Sans Condensed')),
			array('value'=>'BenchNine', 'label'=>Mage::helper('korion')->__('BenchNine')),
			array('value'=>'Droid Sans', 'label'=>Mage::helper('korion')->__('Droid Sans')),
			array('value'=>'Droid Serif', 'label'=>Mage::helper('korion')->__('Droid Serif')),
			array('value'=>'PT Sans', 'label'=>Mage::helper('korion')->__('PT Sans')),
			array('value'=>'Vollkorn', 'label'=>Mage::helper('korion')->__('Vollkorn')),
			array('value'=>'Ubuntu', 'label'=>Mage::helper('korion')->__('Ubuntu')),
			array('value'=>'Neucha', 'label'=>Mage::helper('korion')->__('Neucha')),
			array('value'=>'Cuprum', 'label'=>Mage::helper('korion')->__('Cuprum'))
		);
	}
}
