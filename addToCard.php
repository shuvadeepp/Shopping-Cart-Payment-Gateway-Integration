<?php
   session_start();
   
   $jsonFile   = file_get_contents('product.json');
   $jsonDecode = json_decode($jsonFile, TRUE);
   // echo'<pre>';print_r($_SESSION['save_item']);exit;

   /* ::::: REMOVE ITEM ::::: */
   if(isset($_POST['remove_btn'])){
      foreach($_SESSION['save_item'] as $ky => $vy){
         if($vy['product_id'] == $_POST['prod_id']) {
            // echo $ky;
            unset($_SESSION['save_item'][$ky]);
            header("Location: addToCard.php");
         }
      }
   }
?>
<script>
   /* ::::: Calculate Row Amount ::::: */
   function calculate(qty, price, discount, id){

      // console.table(qty, price, discount, id);

      var calculate_total_item   = 0;
      var qty_price              = qty * price;
      var discound_price         = qty_price * discount / 100;
      var total_price            = qty_price - discound_price;
      
      // alert(calculate_total_item)
      
      $('#total_price_per_Itm' + id).html(Math.round(total_price));
      $('#per_row_price' + id).val(Math.round(total_price));
      $('#per_row_price' + id).val(Math.round(total_price));

      amount_data();
   }

   /* ::::: Calculate Total Cart Amount ::::: */
   function amount_data() {
      var get_product_total    = 0;
      $(".per_row_price").each(function () {
            //console.log($(this).val()); 
            get_product_total += parseInt($(this).val());
      });
      // console.log(get_product_total);
      $('.total_item_price').text(" ₹ " + Math.round(get_product_total));
      $('#hdn_price').val(Math.round(get_product_total));

      // var total_item_price = document.getElementById("total_item_price").value;  
      // console.log(total_item_price);
   }

