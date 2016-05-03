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
             print("<img src=\"$directory[$i]\" width=\"80%\">");
        }
    }
}

function showPageNumbers($numPages){
    for($i=1; $i<=$numPages; $i++){
        print("<a href=\"" .$_SERVER['SCRIPT_NAME']. "?p=" .$i. "\">" .$i. "</a> ");
    }
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
        print("<img src=\"$directory[$imgBefore]\" width=\"20%\">");
        print("</a> ");
    }

    if($imgCurrent < sizeof($directory)){
        print("<a href=\"" . $directory[$imgCurrent] ."\">");
        print("<img src=\"$directory[$imgCurrent]\" width=\"40%\">");
        print("</a> ");
    }    

    if($imgAfter < sizeof($directory)){
        $linkImgAfter = $imgAfter + 1;
        print("<a href=\"" .$_SERVER['SCRIPT_NAME']. "?i=" .$linkImgAfter. "\">");
        print("<img src=\"$directory[$imgAfter]\" width=\"20%\">");
        print("</a> ");
    }

}

//link to each image in list start at 1
function showImageNumbers($directory){
    for($i=1; $i<=sizeof($directory); $i++){
        print("<a href=\"" .$_SERVER['SCRIPT_NAME']. "?i=" .$i. "\">" .$i. "</a> ");
    }
    print("<br>");
}

function showSelector(){
     print("<a href=\"" .$_SERVER['SCRIPT_NAME']. "?i=1\">Image View</a> \n");
     print("<a href=\"" .$_SERVER['SCRIPT_NAME']. "?p=1\">Page View</a> \n");

}

$thisDirectory = dirList(dirname($_SERVER["SCRIPT_FILENAME"]));
$numPages = numberOfPages($thisDirectory);

//DEBUG: print(sizeof($thisDirectory));

// Select Image or Page view
showSelector();

// Image view

if($_GET['i']){
    showImageNumbers($thisDirectory);
    showImage($_GET['i'],$thisDirectory);
}

// Page view
if($_GET['p']){
    showPageNumbers($numPages);
    showPage ($_GET['p'],$thisDirectory);
}
else{
    //showPage ($_GET['p'],$thisDirectory);
}


//for($i=0; $i<10; $i++){
//print("<img src=\"$thisDirectory[$i]\" width=\"80%\">");
//}

//phpinfo();
?>
