<?php

    require "twitteroauth-0.5.1/autoload.php";
    use Abraham\TwitterOAuth\TwitterOAuth;
    
    $html = file_get_contents("http://www.isbnsearch.org/isbn/" . $_GET['isbn']);
    $a = explode('<div class="bookinfo">', $html);
    $b = explode('<h2>', $a[1]);
    $c = explode('</h2>', $b[1]);
    $title = $c[0];
    if(strlen($title) > 100)
        $title = substr($title, 0, 100) . "...";
    
    
    $a = explode('Price:</strong>', $html);
    $b = explode('</p>', $a[1]);
    $cost = $b[0];
    
    //Try alternate price source
    if(trim($cost) == "") {
        $a = explode('<p class="pricelink">', $html);
        $b = explode('>', $a[1]);
        $c = explode('<', $b[1]);
        $cost = $c[0];
    }
    
    
    if(trim($title) != "" && trim($cost) != "") {
        
        echo "Success.";
        
        $connection = new TwitterOAuth("r0tuiV8RgJJdjylhqBp5GUpgU", "1o2WTRTRZDqlgjbjQnHEoqVHedvpqsyHWyzE8OIcUgGtzMoany", "3052053793-E2uEdA0OQQS2r0LfGtWT1qvZrZVUFxbtm37xFco", "9WkxmtXhBRYJikcs8yddyZQIXc0twLLT8fl2YwcZZAwCa");
        $content = $connection->get("account/verify_credentials");
        
        $statues = $connection->post("statuses/update", array("status" => $cost . " - " . $title));
        
    } else if(trim($title) != "") {
        echo "Success.";
                
        $connection = new TwitterOAuth("r0tuiV8RgJJdjylhqBp5GUpgU", "1o2WTRTRZDqlgjbjQnHEoqVHedvpqsyHWyzE8OIcUgGtzMoany", "3052053793-E2uEdA0OQQS2r0LfGtWT1qvZrZVUFxbtm37xFco", "9WkxmtXhBRYJikcs8yddyZQIXc0twLLT8fl2YwcZZAwCa");
        $content = $connection->get("account/verify_credentials");
        
        $statues = $connection->post("statuses/update", array("status" => $title));
        
    } else {
        echo "Failure.";
    }  
    
    
    
    
    
    
    
    