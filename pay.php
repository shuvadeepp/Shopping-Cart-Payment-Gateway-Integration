<?php 
session_start();
$jsonFile   = file_get_contents('product.json');
$jsonDecode = json_decode($jsonFile, TRUE);

if(isset($_POST['click_to_pay'])) {
    $your_name  = $_POST['your_name'];
    $your_phone = $_POST['your_phone'];
    $your_email = $_POST['your_email'];
    $hdn_price  = $_POST['hdn_price'];

    // echo $your_name . ' ' . $your_phone . ' ' . $your_email . ' ' . $hdn_price;exit;
}

include 'src/instamojo.php';

//$api = new Instamojo\Instamojo('YOU_PRIVATE_API_KEY', 'YOUR_PRIVATE_AUTH_TOKEN','https://test.instamojo.com/api/1.1/');
$api = new Instamojo\Instamojo('cf57724430c2708d78e245774f631429', 'fca56b59ec182b3d4a1bfc633f5fd1f4','https://test.instamojo.com/api/1.1/');


try {
    $response = $api->paymentRequestCreate(array(
        "purpose" => uniqid(),
        "amount" => $hdn_price,
        "buyer_name" => $your_name,
        "phone" => $your_phone,
        "send_email" => true,
        "send_sms" => true,
        "email" => $your_email,
        'allow_repeated_payments' => false,
        "redirect_url" => "http://192.168.203.143:7001/SHOOPING/thankyou.php",
        // "redirect_url" => "http://localhost:7001/SHOOPING/thankyou.php",
        "webhook" => "http://192.168.203.143:7001/SHOOPING/webhook.php"
    ));
    //print_r($response);
    // productProject\addToCard.php
    $pay_ulr = $response['longurl'];
    
    //Redirect($response['longurl'],302); //Go to Payment page

    header("Location: $pay_ulr");
    exit();

}
catch (Exception $e) {
    print('Error: ' . $e->getMessage());
}     
  ?>