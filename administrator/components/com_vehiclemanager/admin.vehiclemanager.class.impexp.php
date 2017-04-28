<?php

if (!defined('_VALID_MOS') && !defined('_JEXEC'))
    die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');
/**
 *
 * @package  VehicleManager
 * @copyright 2013 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Rob de Cleen(rob@decleen.com);
 * Homepage: http://www.ordasoft.com
 * @version: 3.2 Pro 
 * Updated on January 2013
 * */
$mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path'];
require_once ($mosConfig_absolute_path . "/components/com_vehiclemanager/vehiclemanager.main.categories.class.php"); // for 1.6
require_once ($mosConfig_absolute_path . "/components/com_vehiclemanager/vehiclemanager.class.feature.php");

function print_vars($obj)
{
    $arr = get_object_vars($obj);
    while (list($prop, $val) = each($arr))
        if (class_exists($val))
            print_vars($val);
        else
            echo "\t $prop = $val\n<br />";
}

function print_methods($obj)
{
    $arr = get_class_methods(get_class($obj));
    foreach ($arr as $method)
        echo "\tfunction $method()\n <br />";
}

if (PHP_VERSION >= 5)
{

    // Emulate the old xslt library functions
    function xslt_create()
    {
        return new XsltProcessor();
    }

    function xslt_process($xsltproc, $xml_arg, $xsl_arg, $xslcontainer = null, $args = null, $params = null)
    {
        // Create instances of the DomDocument class
        $xml = new DomDocument;
        $xsl = new DomDocument;

        // Load the xml document and the xsl template
        $xml->load($xml_arg);
        $xsl->load($xsl_arg);

        // Load the xsl template
        $xsltproc->importStyleSheet($xsl);

        // Set parameters when defined
        if ($params)
            foreach ($params as $param => $value)
                $xsltproc->setParameter("", $param, $value);

        // Start the transformation
        $processed = $xsltproc->transformToXML($xml);

        // Put the result in a file when specified
        if ($xslcontainer)
            return @file_put_contents($xslcontainer, $processed); else
            return $processed;
    }

    function xslt_free($xsltproc)
    {
        unset($xsltproc);
    }

}

class mosVehicleManagerImportExport
{

