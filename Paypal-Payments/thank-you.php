<!-- <!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Thank you page</title>
  </head>
  <body>
    <h1>
      Your Payment done.
      Thanks
    </h1>
  </body>
</html>
 -->

 <?php
 $tx=$_GET['tx'];
 // $tx="6CN12232T5449962K";
 $token="XT8WIpnAYC9kOJFUYg7qZAoTR0BBHrdl8_RpRKxXTuVzdsqX4Qp9Ja4azy0";
 $request = curl_init();

        // Set request options
        curl_setopt_array($request, array
        (
                CURLOPT_URL => 'https://www.sandbox.paypal.com/cgi-bin/webscr',
                CURLOPT_POST => TRUE,
                CURLOPT_POSTFIELDS => http_build_query(array
                (
                        'cmd' => '_notify-synch',
                        'tx' => $tx,
                        'at' => $token,
                )),
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_HEADER => FALSE,
              //  CURLOPT_SSL_VERIFYPEER => TRUE,
                //CURLOPT_CAINFO => 'cacert.pem',
        ));

        // Execute request and get response and status code
        $res = curl_exec($request);
        $status   = curl_getinfo($request, CURLINFO_HTTP_CODE);

        // Close connection
        curl_close($request);
        echo $res.' check';
if(!$res){
    echo 'error';
}else{
     // parse the data
    $lines = explode("\n", trim($res));
    $keyarray = array();
    if (strcmp ($lines[0], "SUCCESS") == 0) {
        for ($i = 1; $i < count($lines); $i++) {
            $temp = explode("=", $lines[$i],2);
            $keyarray[urldecode($temp[0])] = urldecode($temp[1]);
        }
    // check the payment_status is Completed
    // check that txn_id has not been previously processed
    // check that receiver_email is your Primary PayPal email
    // check that payment_amount/payment_currency are correct
    // process payment
    $firstname = $keyarray['first_name'];
    $lastname = $keyarray['last_name'];
    $itemname = $keyarray['item_name'];
    $amount = $keyarray['payment_gross'];
     
    echo ("<p><h3>Thank you for your purchase!</h3></p>");
     
    echo ("<b>Payment Details</b><br>\n");
    echo ("<li>Name: $firstname $lastname</li>\n");
    echo ("<li>Item: $itemname</li>\n");
    echo ("<li>Amount: $amount</li>\n");
    echo ("");
    }
    else if (strcmp ($lines[0], "FAIL") == 0) {
        // log for manual investigation
    }
}
 
?>

 
Your transaction has been completed, and a receipt for your purchase has been emailed to you.<br> You may log into your account at <a href='https://www.paypal.com'>www.paypal.com</a> to view details of this transaction.<br>