<?php


  /**
  * Searches thepiratebay.org and returns top 3 results,
  * sorted by seeds (high -> low)
  *
  * @params
  *   title: Full title of book (string)
  **/
  
  function search($title) {
    
    $html = file_get_contents("https://thepiratebay.se/search/" . rawurlencode($title) . "/0/99/0");
    $b = explode('<table id="searchResult">', $html);
    $c = explode('<div class="detName">', $b[1]);
    
    $results = array();
    
    $count = count($c);
    if(count($c) > 4)
        $count = 4;
        
    for($i=1; $i < $count; $i++) {
      $boom = explode('<a href="', $c[$i]);
      $title = explode('>', $boom[1]);
      $title = substr($title[1], 0, -3);
      
      $boom = explode('<a href="magnet', $c[$i]);
      $link = explode('"', $boom[1]);
      $link = "magnet" . $link[0];
      
      $temp = json_encode(array('title' => $title, 'magnet' => $link));
      array_push($results, $temp);
    }
    
    return json_encode($results);
  }

  
  /**
  * Returns a book's title, given its ISBN. 
  * Scraped fro isbnsearch.org.
  *
  * @params:
  *   isbn: 10 or 13 digit ISBN (string)
  **/

  function isbn_to_title($isbn) {    
    $html = file_get_contents('http://www.isbnsearch.org/isbn/' . $_GET["isbn"]);
    $a = explode('<div class="bookinfo">', $html);
    $b = explode('<h2>', $a[1]);
    $c = explode('</h2>', $b[1]);
    return $c[0];
  }
  
  
  echo search(isbn_to_title($_GET['isbn']));  