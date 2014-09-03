<?php
/*------------------------------------------------------------------------
 * Copyright (C) 2013 The SNS Theme. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: SNS Theme
 * Websites: http://www.snstheme.com
-------------------------------------------------------------------------*/
/*--- BEGIN: Theme param config ---*/
$params = new ThemeParameter();
$config = array(
	'showCpanel'				=>'1',
	'showTooltip'				=>'1',
	'fontSize'					=>'12px',
	'fontFamily'				=>'arial',
	'themeColor'				=>'black',
	'layoutType'				=>'1',
	'useSnowFall'				=>'1',
	'menuStyles'				=>'css',
	'keepMenu'					=>'1',
	'resMenu'					=>'sidebar',
	'bodyBgColor'				=>'#ffffff',
	'googleFont'				=>'',
	'googleFontTargets'			=>'',
	'scrollToTop'				=>'',
	'use_bgoverlay'				=>'',
	'heightItemInfo'			=>''
);
// Array param for cookie
$paramscookie =array(
					'fontSize',
					'fontFamily',
					'themeColor',
					'useSnowFall',
					'menuStyles',
					'keepMenu',
					'bodyBgColor',
					'bodyTextColor'
);

if(Mage::getConfig()->getNode('modules/Sns_Korion')){
	$config	=	Mage::helper('korion/data')->get($attributes);
}
// enable Cpanel
$params->set('showCpanel',$config['showCpanel']);
// enable showTooltip
$params->set('showTooltip',$config['showTooltip']);
// Fontsize
$params->set('fontSize',$config['fontSize']);
// font family
$params->set('fontFamily',$config['fontFamily']);
// Google web font
$params->set('googleWebFont',$config['googleWebFont']);
// Google WebFont Targets
$params->set('googleWebFontTargets',$config['googleWebFontTargets']);
// Theme color
$params->set('themeColor',$config['themeColor']);
//
$params->set('layoutType',$config['layoutType']);
// Using snow fall, only apply for themeColor: Christmas
$params->set('useSnowFall',$config['useSnowFall']);
// Menu styles
$params->set('menuStyles', $config['menuStyles']);
// Keep menu on top
$params->set('keepMenu', $config['keepMenu']);
// Res menu
$params->set('resMenu', $config['resMenu']);
// Body background-color
$params->set('bodyBgColor', $config['bodyBgColor']);
// Body background-color
$params->set('googleFont', $config['googleFont']);
// Body text color
$params->set('googleFontTargets', $config['googleFontTargets']);
//
$params->set('scrollToTop', $config['scrollToTop']);
//
$params->set('use_bgoverlay', $config['use_bgoverlay']);
//
$params->set('heightItemInfo', $config['heightItemInfo']);
/*--- END: Theme param config ---*/
global $var_snstheme;
$var_snstheme = new SNSTheme('sns_korion', $params, $paramscookie);
?>


