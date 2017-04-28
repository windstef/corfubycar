<?php

if (!defined('_VALID_MOS') && !defined('_JEXEC'))
    die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');

/**
 *
 * @package  VehicleManager
 * @copyright 2013 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Rob de Cleen(rob@decleen.com);
 * Homepage: http://www.ordasoft.com
 * @version: 3.2 Pro 
 * */
$mainframe = $GLOBALS['mainframe'] = JFactory::getApplication(); // for 1.6


if (stristr($_SERVER['PHP_SELF'], 'administrator'))
{
    @define('_VM_IS_BACKEND', '1');
}
defined('_VM_TOOLBAR_LOADED') or define('_VM_TOOLBAR_LOADED', 1);

include_once( JPATH_ROOT . "/components/com_vehiclemanager/compat.joomla1.5.php" );



/* require_once( $mainframe->getPath( 'toolbar_html' ) );
  require_once( $mainframe->getPath( 'toolbar_default' ) ); */
// for 1.6
$path = JPATH_SITE . "/administrator/components/com_vehiclemanager/";
//require_once( $path . 'toolbar.vehiclemanager.php' );
require_once( $path . 'toolbar_ext.php' );
require_once( $path . 'toolbar.vehiclemanager.html.php' );
require_once ( JPATH_ROOT . "/components/com_vehiclemanager/functions.php" );

// --

$database = JFactory::getDBO();
if (version_compare(JVERSION, "3.0.0", "ge"))
{
// load language
    $languagelocale = "";
    $database->setQuery("SELECT title, lang_code FROM #__vehiclemanager_languages");
    $languages = $database->loadObjectList();

    $lang = JFactory::getLanguage();
    foreach ($languages as $language) {
        if($lang->getTag() == $language->lang_code){
               $mosConfig_lang = $language->lang_code;
               $languagelocale = $language->lang_code;
               break; 
        }
    }   
        
    if ($languagelocale == ''){   
    foreach ($lang->getLocale() as $locale) {
        foreach ($languages as $language) {
            if ($locale == $language->title || $locale == $language->lang_code) 
            {
                $mosConfig_lang = $locale;
                $languagelocale = $language->lang_code;
                break;
            }
        }
       }
    }
    
    if ($languagelocale == '')
        $languagelocale = "en-GB";

    $query = "SELECT c.const, cl.value_const ";
    $query .= "FROM #__vehiclemanager_const_languages as cl ";
    $query .= "LEFT JOIN #__vehiclemanager_languages AS l ON cl.fk_languagesid=l.id ";
    $query .= "LEFT JOIN #__vehiclemanager_const AS c ON cl.fk_constid=c.id ";
    $query .= "WHERE l.lang_code = '$languagelocale'";
    $database->setQuery($query);
    $langConst = $database->loadObjectList();

    foreach ($langConst as $item) {
        define($item->const, $item->value_const);
    }
}
//
$section = mosGetParam($_REQUEST, 'section', 'courses');

if (version_compare(JVERSION, "3.0.0", "ge"))
    if (isset($_REQUEST['task']))
    {
        $task = $_REQUEST['task'];
    } else
    {
        $task = '';
    }

if (isset($section) && $section == 'categories')
{
    switch ($task) {
        //case "new":
        case "add":
            menucat::NEW_CATEGORY();
            vmLittleThings::addSubmenu("Categories");
            break;
        case "edit":
            menucat::EDIT_CATEGORY();
            vmLittleThings::addSubmenu("Categories");
            break;
        default:
            menucat::SHOW_CATEGORIES();
            vmLittleThings::addSubmenu("Categories");
            break;
    }
} elseif ($section == 'featured_manager')
{
    switch ($task) {
        case "add":
            menufeaturedmanager::NEW_FEATUREDMANAGER();
            vmLittleThings::addSubmenu("Features Manager");
            break;
        case "edit":
            menufeaturedmanager::EDIT_FEATUREDMANAGER();
            vmLittleThings::addSubmenu("Features Manager");
            break;
        default:
            menufeaturedmanager::MENU_FEATUREDMANAGER();
            vmLittleThings::addSubmenu("Features Manager");
            break;
    }
} elseif ($section == 'language_manager')
{
    switch ($task) {

        case "copy":
            menulanguagemanager::EDIT_LANGUAGEMANAGER();
            vmLittleThings::addSubmenu("Language Manager");
            break;
        case "edit":
            menulanguagemanager::EDIT_LANGUAGEMANAGER();
            vmLittleThings::addSubmenu("Language Manager");
            break;
        default:
            menulanguagemanager::MENU_LANGUAGEMANAGER();
            vmLittleThings::addSubmenu("Language Manager");
            break;
    }
} else
{   
    switch ($task) {
        //case "new":
        case "add":
            menuvehiclemanager::MENU_SAVE_BACKEND();
            vmLittleThings::addSubmenu("Vehicles");
            break;

        case "edit":
            menuvehiclemanager::MENU_EDIT();
            vmLittleThings::addSubmenu("Vehicles");
            break;

        case "rent":
            menuvehiclemanager::MENU_RENT();
            vmLittleThings::addSubmenu("Rent Requests");
            break;

        case "edit_rent":
            menuvehiclemanager::MENU_EDIT_RENT();
            vmLittleThings::addSubmenu("Rent Requests");
            break;

        case "rent_return":
            menuvehiclemanager::MENU_RENT_RETURN();
            vmLittleThings::addSubmenu("Rent Requests");
            break;

        case "rent_requests":
            menuvehiclemanager::MENU_RENTREQUESTS();
            vmLittleThings::addSubmenu("Rent Requests");
            break;

        case "buying_requests":
            menuvehiclemanager::MENU_BUYINGREQUESTS();
            vmLittleThings::addSubmenu("Sale Manager");
            break;

        case "config":
            menuvehiclemanager::MENU_CONFIG();
            vmLittleThings::addSubmenu("Settings");
            break;

        case "config_save":
            menuvehiclemanager::MENU_CONFIG();
            vmLittleThings::addSubmenu("Settings");
            break;

        case "about":
            menuvehiclemanager::MENU_ABOUT();
            vmLittleThings::addSubmenu("About");
            break;
        
        case "delete_review":
            menuvehiclemanager::MENU_DELETE_REVIEW();
            vmLittleThings::addSubmenu("Vehicles");
            break;

        case "edit_review":
            menuvehiclemanager::MENU_EDIT_REVIEW();
            vmLittleThings::addSubmenu("Vehicles");
            break;

        case "update_review":
            menuvehiclemanager::MENU_EDIT();
            vmLittleThings::addSubmenu("Vehicles");
            break;

        case "cancel_review_edit":
            menuvehiclemanager::MENU_EDIT();
            vmLittleThings::addSubmenu("Vehicles");
            break;

        default: 
            menuvehiclemanager::MENU_DEFAULT();
            vmLittleThings::addSubmenu("Vehicles");
            break;
    }
}

