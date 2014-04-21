<?php
/**
 * Crucible functions
 * @package Crucible
 */
/*-----------------------------------------------------------------------------------*/
/* Please refrain from editing this file. 
 * Place your custom functions in a child theme. See http://smartestthemes.com/docs/how-to-customize-without-losing-my-customizations-when-i-update-5/
 *
 */
include dirname( __FILE__ ) . '/inc/updater.php';
// Smartest Themes Business Framework
require_once (TEMPLATEPATH . '/business-framework/admin-init.php'); 
// Theme specific functionality
$incpath = TEMPLATEPATH . '/inc/';
require_once ($incpath . 'options.php');
require_once ($incpath . 'theme-functions.php');
require_once ($incpath . 'enqueue.php');
require_once ($incpath . 'fontface.php');