<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
      <title>Listing Matching Service</title>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <link href="styles/style.css" media="all" rel="stylesheet" type="text/css" />
      <script src="js/jquery-1.6.1.min.js" type="text/javascript"></script>
      <script src="js/functions.js" type="text/javascript"></script> 
    </head>
    <body onload="enableOrDisableFields();">
        <div class="main">
            <div class="title">
                <img class="title" src="images/banner.jpg" />
                <p class="title">List Matching Service</p>
            </div>
            <div class="container">
                <form method="POST" name="form" id="form" enctype="multipart/form-data" action="list_matching_result.php">
                    <table class="container">
                        <tr>
                            <td style="width: 20%;">Column delimiter:</td>
                            <td>
                                <select id="col_delimiter" name="col_delimiter">
                                    <option value=" ">Space</option>
                                    <option value="\t">Tab</option>
                                    <option value=";">Semicolon (;)</option>
                                    <option value=",">Comma (,)</option>
                                    <option value="|">Vertical bar (|)</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="first_row_header">First row contains headers:</label></td>
                            <td>
                                <input type="checkbox" id="first_row_header" name="first_row_header" value="1"/>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="only_accepted_names">Only accepted names:</label></td>
                            <td>
                                <input type="checkbox" id="only_accepted_names" name="only_accepted_names" value="1"/>
                            </td>
                        </tr>
                        <tr>
                            <td>Input type:</td>
                            <td>
                                <input type="radio" id="input_type_file" name="input_type" value="file" checked="checked" onclick="enableOrDisableFields();"><label for="input_type_file">File upload</label>
                                <input type="radio" id="input_type_text" name="input_type" value="text" onclick="enableOrDisableFields();"><label for="input_type_text">Text area</label>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input name="file_upload" id="file_upload" type="file" class="fileinput" size="70" />
                                <textarea style="display: none;" name="text_search" id="text_search" rows="10" cols="100"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: right;">
                                <input type="submit" name="send" value="send" class="sendbutton"/>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </body>
</html>
