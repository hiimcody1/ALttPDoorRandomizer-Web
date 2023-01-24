<?php
/*
 * File: index.php
 * File Created: Saturday, 6th August 2022 8:36:11 pm
 * Author: hiimcody1
 * 
 * Last Modified: Tuesday, 24th January 2023 1:15:16 pm
 * Modified By: hiimcody1
 * 
 * License: MIT License http://www.opensource.org/licenses/MIT
 */

require("config.php");
require("classes/unique.php");
require("classes/class.process.php");
require("classes/class.doorrandomizer.php");
require("classes/class.template.php");
require("classes/class.ui.php");

///*
$UI = new UI();
if(isset($_GET['view']))
    $UI->Render($UI->GetRoute($_GET['view']));
else
    $UI->Render($UI->GetRoute(""));


echo $Template->render("base.php");
die();
//*/
echo "<pre>";
$r = new DoorRandomizer("/home/alttpr/ALttPDoorRandomizer-OW/");

$Options = $r->Options;

echo count($Options)."<br /><br />";

foreach($Options as $Option) {
    //if($Option->DefaultValue === null) {
        var_export($Option);
        echo "<hr />";
    //}
}



$GenOptions = Array();

$Swords = $Options['swords'];
$Swords->Value = "assured";

$GenOptions[] = $Swords;

//echo $r->Generate("Testing",$GenOptions);


?>