    /**
     * Imports the lines given to this method into the database and writes a
     * table containing the information of the imported vehicles.
     * The imported vehicles will be set to [not published] 
     * Format: #;id;isbn;title;author;language
     * @param array lines - an array of lines read from the file
     * @param int catid - the id of the category the vehicles should be added to 
     */
    static function importVehiclesCSV($lines, $catid)
    { 
        
        
        global $database;
        $retVal = array();
        $i = 0;
        
        foreach ($lines as $line) {
            $tmp = array();
            if (trim($line) == "") continue;
            
            $line = explode('|', $line);
            $vehicle = new mosVehicleManager($database);
            
            $vehicle->asset_id = $line[0];
            $vehicle->vehicleid = trim($line[1]);
            //$vehicle->catid = trim($line[2]);
            $vehicle->sid = trim($line[3]);
            $vehicle->fk_rentid = trim($line[4]);
            $vehicle->description = $line[5];
            $vehicle->link = $line[6];
            $vehicle->listing_type = $line[7];
                if (($vehicle->listing_type) != ''){
                    $listing_type[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
                    $listing_type[_VEHICLE_MANAGER_OPTION_FOR_RENT] = 1;
                    $listing_type[_VEHICLE_MANAGER_OPTION_FOR_SALE] = 2;
                    $vehicle->listing_type = $listing_type[$vehicle->listing_type];
                }
                else{
                    $vehicle->listing_type = 0;
                }
                
            $vehicle->price = $line[8];
            $vehicle->priceunit = $line[9];
            $vehicle->vtitle = $line[10];
            $vehicle->maker = $line[11];
            $vehicle->vmodel = $line[12];
            $vehicle->vtype = $line[13];
                if (($vehicle->vtype) != ''){
                    $vtype[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
                    $vtype1 = explode(',', _VEHICLE_MANAGER_OPTION_VEHICLE_TYPE);
                    $k = 1;
                    foreach ($vtype1 as $vtype2) {
                        $vtype[$vtype2] = $k;
                        $k++;
                    }
                    $vehicle->vtype = $vtype[$vehicle->vtype];
                }
                else{ $vehicle->vtype = 0; }
                
            $vehicle->vlocation = $line[14];
            $vehicle->vlatitude = $line[15];
            $vehicle->vlongitude = $line[16];
            $vehicle->map_zoom = $line[17];
            $vehicle->contacts = $line[18];
            $vehicle->year = $line[19];
            $vehicle->vcondition = $line[20];
                if (($vehicle->vcondition) != ''){
                    $vcondition[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
                    $vcondition1 = explode(',', _VEHICLE_MANAGER_OPTION_VEHICLE_CONDITION);
                    $k = 1;
                    foreach ($vcondition1 as $vcondition2) {
                        $vcondition[$vcondition2] = $k;
                        $k++;
                    }
                    $vehicle->vcondition = $vcondition[$vehicle->vcondition];
                } else{
                    $vehicle->vcondition = 0;
                }
                
            $vehicle->mileage = $line[21];
            $vehicle->image_link = $line[22];
            $vehicle->listing_status = $line[23];
                if (($vehicle->listing_status) != ''){
                    $listing_status[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
                    $listing_status1 = explode(',', _VEHICLE_MANAGER_OPTION_LISTING_STATUS);
                    $k = 1;
                    foreach ($listing_status1 as $listing_status2) {
                        $listing_status[$listing_status2] = $k;
                        $k++;
                    }
                    $vehicle->listing_status = $listing_status[$vehicle->listing_status];
                } else{ $vehicle->listing_status = 0; }
                
            $vehicle->price_type = $line[24];
                if (($vehicle->price_type) != '')
                {
                    $price_type[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
                    $price_type1 = explode(',', _VEHICLE_MANAGER_OPTION_PRICE_TYPE);
                    $k = 1;
                    foreach ($price_type1 as $price_type2) {
                        $price_type[$price_type2] = $k;
                        $k++;
                    }
                    $vehicle->price_type = $price_type[$vehicle->price_type];
                }
                else{ $vehicle->price_type = 0; }
                    
            $vehicle->transmission = $line[25];
                if (($vehicle->transmission) != ''){
                    $transmission[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
                    $transmission1 = explode(',', _VEHICLE_MANAGER_OPTION_TRANSMISSION);
                    $k = 1;
                    foreach ($transmission1 as $transmission2) {
                        $transmission[$transmission2] = $k;
                        $k++;
                    }
                    $vehicle->transmission = $transmission[$vehicle->transmission];
                }
                else{ $vehicle->transmission = 0; }
                
            $vehicle->num_speed = $line[26];
                if (($vehicle->num_speed) != ''){
                    $num_speed[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
                    $num_speed1 = explode(',', _VEHICLE_MANAGER_OPTION_NUMBER_OF_SPEEDS);
                    $k = 1;
                    foreach ($num_speed1 as $num_speed2) {
                        $num_speed[$num_speed2] = $k;
                        $k++;
                    }
                    $vehicle->num_speed = $num_speed[$vehicle->num_speed];
                }
                else{ $vehicle->num_speed = 0; }
            
            $vehicle->interior_color = $line[27];
            $vehicle->exterior_color = $line[28];
            $vehicle->doors = $line[29];
                if (($vehicle->doors) != '')
                {
                    $doors[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
                    $doors1 = explode(',', _VEHICLE_MANAGER_OPTION_NUMBER_OF_DOORS);
                    $k = 1;
                    foreach ($doors1 as $doors2) {
                        $doors[$doors2] = $k;
                        $k++;
                    }
                    $vehicle->doors = $doors[$vehicle->doors];
                }
                else{
                    $vehicle->doors = 0;
                }
            
            $vehicle->engine = $line[30];
            $vehicle->fuel_type = $line[31];
                if (($vehicle->fuel_type) != ''){
                    $fuel_type[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
                    $fuel_type1 = explode(',', _VEHICLE_MANAGER_OPTION_FUEL_TYPE);
                    $k = 1;
                    foreach ($fuel_type1 as $fuel_type2) {
                        $fuel_type[$fuel_type2] = $k;
                        $k++;
                    }
                    $vehicle->fuel_type = $fuel_type[$vehicle->fuel_type];
                }
                else{
                    $vehicle->fuel_type = 0;
                }
            
            $vehicle->drive_type = $line[32];
                if (($vehicle->drive_type) != ''){
                    $drive_type[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
                    $drive_type1 = explode(',', _VEHICLE_MANAGER_OPTION_DRIVE_TYPE);
                    $k = 1;
                    foreach ($drive_type1 as $drive_type2) {
                        $drive_type[$drive_type2] = $k;
                        $k++;
                    }
                    $vehicle->drive_type = $drive_type[$vehicle->drive_type];
                }
                else{ $vehicle->drive_type = 0; }
                
            $vehicle->cylinder = $line[33];
                if (($vehicle->cylinder) != '')
                {
                    $cylinder[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
                    $cylinder1 = explode(',', _VEHICLE_MANAGER_OPTION_NUMBER_OF_CYLINDERS);
                    $k = 1;
                    foreach ($cylinder1 as $cylinder2) {
                        $cylinder[$cylinder2] = $k;
                        $k++;
                    }
                    $vehicle->cylinder = $cylinder[$vehicle->cylinder];
                }
                else {$vehicle->cylinder = 0;}
            
            $vehicle->wheelbase = $line[34];
            $vehicle->seating = $line[35];
            $vehicle->city_fuel_mpg = $line[36];
            $vehicle->highway_fuel_mpg = $line[37];
            $vehicle->wheeltype = $line[38];
            $vehicle->rear_axe_type = $line[39];
            $vehicle->brakes_type = $line[40];
            $vehicle->exterior_amenities = $line[41];
            $vehicle->dashboard_options = $line[42];
            $vehicle->interior_amenities = $line[43];
            $vehicle->safety_options = $line[44];
            $vehicle->w_basic = $line[45];
            $vehicle->w_drivetrain = $line[46];
            $vehicle->w_corrosion = $line[47];
            $vehicle->w_roadside_ass = $line[48];
            $vehicle->checked_out = $line[49];
            $vehicle->checked_out_time = $line[50];
            $vehicle->ordering = $line[51];
            $vehicle->date = $line[52];
            $vehicle->hits = $line[53];
            $vehicle->edok_link = $line[54];
            $vehicle->published = $line[55];
            $vehicle->approved = $line[56];
            $vehicle->country = $line[57];
            $vehicle->region = $line[58];
            $vehicle->city = $line[59];
            $vehicle->district = $line[60];
            $vehicle->zipcode = $line[61];
            $vehicle->owneremail = $line[62];
            $vehicle->language = $line[63];
            $vehicle->featured_clicks = $line[64];
            $vehicle->featured_shows = $line[65];
            $vehicle->extra1 = $line[66];
            $vehicle->extra2 = $line[67];
            $vehicle->extra3 = $line[68];
            $vehicle->extra4 = $line[69];
            $vehicle->extra5 = $line[70];
            $vehicle->extra6 = $line[71];
            $vehicle->extra7 = $line[72];
            $vehicle->extra8 = $line[73];
            $vehicle->extra9 = $line[74];
            $vehicle->extra10 = $line[75];
            $vehicle->video_link = $line[76];
            $vehicle->owner_id = $line[77];         
            
            $tmp[0] = $i;
            $tmp[1] = trim($vehicle->vehicleid);
            $tmp[2] = $vehicle->vtitle;
            $tmp[3] = $vehicle->vmodel;
            $tmp[4] = $vehicle->price . ' ' . $vehicle->priceunit;
            
            
            //print_r($catid); exit;
            
            if(!$vehicle->check() || !$vehicle->store()){
                $tmp[5] = $vehicle->getError();
            }
            else{
                $tmp[5] = "OK";
                $vehicle->saveCatIds($catid);
            }
            
            $retVal[$i] = $tmp;
            $i++;
        
            
        }
        return $retVal;
        
    }

    static function getXMLItemValue($item, $item_name)
    {
        $vehicle_items = $item->getElementsByTagname($item_name);
        $vehicle_item = $vehicle_items->item(0);
        if (NULL != $vehicle_item)
            return $vehicle_item->nodeValue; else
            return "";
    }

    static function findCategory(& $categories, $new_category)
    {
        global $database;
        foreach ($categories as $category)
            if ($category->old_id == $new_category->old_id)
                return $category;
        $new_parent_id = -1;
        if ($new_category->old_parent_id != 0)
        {
            foreach ($categories as $category) {
                if ($category->old_id == $new_category->old_parent_id)
                {
                    $new_parent_id = $category->id;
                    break;
                }
            }
        } else
            $new_parent_id = 0;

        //sanity test
        if ($new_parent_id === -1)
        {
            echo "error in import !";
            exit;
        }
        $row = new mainVehiclemanagerCategories($database); // for 1.6
        $row->section = 'com_vehiclemanager';
        $row->parent_id = $new_parent_id;
        $row->name = $new_category->name;
        $row->title = $new_category->title;
        $row->published = $new_category->published;
        $row->params = $new_category->params;
        $row->params2 = $new_category->params2;
        $row->language = $new_category->language;
        if (!$row->check())
        {
            echo "error in import2 !";
            exit;
            exit();
        }
        if (!$row->store())
        {
            echo "error in import3 !";
            exit;
            exit();
        }

        $row->updateOrder("section='com_vehiclemanager' AND parent_id='$row->parent_id'");

        $new_category->id = $row->id;
        $categories[] = $new_category;

        return $new_category;
    }
            static function updateAssociateVehicle($infoArr){

            $dataToUpdate = array();
            global $database;
            for($i = 0; $i < count($infoArr); $i++){
                if(isset($infoArr[$i]['associateVehicle']) && $infoArr[$i]['associateVehicle']){
                    $currentAssocId = array();
                    $newObjassociateVehicle = unserialize($infoArr[$i]['associateVehicle']);
                    
                    foreach ($newObjassociateVehicle as $value=>$key){
                        if($key && $key != 0){
                            for($j = 0; $j < count($infoArr); $j++){
                                if(isset($infoArr[$j]['oldId']) && $infoArr[$j]['oldId'] == $key){
                                    $newObjassociateVehicle[$value] = $infoArr[$j]['newId'];
                                    $currentAssocId[] = $infoArr[$j]['newId'];
                                }
                            }
                        }         
                    }
                    $newSerializAssoc = serialize($newObjassociateVehicle);
                    $currentAssocIdToString = implode(',', $currentAssocId);
                    if(!isset($dataToUpdate[$newSerializAssoc])){
                        $dataToUpdate[$newSerializAssoc] = $currentAssocIdToString;
                        
                    }
                }    
            } 
            if(!empty($dataToUpdate)){
                foreach ($dataToUpdate as $value=>$key){
                    
                    $query = "UPDATE #__vehiclemanager_vehicles
                              SET associate_vehicle = '$value'
                              WHERE id in ($key) ";

                    $database->setQuery($query);
                    $database->query();                 
                }
            }
           
            
        }
    //******************   begin add for import XML format   ****************************
    static function importVehiclesXML($files_name_pars, $catid)
    {
        global $database;
        $retVal = array();
        $k = 0;
        $new_categories = array();
        $new_features = array();
        $new_relate_ids = array();

        $dom = new domDocument('1.0', 'utf-8');
        $dom->load($files_name_pars);

        if ($catid === null)
        {
            mosVehicleManagerImportExport::clearDatabase();
            $cat_list = $dom->getElementsByTagname('category');
            for ($i = 0; $i < $cat_list->length; $i++) {
                $category = $cat_list->item($i);
                $new_category = new stdClass();
                if (mosVehicleManagerImportExport::getXMLItemValue($category, 'category_section') == 'com_vehiclemanager')
                {
                    $new_category->old_id = mosVehicleManagerImportExport::getXMLItemValue($category, 'category_id');
                    $new_category->old_parent_id = mosVehicleManagerImportExport::getXMLItemValue($category, 'category_parent_id');
                    $new_category->name = mosVehicleManagerImportExport::getXMLItemValue($category, 'category_name');
                    $new_category->title = mosVehicleManagerImportExport::getXMLItemValue($category, 'category_title');
                    $new_category->published = mosVehicleManagerImportExport::getXMLItemValue($category, 'category_published');
                    $new_category->params = mosVehicleManagerImportExport::getXMLItemValue($category, 'category_params');
                    $new_category->params2 = mosVehicleManagerImportExport::getXMLItemValue($category, 'category_params2');
                    $new_category->language = mosVehicleManagerImportExport::getXMLItemValue($category, 'category_language');
                    if ($new_category->params == '')
                        $new_category->params = '-2';
                    $new_category = mosVehicleManagerImportExport::findCategory($new_categories, $new_category);
                }
            }
        }

        $feature_list = $dom->getElementsByTagname('feature');
        for ($i = 0; $i < $feature_list->length; $i++) {
            $feature = $feature_list->item($i);
            $new_feature = new mosVehicleManager_feature($database);
            $old_id = mosVehicleManagerImportExport::getXMLItemValue($feature, 'feature_id');
            $new_feature->name = mosVehicleManagerImportExport::getXMLItemValue($feature, 'feature_name');
            $new_feature->categories = mosVehicleManagerImportExport::getXMLItemValue($feature, 'feature_categories');
            $new_feature->published = mosVehicleManagerImportExport::getXMLItemValue($feature, 'feature_published');
            $new_feature->image_link = mosVehicleManagerImportExport::getXMLItemValue($feature, 'feature_image_link');
            if (!$new_feature->check() || !$new_feature->store())
            {
                $tmp[5] = $new_feature->getError();
            } else
            {
                $database->setQuery("UPDATE #__vehiclemanager_feature SET id = $old_id WHERE id = " . $new_feature->id . "");
                $database->query();
                $tmp[5] = "OK";
            }
        }

        $vehicle_list = $dom->getElementsByTagname('vehicle');
        $associateSaveArr = array();
        
        for ($i = 0; $i < $vehicle_list->length; $i++) {
            $vehicle_class = new mosVehicleManager($database);
            $vehicle = $vehicle_list->item($i);
            //get VehicleID
            //$vehicle_class->vehicleid = $vehicle_id = $vehicle_class->getUnusedVehicleId();        
            $vehicle_class->vehicleid = $vehicle_id = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'vehicleid');
            // get description
            $vehicle_description = $vehicle_class->description = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'description');
            //get link
            $vehicle_class->link = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'link');
            //get model
            $vehicle_model = $vehicle_class->vmodel = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'vmodel');
            //get vehicle type
            $vehicle_class->vtype = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'vtype');
            if (($vehicle_class->vtype) != '')
            {
                $vtype[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
                $vtype1 = explode(',', _VEHICLE_MANAGER_OPTION_VEHICLE_TYPE);
                $k = 1;
                foreach ($vtype1 as $vtype2) {
                    $vtype[$vtype2] = $k;
                    $k++;
                }
                $vehicle_class->vtype = $vtype[$vehicle_class->vtype];
            } else
                $vehicle_class->vtype = 0;
            //get listing_type
            $vehicle_class->listing_type = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'listing_type');
            if (($vehicle_class->listing_type) != '')
            {
                $listing_type[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
                $listing_type[_VEHICLE_MANAGER_OPTION_FOR_RENT] = 1;
                $listing_type[_VEHICLE_MANAGER_OPTION_FOR_SALE] = 2;
                $vehicle_class->listing_type = $listing_type[$vehicle_class->listing_type];
            } else
                $vehicle_class->listing_type = 0;
            //get price
            $vehicle_price = $vehicle_class->price = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'price');
            $vehicle_priceunit = $vehicle_class->priceunit = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'priceunit');
            //get price_type
            $vehicle_class->price_type = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'price_type');
            if (($vehicle_class->price_type) != '')
            {
                $price_type[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
                $price_type1 = explode(',', _VEHICLE_MANAGER_OPTION_PRICE_TYPE);
                $k = 1;
                foreach ($price_type1 as $price_type2) {
                    $price_type[$price_type2] = $k;
                    $k++;
                }
                $vehicle_class->price_type = $price_type[$vehicle_class->price_type];
            } else
                $vehicle_class->price_type = 0;
            //get title
            $vehicle_title = $vehicle_class->vtitle = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'vtitle');
            //get location
            $vehicle_class->vlocation = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'vlocation');
            //get vlatitude
            $vehicle_class->vlatitude = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'vlatitude');
            //get vlongitude
            $vehicle_class->vlongitude = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'vlongitude');
            //get map_zoom
            $vehicle_class->map_zoom = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'map_zoom');
            //get year
            $vehicle_class->year = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'year');
            //get vcondition
            $vehicle_class->vcondition = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'vcondition');
            if (($vehicle_class->vcondition) != '')
            {
                $vcondition[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
                $vcondition1 = explode(',', _VEHICLE_MANAGER_OPTION_VEHICLE_CONDITION);
                $k = 1;
                foreach ($vcondition1 as $vcondition2) {
                    $vcondition[$vcondition2] = $k;
                    $k++;
                }
                $vehicle_class->vcondition = $vcondition[$vehicle_class->vcondition];
            } else
                $vehicle_class->vcondition = 0;
            //get mileage
            $vehicle_class->mileage = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'mileage');
            //get listing_status
            $vehicle_class->listing_status = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'listing_status');
            if (($vehicle_class->listing_status) != '')
            {
                $listing_status[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
                $listing_status1 = explode(',', _VEHICLE_MANAGER_OPTION_LISTING_STATUS);
                $k = 1;
                foreach ($listing_status1 as $listing_status2) {
                    $listing_status[$listing_status2] = $k;
                    $k++;
                }
                $vehicle_class->listing_status = $listing_status[$vehicle_class->listing_status];
            } else
                $vehicle_class->listing_status = 0;
            //get engine
            $vehicle_class->engine = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'engine');
            //get transmission
            $vehicle_class->transmission = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'transmission');
            if (($vehicle_class->transmission) != '')
            {
                $transmission[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
                $transmission1 = explode(',', _VEHICLE_MANAGER_OPTION_TRANSMISSION);
                $k = 1;
                foreach ($transmission1 as $transmission2) {
                    $transmission[$transmission2] = $k;
                    $k++;
                }
                $vehicle_class->transmission = $transmission[$vehicle_class->transmission];
            } else
                $vehicle_class->transmission = 0;
            //get drive_type
            $vehicle_class->drive_type = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'drive_type');
            if (($vehicle_class->drive_type) != '')
            {
                $drive_type[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
                $drive_type1 = explode(',', _VEHICLE_MANAGER_OPTION_DRIVE_TYPE);
                $k = 1;
                foreach ($drive_type1 as $drive_type2) {
                    $drive_type[$drive_type2] = $k;
                    $k++;
                }
                $vehicle_class->drive_type = $drive_type[$vehicle_class->drive_type];
            } else
                $vehicle_class->drive_type = 0;
            //get cylinder
            $vehicle_class->cylinder = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'cylinder');
            if (($vehicle_class->cylinder) != '')
            {
                $numcylinder[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
                $numcylinder1 = explode(',', _VEHICLE_MANAGER_OPTION_NUMBER_OF_CYLINDERS);
                $k = 1;
                foreach ($numcylinder1 as $numcylinder2) {
                    $numcylinder[$numcylinder2] = $k;
                    $k++;
                }
                $vehicle_class->cylinder = $numcylinder[$vehicle_class->cylinder];
            } else
                $vehicle_class->cylinder = 0;
            //get num_speed
            $vehicle_class->num_speed = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'num_speed');
            if (($vehicle_class->num_speed) != '')
            {
                $numspeed[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
                $numspeed1 = explode(',', _VEHICLE_MANAGER_OPTION_NUMBER_OF_SPEEDS);
                $k = 1;
                foreach ($numspeed1 as $numspeed2) {
                    $numspeed[$numspeed2] = $k;
                    $k++;
                }
                $vehicle_class->num_speed = $numspeed[$vehicle_class->num_speed];
            } else
                $vehicle_class->num_speed = 0;
            //get fuel_type
            $vehicle_class->fuel_type = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'fuel_type');
            if (($vehicle_class->fuel_type) != '')
            {
                $fuel_type[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
                $fuel_type1 = explode(',', _VEHICLE_MANAGER_OPTION_FUEL_TYPE);
                $k = 1;
                foreach ($fuel_type1 as $fuel_type2) {
                    $fuel_type[$fuel_type2] = $k;
                    $k++;
                }
                $vehicle_class->fuel_type = $fuel_type[$vehicle_class->fuel_type];
            } else
                $vehicle_class->fuel_type = 0;
            //get city_fuel_mpg
            $vehicle_class->city_fuel_mpg = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'city_fuel_mpg');
            //get highway_fuel_mpg
            $vehicle_class->highway_fuel_mpg = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'highway_fuel_mpg');
            //get wheelbase
            $vehicle_class->wheelbase = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'wheelbase');
            //get wheeltype
            $vehicle_class->wheeltype = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'wheeltype');
            //get rear_axe_type
            $vehicle_class->rear_axe_type = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'rear_axe_type');
            //get brakes_type
            $vehicle_class->brakes_type = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'brakes_type');
            //get exterior_color
            $vehicle_class->exterior_color = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'exterior_color');
            //get doors
            $vehicle_class->doors = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'doors');
            if (($vehicle_class->doors) != '')
            {
                $numdoors[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
                $numdoors1 = explode(',', _VEHICLE_MANAGER_OPTION_NUMBER_OF_DOORS);
                $k = 1;
                foreach ($numdoors1 as $numdoors2) {
                    $numdoors[$numdoors2] = $k;
                    $k++;
                }
                $vehicle_class->doors = $numdoors[$vehicle_class->doors];
            } else
                $vehicle_class->doors = 0;
            //get exterior_amenities
            $vehicle_class->exterior_amenities = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'exterior_amenities');
            //get interior_color
            $vehicle_class->interior_color = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'interior_color');
            //get seating
            $vehicle_class->seating = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'seating');
            //get dashboard_options
            $vehicle_class->dashboard_options = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'dashboard_options');
            //get interior_amenities
            $vehicle_class->interior_amenities = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'interior_amenities');
            //get safety_options
            $vehicle_class->safety_options = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'safety_options');
            //get w_basic
            $vehicle_class->w_basic = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'w_basic');
            //get w_drivetrain
            $vehicle_class->w_drivetrain = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'w_drivetrain');
            //get w_corrosion
            $vehicle_class->w_corrosion = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'w_corrosion');
            //get w_roadside_ass
            $vehicle_class->w_roadside_ass = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'w_roadside_ass');
            //get image_link
            $vehicle_class->image_link = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'image_link');
            //get featured_clicks
            $vehicle_class->featured_clicks = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'featured_clicks');
            //get featured_shows
            $vehicle_class->featured_shows = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'featured_shows');
            //get hits
            $vehicle_class->hits = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'hits');
            //get date
            $vehicle_class->date = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'date');
            //get published
            $vehicle_class->published = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'published');
            //get contacts
            $vehicle_class->contacts = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'contacts');

            $vehicle_class->owneremail = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'owneremail');
            $vehicle_class->language = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'language');
            $vehicle_class->maker = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'maker');
            $vehicle_class->country = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'country');
            $vehicle_class->region = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'region');
            $vehicle_class->city = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'city');
            $vehicle_class->district = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'district');
            $vehicle_class->zipcode = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'zipcode');

            $vehicle_class->extra1 = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'extra1');
            $vehicle_class->extra2 = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'extra2');
            $vehicle_class->extra3 = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'extra3');
            $vehicle_class->extra4 = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'extra4');
            $vehicle_class->extra5 = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'extra5');
            $vehicle_class->extra6 = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'extra6');
            $vehicle_class->extra7 = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'extra7');
            $vehicle_class->extra8 = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'extra8');
            $vehicle_class->extra9 = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'extra9');
            $vehicle_class->extra10 = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'extra10');
            
            $vehicle_class->owner_id = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'owner_id');
            //get associate_vehicle
            $vehicle_class->associate_vehicle = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'associate_vehicle');
            //get category
            if ($catid === null)
            {
                $new_category = new stdClass();
                $catidsxml = $vehicle->getElementsByTagname('catid');
                $tempcat = array();
                for ($t = 0; $t < $catidsxml->length; $t++) {
                    $tempxml[$t] = $catidsxml->item($t);
                    $new_category = new stdClass();
                    $new_category->old_id = $tempxml[$t]->nodeValue;
                    $new_category = mosVehicleManagerImportExport::findCategory($new_categories, $new_category);
                    $tempcat[] = $new_category->id;
                }
            } else
            {
                $tempcat = array();
                $tempcat = $catid;
            }
            //for output rezult in table
            $tmp[0] = $i;
            $tmp[1] = $vehicle_id;
            $tmp[2] = $vehicle_title;
            $tmp[3] = $vehicle_model;
            $tmp[4] = $vehicle_price . ' ' . $vehicle_priceunit;
            if (!$vehicle_class->check() || !$vehicle_class->store())
            {
                $tmp[5] = $vehicle_class->getError();
            } else
            {
                $vehicle_class->saveCatIds($tempcat);
                $tmp[5] = "OK";
            }
            
            if ($catid === null){
                $vehicle_class->checkin();
            }
            
            $retVal[$i] = $tmp;

           $ussuesArray = array();
            $ussuesArray["associateVehicle"] = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'associate_vehicle');
            $ussuesArray["oldId"] = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'id');
            $ussuesArray["newId"] = $vehicle_class->id;
            
            $associateSaveArr[] = $ussuesArray;
            
            //get Reviews
            if ($tmp[5] == "OK" && mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'reviews') != "")
            {
                $review_list = $vehicle->getElementsByTagname('review');
                for ($j = 0; $j < $review_list->length; $j++) {
                    $review = $review_list->item($j);

                    //get for review - user_name
                    $review_user_name = mosVehicleManagerImportExport::getXMLItemValue($review, 'user_name');
                    //get for review - user_email
                    $review_user_email = mosVehicleManagerImportExport::getXMLItemValue($review, 'user_email');
                    //get for review - date
                    $review_date = mosVehicleManagerImportExport::getXMLItemValue($review, 'date');
                    //get for review - rating
                    $review_rating = mosVehicleManagerImportExport::getXMLItemValue($review, 'rating');
                    //get for review - title
                    $review_title = mosVehicleManagerImportExport::getXMLItemValue($review, 'title');
                    //get for review - comment
                    $review_comment = mosVehicleManagerImportExport::getXMLItemValue($review, 'comment');
                    //get for review - published
                    $review_published = mosVehicleManagerImportExport::getXMLItemValue($review, 'published');

                    //insert data in table review
                    $database->setQuery("INSERT INTO #__vehiclemanager_review" .
                            "\n (fk_vehicleid, user_name,user_email, date, rating, title, comment, published)" .
                            "\n VALUES " .
                            "\n (" . $vehicle_class->id . ", '" . $review_user_name . "', '" . $review_user_email .
                            "', '" . $review_date . "'," . $review_rating . ",'" . $review_title . "', '" . $review_comment . "', '" . $review_published . "');");
                    $database->query();
                } //end for(...) - REVIEW
            } //end if(...) - REVIEW
            //get rents
            if ($tmp[5] == "OK" && mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'rents') != "")
            {
                $rent_list = $vehicle->getElementsByTagname('rent');
                for ($j = 0; $j < $rent_list->length; $j++) {
                    $rent = $rent_list->item($j);
                    $help = new mosVehicleManager_rent($database);
                    $help->fk_vehicleid = $vehicle_class->vehicleid;
                    //get for rent - rent_from
                    $help->rent_from = mosVehicleManagerImportExport::getXMLItemValue($rent, 'rent_from');
                    //get for rent - rent_until
                    $help->rent_until = mosVehicleManagerImportExport::getXMLItemValue($rent, 'rent_until');
                    //get for rent - rent_return
                    $rent_return = mosVehicleManagerImportExport::getXMLItemValue($rent, 'rent_return');
                    //get for rent - user_name
                    $help->user_name = mosVehicleManagerImportExport::getXMLItemValue($rent, 'user_name');
                    //get for rent - user_email
                    $help->user_email = mosVehicleManagerImportExport::getXMLItemValue($rent, 'user_email');
                    //get for rent - user_mailing
                    $help->user_mailing = mosVehicleManagerImportExport::getXMLItemValue($rent, 'user_mailing');

                    if (empty($rent_return))
                    {
                        $help->rent_return = new stdClass();
                    } else
                    {
                        $help->rent_return = $rent_return;
                    }

                    //insert data in table #__vehiclemanager_rent
                    if (!$help->check() || !$help->store())
                    {
                        $tmp[5] = $help->getError();
                    } else
                    {
                        $vehicle_class->fk_rentid = $help->id;
                        if (!$vehicle_class->check() || !$vehicle_class->store())
                        {
                            $tmp[5] = $vehicle_class->getError();
                        } else
                        {
                            $tmp[5] = "OK";
                        }
                    }
                } //end for(...) - rent
            } //end if(...) - rent
            //get rentrequests
            if ($tmp[5] == "OK" && mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'rentrequests') != "")
            {
                $rentrequests_list = $vehicle->getElementsByTagname('rentrequest');
                for ($j = 0; $j < $rentrequests_list->length; $j++) {
                    $rentrequest = $rentrequests_list->item($j);
                    //get for rentrequest - rent_from
                    $rr_rent_from = mosVehicleManagerImportExport::getXMLItemValue($rentrequest, 'rent_from');
                    //get for rentrequest - rent_until
                    $rr_rent_until = mosVehicleManagerImportExport::getXMLItemValue($rentrequest, 'rent_until');
                    //get for rentrequest - rent_return
                    $rr_rent_request = mosVehicleManagerImportExport::getXMLItemValue($rentrequest, 'rent_request');
                    //get for rentrequest - user_name
                    $rr_user_name = mosVehicleManagerImportExport::getXMLItemValue($rentrequest, 'user_name');
                    //get for rentrequest - user_email
                    $rr_user_email = mosVehicleManagerImportExport::getXMLItemValue($rentrequest, 'user_email');
                    //get for rentrequest - user_mailing
                    $rr_user_mailing = mosVehicleManagerImportExport::getXMLItemValue($rentrequest, 'user_mailing');
                    //get for rentrequest - status
                    $rr_status = mosVehicleManagerImportExport::getXMLItemValue($rentrequest, 'status');
                    //insert data in table jos_vehiclemanager_rent_request
                    $database->setQuery("INSERT INTO #__vehiclemanager_rent_request" .
                            "\n (fk_vehicleid, rent_from,rent_until, rent_request, user_name, user_email, user_mailing,status)" .
                            "\n VALUES " .
                            "\n (" . $vehicle_class->id . ", '" . $rr_rent_from . "', '" . $rr_rent_until .
                            "', '" . $rr_rent_request . "','" . $rr_user_name . "','" . $rr_user_email . "', '" . $rr_user_mailing .
                            "', '" . $rr_status . "');");
                    $database->query();
                } //end for(...) - rentrequest
            } //end if(...) - rentrequest
            //get buyingrequests
            if ($tmp[5] == "OK" && mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'buyingrequests') != "")
            {
                $buyingrequests_list = $vehicle->getElementsByTagname('buyingrequest');
                for ($j = 0; $j < $buyingrequests_list->length; $j++) {
                    $buyingrequest = $buyingrequests_list->item($j);
                    //get for $buyingrequest - buying_request
                    $br_buying_request = mosVehicleManagerImportExport::getXMLItemValue($buyingrequest, 'buying_request');
                    //get for $buyingrequest - customer_name
                    $br_customer_name = mosVehicleManagerImportExport::getXMLItemValue($buyingrequest, 'customer_name');
                    //get for $buyingrequest - customer_email
                    $br_customer_email = mosVehicleManagerImportExport::getXMLItemValue($buyingrequest, 'customer_email');
                    //get for $buyingrequest - customer_phone
                    $br_customer_phone = mosVehicleManagerImportExport::getXMLItemValue($buyingrequest, 'customer_phone');
                    //get for $buyingrequest - status
                    $br_status = mosVehicleManagerImportExport::getXMLItemValue($buyingrequest, 'status');
                    //insert data in table jos_vehiclemanager_buying_request
                    $database->setQuery("INSERT INTO #__vehiclemanager_buying_request" .
                            "\n (fk_vehicleid, buying_request, customer_name, customer_email, customer_phone,status)" .
                            "\n VALUES " .
                            "\n (" . $vehicle_class->id .
                            ", '" . $br_buying_request . "','" . $br_customer_name . "','" . $br_customer_email . "', '" . $br_customer_phone .
                            "', '" . $br_status . "');");
                    $database->query();
                } //end for(...) - $buyingrequest
            } //end if(...) - $buyingrequest
            //get images

            if ($tmp[5] == "OK" && mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'images') != "")
            {
                $images_list = $vehicle->getElementsByTagname('image');
                for ($j = 0; $j < $images_list->length; $j++) {
                    $image = $images_list->item($j);
                    //get for $image - thumbnail_img
                    $image_thumbnail_img = mosVehicleManagerImportExport::getXMLItemValue($image, 'thumbnail_img');
                    //get for $image - main_img
                    $image_main_img = mosVehicleManagerImportExport::getXMLItemValue($image, 'main_img');
                    //insert data in table jos_vehiclemanager_photos
                    $database->setQuery("INSERT INTO #__vehiclemanager_photos" .
                            "\n (fk_vehicleid, thumbnail_img, main_img)" .
                            "\n VALUES " .
                            "\n (" . $vehicle_class->id .
                            ", '" . $image_thumbnail_img . "','" . $image_main_img . "');");
                    $database->query();
                } //end for(...) - images
            } //end if(...) - images

            $vehicleid_old[] = array('old_id' => mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'id'), 'id' => $vehicle_class->id);
        }//end for(...) - vehicle

        $features_vehicles = array();
        $feature_vehicle_list = $dom->getElementsByTagname('feature_vehicle');
        for ($i = 0; $i < $feature_vehicle_list->length; $i++) {
            $feature_vehicle = $feature_vehicle_list->item($i);
            $fk_vehicleid = mosVehicleManagerImportExport::getXMLItemValue($feature_vehicle, 'feature_vehicle_fk_vehicleid');
            $fk_featureid = mosVehicleManagerImportExport::getXMLItemValue($feature_vehicle, 'feature_vehicle_fk_featureid');
            $features_vehicles[] = array('vehicleid' => $fk_vehicleid, 'featureid' => $fk_featureid);
        }
