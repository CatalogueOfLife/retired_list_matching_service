<?php

if (isset($_POST["download"]) && isset($_POST["csv_value"]))
{
    header("Content-type: text/csv; charset=utf-8");
    header("Content-Disposition: attachment; filename=scientific_names.csv");
    header('Pragma: no-cache');
    header('Expires: 0');
    
    //replace tab character, if exists
    $csv_value = str_replace("-tab-", chr(9), $_POST["csv_value"]);
    
    echo $csv_value;
}