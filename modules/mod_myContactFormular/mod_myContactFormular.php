<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

// the following 3 Constants already defined
//define('_JEXEC', 1);
//define('DS', DIRECTORY_SEPARATOR);
//define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT'] . DS . '');
/*
require_once (JPATH_BASE . DS . 'includes' . DS . 'defines.php');
require_once (JPATH_BASE . DS . 'includes' . DS . 'framework.php');
require_once (JPATH_BASE . DS . 'libraries' . DS . 'joomla' . DS . 'factory.php');
*/
if (!defined('_JEXEC'))
	define('_JEXEC', 1);
if (!defined('JPATH_BASE'))
define('JPATH_BASE', '../..' );

require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );

require_once (JPATH_BASE . '/libraries/myPHPMailer/CorfuByCar-email_configurations.php');	// extra, required this file
//require_once (JPATH_BASE . '/modules/mod_myContactFormular/captcha.php');	// extra, required this file

//require_once (JPATH_BASE . '/modules/mod_myContactFormular/style.css');	// extra, required this file


// Text Parameters
$required_fields_notice = $params->get('required_fields_notice', '');
require(JModuleHelper::getLayoutPath('mod_myContactFormular'));

JHtml::stylesheet(JUri::base() . 'modules/mod_myContactFormular/style.css');	
JHtml::script(JUri::base() . 'modules/mod_myContactFormular/script.js');

	
?>