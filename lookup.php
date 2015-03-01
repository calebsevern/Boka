<?php

/*
*  Appends queries to an arbitrary file for future use.
*/

function log_searches() {
  $current = file_get_contents("searches.txt");
  $current .= $_GET['isbn'] . "\n";
  file_put_contents("searches.txt", $current);
}


/*
*  Isn't this the same as kat_ph?
*  Deleted kat_ph.
*  Gets top listings from The Pirate Bay
*
*  @params:
*    title: book's title
*/
 function kat_ph_results($title) {
$url = "https://thepiratebay.se/search/" . rawurlencode($title) . "/0/99/0";
$a = file_get_contents($url);
$b = explode('<table id="searchResult">', $a);
$c = explode('<div class="detName">', $b[1]);
$results = array();
for($i=1; $i<count($c); $i++) {
$boom = explode('<a href="', $c[$i]);
$title = explode('>', $boom[1]);
$title = substr($title[1], 0, -3);
$boom = explode('<a href="magnet', $c[$i]);
$link = explode('"', $boom[1]);
$link = "magnet" . $link[0];
// echo "<br><br><br>";
// echo "<a href='" . $link . "'>Test</a>";
// echo "<br><br><br>";
$temp = json_encode(array('title' => $title, 'magnet' => $link));
array_push($results, $temp);
}
return json_encode($results);
}

/*
*  Gets top listings from Google Books
*  Usually only previews/snippets
*
*  @params:
*    title: book's title
*/

function google_results($title) {
  $url = "https://www.google.com/search?q=" . rawurlencode($title). "&btnG=Search+Books&tbm=bks&tbo=1";
  $a = file_get_contents($url);
  $b = explode('td"', $a);

  $c = explode('<div id="res">', $b[1]);
  $c = explode('<h3 class="r">', $c[1]);
  $results = array();
	
  for($i=1; $i<count($c); $i++) {
    $boom = explode('<a href="', $c[$i]);

    $title = explode('>', $boom[1]);
    

    $link = $title[0];

    $title = substr($title[1], 0, -2);
   # $boom = explode('<a href="magnet', $c[$i]);
   # $link = explode('"', $boom[1]);
   # $link = "magnet" . $link[0];
    if (trim($title) != ""){
 	$title = explode('<', $title);
 	$title = $title[0];
   	$temp = json_encode(array("title" => $title, 'magnet' => $link));
   	array_push($results, $temp);
   }
    

  }
	
  return json_encode($results);
}



/*
*  Gets top listing(s) from isohunt
*
*  @params:
*    title: book's title
*/

function iso_results($title) {
  $url = "https://isohunt.to/torrents/?ihq=" . rawurlencode($title);
  $a = file_get_contents($url);
  $b = explode('<table class="table-torrents table table-striped table-hover">', $a);
    $c = explode('<span class="torrent-icon torrent-icon-books">', $b[1]);
    $c = explode('<span>', $c[0]);
  
	
  $results = array();
	
  for($i=1; $i<count($c); $i++) {
    $boom = explode('<a href="', $c[$i]);
    // $title = explode('>', $boom[1]);
    // $title = substr($title[1], 0, -3);
    $title = $c[$i];
    $title = explode('</span>', $title);
    $title = $title[0];


    $boom = explode('<a href="', $c[$i]);

	$link = $boom[2];
$link = explode('">', $link);
$link = $link[0];

    

    //$link = "magnet" . $link[0];

    $temp = json_encode(array('title' => $title, 'magnet' => $link));
    array_push($results, $temp);
  }
	
  return json_encode($results);
}

 



/*
*  Retrieves a book title by ISBN 
*/

function fetch_title() {    
  $html = file_get_contents('http://www.isbnsearch.org/isbn/' . $_GET["isbn"]);
  $a = explode('<div class="bookinfo">', $html);
  $b = explode('<h2>', $a[1]);
  $c = explode('</h2>', $b[1]);
  return $c[0];
}


/*
*  Only continue search if title was found.
*  Also, log the search query in case we want that info.
* 
*  TODO: Move into seperate files
*    (e.g. google.php, kat.php, iso.php, kickass.php)
*    This way the Android app can make seperate IO
*    calls for each group instead of deciphering a 
*    clusterfuck of JSON arrays.
*/

$title = fetch_title();

if(trim($title) != "") {

    log_searches();
echo " THIS IS ISO\r\n", "<br>" , "<br>" ;
  echo iso_results($title);
  echo  " THIS IS THE PIRATE BAY\r\n ", "<br>" , "<br>" ;
  echo kat_ph_results($title);
  echo  " THIS IS GOOGLE\r\n", "<br>" , "<br>" ;
    echo google_results($title);

}
 





  