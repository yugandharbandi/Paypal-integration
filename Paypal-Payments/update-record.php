<?php
	
//make the user as member
function insert_into_database($email_id, $subsc_time){
	require 'db_connect.php';
	$date=date_create($subsc_time);
	$data= date_format($date,"Y-m-d");
	$query= "UPDATE `seller` SET purchased='1', latest_subs_data='$data' where email='$email_id'";
		mysql_query($query);
}

//array containing the details of transactions(posted by IPN from Paypal)
$array = $_POST;
$encodedString = json_encode($array);

//Insert the contents into a file(just for debugging)
file_put_contents('myfile.txt', $encodedString);

//verify whether the details sent by Paypal are true or not by making a request to paypal and procees if the status is success

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "cmd=_notify-validate&" . http_build_query($_POST));
	$response = curl_exec($ch);
	curl_close($ch);

//Extract amount paid
		 if(isset($_POST['mc_amount1'])){
		 	$price = $_POST['mc_amount1'];
		 }
		 elseif (isset($_POST['mc_gross'])) {
		 	$price = $_POST['mc_gross'];
		 }
		 else{
		 	$price=0;
		 }
//check if the transaction is verified and the message is received b the right recipient
	if ((strcasecmp($response,"verified")==0) && $_POST['receiver_email'] == "nasreen-facilitator@sellergyan.com") {
		 
		 //insert into the database
		$to =  $_POST['payer_email'];
		 insert_into_database($to, $_POST['payment_date']);
		
		 //mail the user
		 $cEmail = "yugandhar.bunny.2220@gmail.com";
		 $subscr_id=$_POST['subscr_id'];
		 $name = $_POST['first_name'] . " " . $_POST['last_name']." ".$subscr_id;
		 $currency = $_POST['mc_currency'];
		 $item = $_POST['item_number'];
		$type=$_POST['txn_type'];

         $subject = "Regarding your subscription for $type";
    	 if(strcmp("subscr_payment", $type)==0){
         $message = "<b>Hey $name,</b><br>Your payment for <b>$item</b> is done<br><b>Order Details:</b><br><b>Item: </b>$item<br><b>Price: </b>$price";
        
    	 }
    	 if(strcmp("subscr_signup", $type)==0){
    	 	$message="Thanks for subscribing. You would shortly receive subscription details from us.";
        
    	 }
         elseif (strcmp($type,"subscr_cancel")==0) {
         $message = "<b>Hey $name,</b><br>";
         $message .= "Your unsubscription for <b>$item</b> is done<br>";
         }
         
         $header = "From:sellergyan@seller.com \r\n";
         // $header .= "Cc:afgh@somedomain.com \r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";
         if (strpos($a, 'Your') !== false) {
    			$message.= "  Yes";
			}
         $retval = mail ($to,$subject,$message,$header);
         
         if( $retval == true ) {
            echo "Message sent successfully...";
           // file_put_contents('myfile.txt', "Success");
         }else {
            echo "Message could not be sent...";
         }

}


?>
