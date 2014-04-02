<?php
/**
 * Crucible functions and definitions
 * @package Crucible
 */
/*-----------------------------------------------------------------------------------*/
/* Please refrain from editing this section */
/*-----------------------------------------------------------------------------------*/

include( dirname( __FILE__ ) . '/inc/license-handler.php' );
// Smartest Themes Business Framework
require_once (TEMPLATEPATH . '/business-framework/admin-init.php'); 
// Set path to theme specific functions
$incpath = TEMPLATEPATH . '/inc/';
// Theme specific functionality
require_once ($incpath . 'theme-options.php'); 	// Options panel settings
require_once ($incpath . 'theme-functions.php'); 	// theme functions
require_once ($incpath . 'theme-enqueue.php');	// Load javascript and styles
require_once ($incpath . 'fontface.php');		// Load font stylesheets
/*-----------------------------------------------------------------------------------*/
/* End Crucible Theme Functions - You can add custom functions below, or better yet, place them in a child theme. */
/*-----------------------------------------------------------------------------------*/