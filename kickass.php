<?php

	$file = 'searches.txt';
	$current = file_get_contents($file);
	$current .= $_GET['isbn'] . "\n";
	file_put_contents($file, $current);

 //  function kickass_results($title) {
	// $url = "https://www.google.com/search?q=" . rawurlencode($title). "&btnG=Search+Books&tbm=bks&tbo=1";
	// $a = file_get_contents($url);
	// $b = explode('td"', $a);
	// print_r($b);
	// $c = explode('<div class="detName">', $b[1]);
	
	// $results = array();
	
	// for($i=1; $i<count($c); $i++) {
	//   $boom = explode('<a href="', $c[$i]);
	//   $title = explode('>', $boom[1]);
	//   $title = substr($title[1], 0, -3);
	  
	//   $boom = explode('<a href="magnet', $c[$i]);
	//   $link = explode('"', $boom[1]);
	//   $link = "magnet" . $link[0];
	//  // echo "<br><br><br>";
	//  // echo "<a href='" . $link . "'>Test</a>";
	//  // echo "<br><br><br>";
	  
	//   $temp = json_encode(array('title' => $title, 'magnet' => $link));
	//   array_push($results, $temp);
	// }
	
	// return json_encode($results);
 //  }

  function iso_results($title) {
	$url = "https://www.google.com/search?q=" . rawurlencode($title). "&btnG=Search+Books&tbm=bks&tbo=1";
	$a = file_get_contents($url);
	$b = explode('td"', $a);
	print_r($b);
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


  function isbn_to_title($isbn) {    
	$html = file_get_contents('http://www.isbnsearch.org/isbn/' . $_GET["isbn"]);
	$a = explode('<div class="bookinfo">', $html);
	$b = explode('<h2>', $a[1]);
	$c = explode('</h2>', $b[1]);
	return $c[0];
  }
 //  function isbn_to_title($isbn) {    
	// $html = file_get_contents('http://www.isbnsearch.org/isbn/' . $_GET["isbn"]);
	// $a = explode('<div class="bookinfo">', $html);
	// $b = explode('<h2>', $a[1]);
	// $c = explode('</h2>', $b[1]);
	// return $c[0];
 //  }
  
  
  $title = isbn_to_title($_GET['isbn']);
  
  $c = "https://kickass.to/usearch/" . rawurlencode($title) . "category:books/";

   $a = kickass_results($title);
   echo $a;
  