//print_r($vehicleid_old); exit;
        foreach ($features_vehicles as $key_features_vehicles) {
            foreach ($vehicleid_old as $key_vehicleid_old) {
                if ($key_vehicleid_old['old_id'] == $key_features_vehicles['vehicleid'])
                {
                    $database->setQuery("INSERT INTO #__vehiclemanager_feature_vehicles (fk_vehicleid, fk_featureid) VALUES (" . $key_vehicleid_old['id'] . "," . $key_features_vehicles['featureid'] . " )");
                    $database->query();
                }
            }
        }
        mosVehicleManagerImportExport::updateAssociateVehicle($associateSaveArr); 
        return $retVal;
    }

//***************************************************************************************************
//***********************   end add for import XML format   *****************************************
//***************************************************************************************************

    static function exportVehiclesXML($vehicles, $all)
    {
        
        // print_r($vehicles); exit;
        
        global $mosConfig_live_site, $mosConfig_absolute_path, $vehiclemanager_configuration, $database;
        $strXmlDoc = "";
        $strXmlDoc .= "<?xml version='1.0' encoding='utf-8'?>\n";

        //$istaller_data_dom =  $xmlDoc->createElement("istaller_data");
        $strXmlDoc .= "<vechicles_data>\n";
        $strXmlDoc .= "<version>";
        $strXmlDoc .= $vehiclemanager_configuration['release']['version'];
        $strXmlDoc .= "</version>\n";
        if ($all)
        {
            $strXmlDoc .= "<categories>\n";
            $database->setQuery("SELECT name, title,section, id, parent_id, published, params, params2, language FROM #__vehiclemanager_main_categories " .
                    "WHERE section='com_vehiclemanager' order by parent_id; ");
            $categories = $database->loadObjectList();

            foreach ($categories as $category) {
                //add category
                $strXmlDoc .= "<category>\n";
                $strXmlDoc .= "<category_id>" . $category->id . "</category_id>";
                $strXmlDoc .= "<category_parent_id>" . $category->parent_id . "</category_parent_id>";
                $strXmlDoc .= "<category_name><![CDATA[" . $category->name . "]]></category_name>";
                $strXmlDoc .= "<category_title><![CDATA[" . $category->title . "]]></category_title>";
                $strXmlDoc .= "<category_section><![CDATA[" . $category->section . "]]></category_section>";
                $strXmlDoc .= "<category_published><![CDATA[" . $category->published . "]]></category_published>";
                $strXmlDoc .= "<category_params><![CDATA[" . $category->params . "]]></category_params>";
                $strXmlDoc .= "<category_params2><![CDATA[" . $category->params2 . "]]></category_params2>";
                $strXmlDoc .= "<category_language><![CDATA[" . $category->language . "]]></category_language>";
                $strXmlDoc .= "</category>\n";
            }
            //create and append list element
            $strXmlDoc .= "</categories>\n";

            $strXmlDoc .= "<features>\n";
            $database->setQuery("SELECT * FROM #__vehiclemanager_feature ");
            $features = $database->loadObjectList();

            foreach ($features as $feature) {
                //add features
                $strXmlDoc .= "<feature>\n";
                $strXmlDoc .= "<feature_id>" . $feature->id . "</feature_id>";
                $strXmlDoc .= "<feature_name><![CDATA[" . $feature->name . "]]></feature_name>";
                $strXmlDoc .= "<feature_categories><![CDATA[" . $feature->categories . "]]></feature_categories>";
                $strXmlDoc .= "<feature_published><![CDATA[" . $feature->published . "]]></feature_published>";
                $strXmlDoc .= "<feature_image_link><![CDATA[" . $feature->image_link . "]]></feature_image_link>";
                $strXmlDoc .= "</feature>\n";
                
                
                
            }
            //create and append list element
            $strXmlDoc .= "</features>\n";

            $strXmlDoc .= "<features_vehicles>\n";
            $database->setQuery("SELECT * FROM #__vehiclemanager_feature_vehicles ");
            $features_vehicles = $database->loadObjectList();

            foreach ($features_vehicles as $feature_vehicle) {
                //add feature vehicle
                $strXmlDoc .= "<feature_vehicle>\n";
                $strXmlDoc .= "<feature_vehicle_id>" . $feature_vehicle->id . "</feature_vehicle_id>";
                $strXmlDoc .= "<feature_vehicle_fk_vehicleid><![CDATA[" . $feature_vehicle->fk_vehicleid . "]]></feature_vehicle_fk_vehicleid>";
                $strXmlDoc .= "<feature_vehicle_fk_featureid><![CDATA[" . $feature_vehicle->fk_featureid . "]]></feature_vehicle_fk_featureid>";
                $strXmlDoc .= "</feature_vehicle>\n";
            }
            //create and append list element
            $strXmlDoc .= "</features_vehicles>\n";
        }
        //create and append list element
        $strXmlDoc .= "<vechicles_list>\n";

        foreach ($vehicles as $vehicle) {
            
            
            $strXmlDoc .= $vehicle->toXML2($all);
            
        }
        
        
        
        $strXmlDoc .= "</vechicles_list>\n";
        $strXmlDoc .= "</vechicles_data>";
        //print_r($strXmlDoc);exit;
        return $strXmlDoc;
    }

    
    static function storeExportFile($data, $type)
    {
        
        
        
        global $mosConfig_live_site, $mosConfig_absolute_path, $vehiclemanager_configuration;
        $fileName = "vehiclemanager_" . date("Ymd_His");
        $fileBase = "/administrator/components/com_vehiclemanager/exports/";

        //write the xml file
        $fp = fopen($mosConfig_absolute_path . $fileBase . $fileName . ".xml", "w", 0); #open for writing

        
        //print_r($data);exit;


        fwrite($fp, $data); #write all of $data to our opened file
        fclose($fp); #close the file
        
        $InformationArray = array();
        $InformationArray['xml_file'] = $fileName . '.xml';
        $InformationArray['log_file'] = $fileName . '.log';
        $InformationArray['fileBase'] = "file://" . getcwd() . "/components/com_vehiclemanager/exports/";
        $InformationArray['urlBase'] = $mosConfig_live_site . $fileBase;
        $InformationArray['out_file'] = $InformationArray['xml_file'];
        $InformationArray['error'] = new stdClass();

        switch ($type) {
            case 'csv':
                $InformationArray['xslt_file'] = 'csv.xsl';
                $InformationArray['out_file'] = $fileName . '.csv';
                mosVehicleManagerImportExport :: transformPHP4($InformationArray);
                break;

            default:
                break;
        }

        return $InformationArray;
    }

    static function transformPHP4(&$InformationArray)
    {
        
        // create the XSLT processor^M
        $xh = xslt_create() or die("Could not create XSLT processor");

        // Process the document
        $result = xslt_process($xh, $InformationArray['fileBase'] . $InformationArray['xml_file'], $InformationArray['fileBase'] . $InformationArray['xslt_file'], $InformationArray['fileBase'] . $InformationArray['out_file']);
        if (!$result)
        {
            // Something croaked. Show the error
            $InformationArray['error'] = "Cannot process XSLT document: " .
                    /* xslt_errno($xh) . */ " " /* . xslt_error($xh) */;
        }

        // Destroy the XSLT processor
        xslt_free($xh);
    }


    static function remove_info()
    {
        global $database;
        $database->setQuery('truncate #__vehiclemanager_vehicles');
        $database->query();
        if ($database->getErrorNum())
        {
            echo $database->stderr();
            return $database->stderr();
        }
        $database->setQuery('truncate #__vehiclemanager_feature_vehicles');
        $database->query();
        if ($database->getErrorNum())
        {
            echo $database->stderr();
            return $database->stderr();
        }
        $database->setQuery('truncate #__vehiclemanager_feature');
        $database->query();
        if ($database->getErrorNum())
        {
            echo $database->stderr();
            return $database->stderr();
        }
        ///$database->setQuery("delete from #__categories where section='com_vehiclemanager'");
        $database->setQuery("DROP TABLE '#__vehiclemanager_main_categories'"); // for 1.6  
        $database->query();
        if ($database->getErrorNum())
        {
            echo $database->stderr();
            return $database->stderr();
        }
        $database->setQuery('truncate #__vehiclemanager_review');
        $database->query();
        if ($database->getErrorNum())
        {
            echo $database->stderr();
            return $database->stderr();
        }
        $database->setQuery('truncate #__vehiclemanager_photos');
        $database->query();
        if ($database->getErrorNum())
        {
            echo $database->stderr();
            return $database->stderr();
        }
        $database->setQuery('truncate #__vehiclemanager_rent');
        $database->query();
        if ($database->getErrorNum())
        {
            echo $database->stderr();
            return $database->stderr();
        }
        $database->setQuery('truncate #__vehiclemanager_rent_request');
        $database->query();
        if ($database->getErrorNum())
        {
            echo $database->stderr();
            return $database->stderr();
        }
        $database->setQuery('truncate #__vehiclemanager_buying_request');
        $database->query();
        if ($database->getErrorNum())
        {
            echo $database->stderr();
            return $database->stderr();
        }
        return "";
    }

    static function clearDatabase()
    {
        global $database;
        ///$database->setQuery("DELETE FROM #__categories WHERE section='com_vehiclemanager'");
        $database->setQuery("DELETE FROM #__vehiclemanager_main_categories "); // for 1.6
        $database->query();
        $database->setQuery("DELETE FROM #__vehiclemanager_feature_vehicles");
        $database->query();
        $database->setQuery("DELETE FROM #__vehiclemanager_feature");
        $database->query();
        $database->setQuery("DELETE FROM #__vehiclemanager_categories");
        $database->query();
        $database->setQuery("DELETE FROM #__vehiclemanager_vehicles");
        $database->query();
        $database->setQuery("DELETE FROM #__vehiclemanager_photos");
        $database->query();
        $database->setQuery("DELETE FROM #__vehiclemanager_rent");
        $database->query();
        $database->setQuery("DELETE FROM #__vehiclemanager_rent_request");
        $database->query();
        $database->setQuery("DELETE FROM #__vehiclemanager_review");
        $database->query();
        $database->setQuery("DELETE FROM #__vehiclemanager_buying_request");
        $database->query();
        $database->setQuery("DELETE FROM #__vehiclemanager_suggestion");
        $database->query();
    }

}

