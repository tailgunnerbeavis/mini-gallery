<?php 
session_start();

function dirList ($directory) 
{
    $allowedExtensions = array("jpg","jpeg","gif","png"); 
    
    // create an array to hold directory list
    $results = array();

    $filelist = scandir($directory);
    foreach ($filelist as $file) {
        // if $file isn't this directory or its parent,
        // add it to the results array
	$tmp = explode(".",strtolower($file));
        if ($file != '.' && $file != '..' && in_array(end($tmp),$allowedExtensions))
            $results[] = $file;
    }

    // done!
    return $results;

}

//We want to have 10 images per page 
function numberOfPages($directoryListing){
    $length = sizeof($directoryListing);
    $pages = 0;
    if($length>0){
         $pages = ceil($length/10);
    }
    return $pages;
}

//Show the images associated with that page, page numbers start with 1
function showPage($pageNum, $directory){
    $start = $pageNum * 10 - 10;
    print("starting with image $start<br>");
    for($i=$start; $i<$start+10; $i++){
        if ($i < sizeof($directory)){
             print("<img class = \"img-responsive\" src=\"$directory[$i]\" width=\"80%\">");
        }
    }
    addBootStrap();
}

function showPageNumbers($numPages){
    print("<ul class=\"pagination\">\n");
    for($i=1; $i<=$numPages; $i++){
        print("<li><a href=\"" .$_SERVER['SCRIPT_NAME']. "?p=" .$i. "\">" .$i. "</a></li>\n");
    }
    print("</ul>");
    print("<br>");
}

//display the image and one before and after
function showImage($imgNum, $directory){
    
    //first decrease Image number so image 1 is the first file in the array
    $imgCurrent = $imgNum - 1;
    $imgBefore = $imgCurrent - 1;
    $imgAfter = $imgCurrent + 1;

    if($imgBefore >= 0 && $imgBefore < sizeof($directory)){
        $linkImgBefore = $imgBefore + 1;
        print("<a href=\"" .$_SERVER['SCRIPT_NAME']. "?i=" .$linkImgBefore. "\">");
        print("<img class = \"img-thumbnail\" src=\"$directory[$imgBefore]\" width=\"20%\">");
        print("</a> ");
    }

    if($imgCurrent < sizeof($directory)){
        print("<a href=\"" . $directory[$imgCurrent] ."\">");
        print("<img class = \"img\" src=\"$directory[$imgCurrent]\" width=\"40%\">");
        print("</a> ");
    }    

    if($imgAfter < sizeof($directory)){
        $linkImgAfter = $imgAfter + 1;
        print("<a href=\"" .$_SERVER['SCRIPT_NAME']. "?i=" .$linkImgAfter. "\">");
        print("<img class = \"img-thumbnail\" src=\"$directory[$imgAfter]\" width=\"20%\">");
        print("</a> ");
    }
    addBootStrap();
}

//link to each image in list start at 1
function showImageNumbers($directory){
    print("<ul class=\"pagination\">\n");
    for($i=1; $i<=sizeof($directory); $i++){
        print("<li><a href=\"" .$_SERVER['SCRIPT_NAME']. "?i=" .$i. "\">" .$i. "</a></li>\n");
    }
    print("</ul>");
    print("<br>");
}

function showSelector(){
    print("<div class=\"btn-group\">\n");
    print("<a class=\"btn btn-primary\" href=\"" .$_SERVER['SCRIPT_NAME']. "?i=1\">Image View</a>\n");
    print("<a class=\"btn btn-primary\" href=\"" .$_SERVER['SCRIPT_NAME']. "?p=1\">Page View</a>\n");
    print("</div>\n");
}

function addBootStrap(){
    $bootstrap = <<<XML
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="js/jquery.serializejson.js"></script>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
XML;
    print($bootstrap);
}

$thisDirectory = dirList(dirname($_SERVER["SCRIPT_FILENAME"]));
$numPages = numberOfPages($thisDirectory);

//DEBUG: print(sizeof($thisDirectory));

// Select Image or Page view
showSelector();

// Image view

if(!empty($_GET['i'])){
    showImageNumbers($thisDirectory);
    showImage($_GET['i'],$thisDirectory);
}

// Page view
if(!empty($_GET['p'])){
    showPageNumbers($numPages);
    showPage ($_GET['p'],$thisDirectory);
}
else{
    //showPage ($_GET['p'],$thisDirectory);
}

?>
