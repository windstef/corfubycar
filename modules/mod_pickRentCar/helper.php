<?php
/**
 * Helper class for Hello World! module
 * 
 * @package    Joomla.Tutorials
 * @subpackage Modules
 * @link http://docs.joomla.org/J3.x:Creating_a_simple_module/Developing_a_Basic_Module
 * @license        GNU/GPL, see LICENSE.php
 * mod_helloworld is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
 
if (!defined('_JEXEC'))
	define('_JEXEC', 1);
if (!defined('JPATH_BASE'))
define('JPATH_BASE', '../..' );

require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );

 
class modPickRentCarHelper
{
    /**
     * initialises the car form
     * with the car categories select form
     * @param array $params An object containing the module parameters
     * @access public
     */    
public static function getCarForm( $params ) {	
	$db = JFactory::getDBO();
	$query = $db->getQuery(true);
    $query = "SELECT * FROM #__vehiclemanager_main_categories";
    $db->setQuery($query);
    $rows = $db->loadObjectList();
    $num = count($rows);
	$categForm = '<div><b>Book a car</b></div>';
	$categForm .= '<form id="bookCarForm" action="" method="post"><div>
				<select id="categCarList" onchange="showCategCars(this.value)">
				<option class="selectPrompt" value="">Select category:</option>';	//<label>Select the car category:</label>
  
	if ($num > 0) {
		for($i=$num-1; $i>=0; $i--) {
		$categ_startPrice = $rows[$i]->title . ": from &#8364;" . $rows[$i]->startPrice . " / day";
			$categForm .= '<option value="' . $rows[$i]->title . '">' . $categ_startPrice . '</option>';
		}
	}

	$categForm .= '</select></div>
					<div id="carSelect"></div></form>';

	return $categForm;
}

    /**
     * updates the car form
     * with the car select form and submit button
     * @access public
     */    
public static function updateCarForm( $q, $baseUrl ) {
	$db = JFactory::getDBO();
	$query = $db->getQuery(true);
    $query = "SELECT mc.id AS mcId, mc.name AS mcCatName, v.id AS vehId, v.vtitle AS vehTitle ";
	$query .= "FROM #__vehiclemanager_main_categories AS mc ";
	$query .= "LEFT JOIN #__vehiclemanager_categories AS c ON c.idcat=mc.id ";
	$query .= "LEFT JOIN #__vehiclemanager_vehicles AS v ON v.id=c.iditem ";
	$query .= "WHERE mc.title = '$q'";
    $db->setQuery($query);
    $rows = $db->loadObjectList();	
    $num = count($rows);
	
	$carForm = 	'<select id="carList" onchange="changeFormAction(this.value)">
	<option class="selectPrompt" value="">Select car type:</option>';	//<label>Select a car:</label>
  
	if ($num > 0) {
	//path http://localhost/CMS_frameworks/corfubycar/car-gallery/172/view_vehicle/48/B-category/13/hyundai-i10
		for($i=$num-1; $i>=0; $i--) {
			$bookCarURL = $baseUrl . "/car-gallery/172/view_vehicle/";
			$vehicleTitle = str_replace(' ', '-', strtolower($rows[$i]->vehTitle));	//convert e.g. Kia Picanto to kia-picanto for right url
			$bookCarURL .= $rows[$i]->mcId . '/' . $rows[$i]->mcCatName . '/' . $rows[$i]->vehId . '/' . $vehicleTitle;
			$carForm .= '<option value="' . $bookCarURL . '">' . $rows[$i]->vehTitle . '</option>';
		}
	}
	
	$carForm .= '</select></br>
				<input type="submit" name="submit" id="carGoBtn" value="Go">'; // onmouseover="changeColor(this)" onmouseout="resetColor(this)">';

	echo $carForm;
}

}

//modify the returned url http://localhost/CMS_frameworks/corfubycar/modules/mod_pickRentCar/ to the home path
$homePath = str_replace('/modules/mod_pickRentCar/', '', JURI::base());

if(!empty($_REQUEST['q'])) {
	modPickRentCarHelper::updateCarForm($_REQUEST['q'], $homePath);
}

?>