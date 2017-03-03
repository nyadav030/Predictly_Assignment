<?php
    $username = "root"; 
    $password = "";   
    $host = "localhost";
    $database="csv_import_demo";
    
    $server = @mysql_connect($host, $username, $password);
    $connection = @mysql_select_db($database, $server);

    $myquery = "
SELECT  SUM(Value) FROM  `sample` WHERE City='Bangalore'
";
    $query = mysql_query($myquery);
    
    if ( ! $query ) {
        echo mysql_error();
        die;
    }
    
    $data = array();
    
    for ($x = 0; $x < mysql_num_rows($query); $x++) {
        $data[] = mysql_fetch_assoc($query);
    }
       
    echo $data[0]['SUM(Value)']; 
     
    
?>