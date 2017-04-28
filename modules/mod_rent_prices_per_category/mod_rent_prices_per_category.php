<?php

if (!defined('_JEXEC'))
	define('_JEXEC', 1);
if (!defined('JPATH_BASE'))
define('JPATH_BASE', '../..' );

require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );

/* database connection */
	$db = JFactory::getDBO();
	$query = $db->getQuery(true);
    $query = "SELECT * FROM #__vehiclemanager_main_categories";
    $db->setQuery($query);
    $rows = $db->loadObjectList();
    $num = count($rows);
?>

<h3>Prices per category</h3> 
<div style="font-size: 12px;padding-left: 0px !important;">
<p>Book your car of your choice and we will contact you by <strong>email&nbsp;</strong>or&nbsp;<strong>telephone</strong> for the availability and the exact price of the renting period you want.</p>
<p>Our rent prices per category for one-day renting period are:</p>
<ol style="list-style-type: upper-alpha;padding-left: 15px;line-height: 20px;">
<?php 
if ($num > 0) {
	for($i=$num-1; $i>=0; $i--) {
		echo '<li>from €' . $rows[$i]->startPrice .  ' / day</li>';
		}
	}
?>
</ol>
<p>The above rent prices can change and are only indicative.</p>
<p>Of course, the common market rule applies:&nbsp;<em>more renting days lower the price per day.</em></p>
</div>