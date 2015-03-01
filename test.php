
	
<?php

  function isbn_to_title($isbn) {    
	$html = file_get_contents('http://www.isbnsearch.org/isbn/' . $_GET["isbn"]);
	$a = explode('<div class="bookinfo">', $html);
	$b = explode('<h2>', $a[1]);
	$c = explode('</h2>', $b[1]);
	return $c[0];
  }
  
  
 $title = isbn_to_title($_GET['isbn']);

$curl = curl_init("https://isohunt.to/torrents/?ihq=" . rawurlencode($title));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
 
$page = curl_exec($curl);
 
if(curl_errno($curl)) // check for execution errors
{
    echo 'Scraper error: ' . curl_error($curl);
    exit;
}
 
curl_close($curl);
 
$regex = '/<table class="table-torrents table table-striped table-hover">(.*?)<\/table>/s';
if ( preg_match($regex, $page, $list) )
	for ($i = 0; $i < count($list); $i++) {
    echo $list[$i];
}
else
    print "Not found";
?>