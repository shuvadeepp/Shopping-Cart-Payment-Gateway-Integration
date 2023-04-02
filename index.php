
<?php
session_start();
// unset($_SESSION['save_item']);
$jsonFile   = file_get_contents('product.json');
$jsonDecode = json_decode($jsonFile, TRUE);


if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'add_to_cart'){
    
    $key = array_search($_REQUEST['product_id'], array_column($_SESSION['save_item'], 'product_id'));
    // echo'<pre>';print_r($_SESSION['save_item'][$key]['quantity'] + $_REQUEST['quantity']);exit;
    // echo'<pre>';print_r($_REQUEST['quantity']);exit;
    
    if((string)$key != '') {
        $_SESSION['save_item'][$key]['quantity'] = $_SESSION['save_item'][$key]['quantity'] + $_REQUEST['quantity'];
    } else {
        $_SESSION['save_item'][] = $_REQUEST;
    }
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Shop Page</title>
  <link rel="stylesheet" href="style.css" type="text/css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
</head>
<body>
  <header>
    <nav>
      <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#">Shop</a></li>
        <li><a href="#">Contact</a></li>
        <li>
            <a href="addToCard.php" class="btn btn-outline-success" id="my_card" target="_blank">MY CART(<?php echo (isset($_SESSION['save_item']))?count($_SESSION['save_item']):0;?>)</a>
        </li>
      </ul>
    </nav>
  </header>
  <div class="container">
  <?php if(!empty($jsonDecode)){ ?>
    <?php foreach($jsonDecode['products'] as $jsonDataKey => $jsonData) {  
        // echo'<pre>';print_r($jsonDataKey); ?>
    <div class="product">
        <form method="POST" action="">
            <input type="hidden" name="action" value="add_to_cart"/>
        
      <img src="<?php echo $jsonData['thumbnail']; ?>" alt="Product Image">
      <h2><?php echo $jsonData['title']; ?></h2>
      <p><?php echo $jsonData['description']; ?></p>
      <p style="color: green; font-weight: bold;">Price: Rs. <?php echo $jsonData['price']; ?></p>
      <p style="color: red; font-weight: bold;">Discount: <?php echo round($jsonData['discountPercentage']); ?></p>
        <div class="input-group mb-3">
            <span class="input-group-text"><i class="fa-sharp fa-solid fa-bag-shopping"></i></span>
            <input type="text" class="form-control" name="quantity" placeholder="Enter Quantity">
        </div>

      <input type="hidden" name="product_id" value="<?php  echo $jsonData['id'] ?>">
            <button type="submit" name="add_to_cart" value="submit" class="add_to_cart">Add to Cart</button>
    </form>
    </div>
    <?php } } ?>
    <!-- Add more products here -->
  </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</html>