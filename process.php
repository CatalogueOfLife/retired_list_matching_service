<?php

require_once("config.php");
require_once("functions.php");

$only_accepted_names = false;
$csv_value = "";
$error = null;

process();

function process()
{
    global $only_accepted_names, $csv_value, $error;
    global $matches, $unmatches;
    
    try 
    {        
        $header_row = false;
        $col_delimiter = "\t";
        $lines_count = 0;
        $more_than_max_lines = false;
        $target_path = "";    
        $search_items = array();
        
        if (isset($_POST["send"]))
        {    
            if (isset($_POST["col_delimiter"]))
            {
                $col_delimiter = $_POST["col_delimiter"];
            }
            
            if (isset($_POST["first_row_header"]))
            {
                $header_row = true;
            }
            
            if (isset($_POST["only_accepted_names"]))
            {
                $only_accepted_names = true;
            }
            
            if ($_POST["input_type"] == "file")
            {
                if ($_FILES["file_upload"]["name"] == null)
                {
                    $error = "No file was selected to be sent. Please, check and try again.";
                    return;
                }
                
                $target_path = TARGETPATH . basename($_FILES["file_upload"]["name"]);    
                $file_name = $_FILES["file_upload"]["name"];
                $extension = end(explode(".", $file_name));
                $size = $_FILES["file_upload"]["size"];
                
                if(($size / 1024) > MAXSIZEFILE)
                {
                    $error = "The size of the uploaded file is larger than the maximum allowed size. Please, check and try again.";
                    return;
                }
                
                if(strtolower($extension) != "txt" && strtolower($extension) != "csv")
                {
                    $error = "The format of the uploaded file is invalid. The allowed formats are CSV and TXT. Please, check and try again.";
                    return;
                }
                
                if(!move_uploaded_file($_FILES["file_upload"]["tmp_name"], $target_path))
                {
                    $error = "An error occurred while sending the file. Please, try again.";
                    return;
                }
                
                $f = fopen($target_path, "r");    
                while ($line = fgets($f))
                {
                    if ($lines_count > MAXLINESFILE)
                    {
                        $more_than_max_lines = true;
                        break;
                    }
                    
                    if (!$header_row)
                    {
                        $search_items[] = $line;    
                    }
                    else
                    {
                         $header_row = false;
                    }
                    
                    $lines_count++;
                }
                
                fclose($f);
                unlink($target_path);
            }
            else
            {
                $text_search = $_REQUEST["text_search"];
                $search_items = preg_split("[\n|\r]", $text_search, -1, PREG_SPLIT_NO_EMPTY);
                
                if (count($search_items) > MAXLINESFILE)
                {
                    $more_than_max_lines = true;
                }
            }
            
            if ($more_than_max_lines)
            {
                $error = "The number of lines exceeded the limit of " . (string)MAXLINESFILE . " lines. Please, check and try again.";
                return;
            }
            
            try
            {
                $conn = mysql_connect(DBSERVER, DBUSER, DBPASSWORD);
                $db_selected = mysql_select_db(DBNAME, $conn);
            }
            catch(Exception $e)
            {
                $error = "An error occurred while connecting to the database. Please, try again.";
                return;
            }
            
            if (!$conn || !$db_selected)
            {
                $error = "An error occurred while connecting to the database. Please, try again.";
                return;
            }
            
            foreach ($search_items as $item)
            {
                if ($header_row)
                {
                    $header_row = false;
                     continue;
                }
                
                $replaced_item = trim(str_replace($col_delimiter, " ", $item));
                
                if (trim($replaced_item) == "")
                {
                    continue;
                }
                
                $parts = tokenizeTaxa($replaced_item);
                
                $genus        = ucfirst($parts["genus"]);
                $species    = $parts["epithet"];
                $infraspecies    = $parts["subepithet"];
                
                if (empty($genus))
                {
                    continue;
                }
                
                $query = "SELECT DISTINCT ss.genus, ss.subgenus, ss.species, ss.infraspecies, ss.infraspecific_marker, ss.author, sns.name_status FROM _search_scientific ss
                            INNER JOIN scientific_name_status sns ON (ss.status = sns.id) WHERE genus = '" . mysql_real_escape_string($genus) . "' ";
                
                if (!empty($species))
                {
                    $query .= "AND species = '" . mysql_real_escape_string($species) . "' ";
                }
                else
                {
                    $query .= "AND (species IS NULL OR species = '')";
                }
                
                if (!empty($infraspecies))
                {
                    $query .= "AND infraspecies = '" . mysql_real_escape_string($infraspecies) . "' ";
                }
                else
                {
                    $query .= "AND (infraspecies IS NULL OR infraspecies = '')";
                }
                
                if ($only_accepted_names)
                {
                    $query .= "AND (status = 1)";
                }
                
                $res = mysql_query($query, $conn);
                
                if (mysql_num_rows($res) > 0)
                {
                    while ($row = mysql_fetch_array($res))
                    {     
                        $csv_value .= $row[0] . $col_delimiter . $row[1] . $col_delimiter . $row[2] . $col_delimiter . $row[3] . $col_delimiter . $row[4] . $col_delimiter . $row[5] . "\n";
                        
                        $matches[] = array(    "data_inputed"        => $replaced_item,
                                            "formated_taxon"    => "<i>" .$row[0] . returnValue($row[1]) . returnValue($row[2]) . returnValue($row[3]) . "</i>" . returnValue($row[4]) . returnValue($row[5]),
                                            "status"            => $row[6]
                                            );
                    }
                }
                else
                {
                    $unmatches[] = $replaced_item;
                }
            }    
                
            if ((count($matches) == 0) && (count($unmatches) == 0))
            {
                $error = "No names were sent to be searched. Please, check and try again.";
            }            
        }
    }
    catch (Exception $e)
    {
        $error = "The application has encountered and error and cannot proceed with the request. The problem can be fixed as soon as possible. Sorry for the inconvenience.";
    }    
}