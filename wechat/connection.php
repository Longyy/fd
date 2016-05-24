<?php

    $connection = "mysqli";
    $server = "127.0.0.1";
    $db_user = "root";
    $db_password = "root";
    $db_name = "fd";
	
	if($connection == "mysqli"){
		// connect using mysqli
//        header ( "Content-type:text/html;charset=utf-8" );  //统一输出编码为utf-8
		$connection = mysqli_connect($server, $db_user, $db_password, $db_name);
        mysqli_query($connection,'set names utf8');
		$db = mysqli_select_db($connection, $db_name);
	} else if($connection == "mysql"){
		// connect using mysql
		$connection = mysql_connect($server, $db_user, $db_password);
		$db = mysql_select_db($db_name, $connection);
	}

?>