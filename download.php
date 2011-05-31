<?php

if (isset($_POST["download"]) && isset($_POST["csv_value"]))
{
    header("Content-type: text/csv; charset=utf-8");
    header("Content-Disposition: attachment; filename=scientific_names.csv");
    header('Pragma: no-cache');
    header('Expires: 0');
    
    echo $_POST["csv_value"];
}