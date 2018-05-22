<?php
ini_set('display_errors','1');
error_reporting(E_ALL);
require_once("./include/db_info.inc.php");
/*
$color = ["Red","Orange","Yellow","Olive","Green","Teal","Blue","Violet","Purple","Pink","Brown","Grey","Black"];
$result = $database->select("global_setting","*",["label"=>"label_color"]);
$labels = $database->select("problem","*");
$label_set = [];
foreach($labels as $row)
{
    $label = explode(" ",$row["label"]);
    foreach($label as $value)
    {
        if(strlen($value) > 0)
        $label_set[$value] = 1;
    }
}

$labels = $database->select("vjudge_problem","*");
foreach($labels as $row)
{
    $label = explode(" ",$row["label"]);
    foreach($label as $value)
    {
        if(strlen($value) > 0)
        $label_set[$value] = 1;
    }
}
$has_color = json_decode($result[0]["value"]);
foreach($has_color as $key=>$value)
{
    unset($label_set[$key]);
}
foreach($label_set as $key=>$value)
{
    $label_set[$key] = strtolower($color[rand(0,12)]);
}
foreach($has_color as $key=>$value)
{
    $label_set[$key] = $value;
}
$database->update("global_setting",["value"=>json_encode($label_set)],["label"=>"label_color"]);
print_r($label_set);
*/

//echo var_dump(json_decode($result[0]["value"]));
?>