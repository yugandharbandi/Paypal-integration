<?php 

define("TOKEN", "XT8WIpnAYC9kOJFUYg7qZAoTR0BBHrdl8_RpRKxXTuVzdsqX4Qp9Ja4azy0");

function insert_into_database($email_id, $subsc_time){
	require 'db_connect.php';
	$date=date_create($subsc_time);
	$data= date_format($date,"Y-m-d");
	$query= "UPDATE `seller` SET purchased='1', latest_subs_data='$data' where email='$email_id'";
		mysql_query($query);
}

function process_pdt($tx)
{
        $array = array(
                        'cmd' => '_notify-synch',
                        'tx' => $tx,
                        'at' => TOKEN
                );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($array));
        $response = curl_exec($ch);
        curl_close($ch);
        file_put_contents('file.txt', $response);
        // Validate response
        if(strpos($response, 'SUCCESS') === 0)
        {
               // 
                // Remove SUCCESS part (7 characters long)
                $response = substr($response, 7);

                // Urldecode it
                $response = urldecode($response);

                // Turn it into associative array
                preg_match_all('/^([^=\r\n]++)=(.*+)/m', $response, $m, PREG_PATTERN_ORDER);
                $response = array_combine($m[1], $m[2]);

                // Fix character encoding if needed
                if(isset($response['charset']) AND strtoupper($response['charset']) !== 'UTF-8')
                {
                        foreach($response as $key => &$value)
                        {
                                $value = mb_convert_encoding($value, 'UTF-8', $response['charset']);
                        }

                        $response['charset_original'] = $response['charset'];
                        $response['charset'] = 'UTF-8';
                }

                // Sort on keys
                ksort($response);
				insert_into_database($response['payer_email'],$response['payment_date']);
                // return response
                return $response;
        }

        return FALSE;
}