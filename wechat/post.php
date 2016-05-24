<?php
	if(isset($_POST['post_type'])){
		$post_type = $_POST['post_type'];
	}
	
	$mysql_connection = "mysqli";
	switch($post_type){
		case "Database":
			$table = $_POST['data_table'];
			$fields = $_POST['data_fields'];
			$values = $_POST['data_values'];
			include("connection.php");
			$query_fields = "";
			$query_values = "";
			foreach ($fields as $value) {
				$query_fields .= $value.',';
			} // foreach fields
			$query_fields = rtrim($query_fields, ', ');
			
			if($mysql_connection=="mysqli"){
				
				foreach ($values as $value) {
					$query_values .= mysqli_real_escape_string($connection, $value)."', '";
				} // foreach values
			    $query_values = rtrim($query_values, ', ');
			  
				$sql = "INSERT INTO ".$table." (".$query_fields.") VALUES ('".$query_values."')";
				$result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
			}else{
				foreach ($values as $value) {
					$query_values .= mysql_real_escape_string($value)."', '";
				} // foreach values
			    $query_values = rtrim($query_values, ', ');
			  
				$sql = "INSERT INTO ".$table." (".$query_fields.") VALUES ('".$query_values."')";
				$result = mysql_query($sql) or die(mysql_error());
			}
			echo "<h2>Data Sent.</h2>";
			
			break;
		case "Email":
			$body = "<html><head></head><body>";
			$body .= $_POST['data_email_body'];
			$body .= "</body></html>";
			$to = $_POST['data_email_to'];
			$subject = "Your Subject";
			$mailheaders = "MIME-Version: 1.0" . "\r\n";
			$mailheaders .= "Content-type:text/html;charset=ISO-8859-1". "\r\n";
			$mailheaders .= "From: www.yourdomain.com <email@yourdomain.com> \n";			
			$mailheaders .= "Reply-To: reply@replydomain.com\n\n";
			//echo $body;
			mail($to, $subject, $body, $mailheaders);
			echo "<h2>Email Sent.</h2>";
			break;
		case "Both":
			$table = $_POST['data_table'];
			$fields = $_POST['data_fields'];
			$values = $_POST['data_values'];
			include("connection.php");
			$query_fields = "";
			$query_values = "";
			foreach ($fields as $value) {
				$query_fields .= $value.',';
			} // foreach fields
			$query_fields = rtrim($query_fields, ', ');
			
			if($mysql_connection=="mysqli"){
				
				foreach ($values as $value) {
					$query_values .= mysqli_real_escape_string($connection, $value)."', '";
				} // foreach values
			    $query_values = rtrim($query_values, ", ''");
			  
				$sql = "INSERT INTO ".$table." (".$query_fields.") VALUES ('".$query_values."')";
				$result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
			}else{
				foreach ($values as $value) {
					$query_values .= mysql_real_escape_string($value)."', '";
				} // foreach values
			    $query_values = rtrim($query_values, ', ');
			  
				$sql = "INSERT INTO ".$table." (".$query_fields.") VALUES ('".$query_values."')";
				$result = mysql_query($sql) or die(mysql_error());
			}
			echo "<h2>Data Saved</h2>";
			$body = "<html><head></head><body>";
			$body .= $_POST['data_email_body'];
			$body .= "</body></html>";
			$to = $_POST['data_email_to'];
			$subject = "Your Subject";
			$mailheaders = "MIME-Version: 1.0" . "\r\n";
			$mailheaders .= "Content-type:text/html;charset=ISO-8859-1". "\r\n";
			$mailheaders .= "From: www.yourdomain.com <email@yourdomain.com> \n";			
			$mailheaders .= "Reply-To: reply@replydomain.com\n\n";
			//echo $body;
			mail($to, $subject, $body, $mailheaders);
			echo "<h2>Email Sent.</h2>";
			
			break;
        case "insert":
            include("connection.php");
            $sql = "INSERT INTO cms_consultation (name,sex,age,city,phone,id_card,order_code,diagnose,description,before_treat,demand,doctor_id,open_id) VALUE( '$_POST[name]','$_POST[sex]','$_POST[age]','$_POST[city]','$_POST[phone]','$_POST[id_card]','$_POST[order_code]','$_POST[diagnose]','$_POST[description]','$_POST[before_treat]','$_POST[demand]')";
            $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
            if($result){

                $msg ="尊敬的".$_POST["name"]."<br/>您请提交的求诊信息已经收到！<br/>医生将会在2个工作日内处理或联系您。<br/>为更好服务您，系统自动为您服务";
                $result=true;
            }
            else{
                $result=false;
                $msg="提交失败";
            }
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(array('st'=>$result,'msg'=>$msg)));
	}


?>