</script>

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
      <title>Your Cart - <?php echo count($_SESSION['save_item']); ?></title>
   </head>
   <body>
   <!-- <form method="post" action="pay.php"> -->
      <div class="container">
         <br><br><br>
         <div class="row justify-content-center">
            <div class="col-md-10">
               <!-- <b>ADD TO CARD</b> -->
               <div class="col-md-5 col-lg-12 order-md-last">
                  <h4 class="d-flex justify-content-between align-items-center mb-3">
                     <span class="text-primary">Your Cart</span>
                     <span class="badge bg-primary rounded-pill"><?php echo count($_SESSION['save_item']);?></span>
                  </h4>
               </div>
               
               <table class="table table-bordered border-primary" id="viewTableData">
                  <thead>
                     <tr>
                        <th scope="col">Sl#</th>
                        <th scope="col">Product Image</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Product Quantity</th>
                        <th scope="col">Product Price</th>
                        <th scope="col">Product Discount</th>
                        <th scope="col">Product Total</th>
                        <th scope="col">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                  <form method="post">
                  <?php 
                  if(!empty($_SESSION['save_item'])){
                     $calculate_total_item = 0;

                      foreach($_SESSION['save_item'] as $key => $viewItem) {
                        
                        // echo'<pre>';print_r($viewItem['quantity']);
                        $arrSearchKey = array_search($viewItem['product_id'], array_column($jsonDecode['products'], 'id'));
                        // echo'<pre>';print_r($jsonDecode['products'][$arrSearchKey]);
                          
                        //   echo'<pre>';print_r($_SESSION['save_item'][$searchSessKy]['product_id']);
                       
                     ?>
                     <tr>
                           <!-- :::: Serial No. :::: -->
                        <th scope="row"><?php echo $key+1 ?> </th>
                        <td>
                           <!-- :::: Product Image :::: -->
                           <img src="<?php echo $jsonDecode['products'][$arrSearchKey]['thumbnail']; ?>" alt="Product Image" height="50" width="120">
                        </td>
                        <td>
                           <!-- :::: Product Name :::: -->
                           <b><i><?php echo $jsonDecode['products'][$arrSearchKey]['title']; ?></i></b>
                        </td>
                        <td>
                           <!-- :::: Product Quantity	:::: -->
                           <div class="input-group">
                              <input type="number" class="form-control quantityVal" name="qty" id="qty<?php echo $jsonDecode['products'][$arrSearchKey]['id']; ?>" value="<?php echo $viewItem['quantity']; ?>" placeholder="Quantity" onchange="calculate(this.value, '<?php echo $jsonDecode['products'][$arrSearchKey]['price']; ?>', '<?php echo round($jsonDecode['products'][$arrSearchKey]['discountPercentage']); ?>', '<?php echo $jsonDecode['products'][$arrSearchKey]['id']; ?>')">
                           </div>
                        </td>
                        <td>
                           <!-- :::: Product Price :::: -->
                           <b><?php echo $jsonDecode['products'][$arrSearchKey]['price']; ?></b>

                           <input type="hidden" name="prod_price" id="prod_price<?php echo $jsonDecode['products'][$arrSearchKey]['id']; ?>" value="<?php echo $jsonDecode['products'][$arrSearchKey]['price']; ?>" >
                        </td>
                        <td>
                           <!-- :::: Product Discount :::: -->
                           <b><?php echo round($jsonDecode['products'][$arrSearchKey]['discountPercentage']); ?></b>

                           <input type="hidden" name="prod_discount" id="prod_discount<?php echo $jsonDecode['products'][$arrSearchKey]['id']; ?>" value="<?php echo round($jsonDecode['products'][$arrSearchKey]['discountPercentage']);?>" >
                        </td>
                        <?php 
                           /* ::::: DISCOUNT CALCULATION ::::: */   
                           $quantity = $viewItem['quantity'] * $jsonDecode['products'][$arrSearchKey]['price'];
                           $discountAmount = $quantity * (round($jsonDecode['products'][$arrSearchKey]['discountPercentage']) / 100); 
                           $finalPrice     = $quantity - $discountAmount; 
                           // echo round($finalPrice);
                           $calculate_total_item += round($finalPrice);
                           // echo $calculate_total_item;
                           ?>
                        <td>
                           <!-- :::: Product Total :::: -->
                           <b id="total_price_per_Itm<?php echo $jsonDecode['products'][$arrSearchKey]['id']; ?>"> <?= round($finalPrice); ?> </b> 
                           
                           <input type="hidden" class="per_row_price" name="per_row_price" id="per_row_price<?php echo $jsonDecode['products'][$arrSearchKey]['id']; ?>" value="<?= round($finalPrice); ?>">
                        </td>
                        <td>
                           <!-- :::: Action :::: -->
                           <!-- <input type="submit" class="btn btn-danger" name="remove_btn" value="Remove"> -->
                           <button type="submit" class="btn btn-danger" name="remove_btn"><i class="fa-solid fa-trash" style="color: #ffffff;"></i></button>
                           <input type="hidden" name="prod_id" value="<?php echo $viewItem['product_id'] ?>">   
                        </td>
                     </tr>
                     <?php }} else { ?>
                        <div class="no-records" style="text-align: center;"> <h>Your Cart is Empty</h></div>
                     <?php }  ?>
                  </form>
                  </tbody>
               </table>
               

            <!-- :::: TOTAL AMOUNT CALCULATION SHOW :::: -->
            <ul class="list-group mb-3">
               <li class="list-group-item d-flex justify-content-between lh-sm">
                  <span> <b> Total (INR) </b> </span>
                  <!-- :::: TOTAL ITEM PRICE :::: -->
                  <strong class="total_item_price" id="total_item_price">₹ <?= (!empty($calculate_total_item) ? $calculate_total_item : 'Please Add Item') ?> </strong>
               </li>
            </ul>

            <!-- :::: YOUR PAYMENT DETAILS :::: -->
            <form method="post" action="pay.php">
               <div class="container">
                  <div class="row justify-content-center">
                     <div class="col-md-6">
                        <h3> <i>Your Payment Details:-</i></h3>
                        <div class="form-group">
                           <label>Your Name</label>
                              <input type="text" class="form-control" name="your_name" placeholder="Enter your name">	 <br/>
                        </div>   
                        
                        <div class="form-group">
                           <label>Your Phone</label>
                              <input type="text" class="form-control" name="your_phone" placeholder="Enter your phone number" maxlength="10"> <br/>
                        </div>


                        <div class="form-group">
                           <label>Your Email</label>
                              <input type="email" class="form-control" name="your_email" placeholder="Enter you email address"> <br/>
                        </div>

                        <div class="form-group">
                        <input type="submit" class="btn btn-success btn-lg click_to_pay" id="click_to_pay" name="click_to_pay" value="Click here to Pay">


                        <input type="hidden" name="hdn_price" id="hdn_price" value="<?php echo (!empty($calculate_total_item) ? $calculate_total_item : 'Please Add Item'); ?>">
                        </div>
                     </div>
                  </div>
               </div>
               </form>
            </div>
         </div>
      </div> 
   </body>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

