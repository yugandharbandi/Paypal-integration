<?php 
          include 'process_pdt.function.php';

$payment_data = isset($_GET['tx'])
        ? process_pdt($_GET['tx'])
        : FALSE;

$current_url = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8" />
        <title>Thank you</title>

        
        <style>
                form
                {
                        float: right;
                        margin: 0 3em 0 4em;
                }
        </style>

</head>
<body>
        <!-- <h1>Thank you!!</h1> -->

        <?php if($payment_data)
                printf('<p>Thank you %s and welcome back.You can now login to our tool to have an amazing experience</p>', $payment_data['first_name'], $payment_data['mc_gross'], $payment_data['mc_currency']);
                
                 ?>

        

        <?php if($_GET): ?>
        <hr/>
        <h2>Details</h2>

         <pre>GET: <?php print_r($_GET) ?></pre>
        <pre>PDT: <?php echo print_r($payment_data) ?></pre> 
        <?php endif ?>

        
</body>
</html>