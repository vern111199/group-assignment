<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if (isset($_POST['checkout'])) {
   // Assuming you have stored the user's ID in the $user_id variable or retrieved it from the session.
   $user_id = $_SESSION['user_id'];
   $quantity = 1;
   $total_price = 100;
   $current_datetime = date('Y-m-d H:i:s');

   $insert_query = "INSERT INTO orders (user_id, total_products, total_price, placed_on) VALUES ('$user_id', $quantity, $total_price, NOW())";
   mysqli_query($conn, $insert_query) or die('query failed');

   // After completing the checkout process, you can clear the cart similarly to the example you provided.

   // Clear the cart from the database for the current user.
   $clear_cart_query = "DELETE FROM cart WHERE user_id = '$user_id'";
   mysqli_query($conn, $clear_cart_query) or die('query failed');

   // Clear the cart by removing all items from the session.
   $_SESSION['cart'] = array();
}

// Clear Cart Function
if (isset($_POST['clear_cart'])) {
    // Clear the cart by removing all items from the database for the current user
    mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$user_id'") or die('query failed');
    // Clear the cart by removing all items from the session
    $_SESSION['cart'] = array();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="cart">

   <div class="box-container">

   <?php  
      $select_cart = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id' LIMIT 6") or die('query failed');
      if(mysqli_num_rows($select_cart) > 0){
         while($fetch_cart = mysqli_fetch_assoc($select_cart)){
            // Calculate the total price for each product
            $total_price = $fetch_cart['quantity'] * $fetch_cart['price'];
            ?>

            <div class="box">
                  <img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="">
                  <div class="name"><?php echo $fetch_cart['name']; ?></div>
                  <div class="price">$<?php echo $fetch_cart['price']; ?></div>
                  <div class="quantity">Qty: <?php echo $fetch_cart['quantity']; ?></div>
                  <div class="total-price">Total: $<?php echo $total_price; ?></div>
            </div>

            <?php
         }
 
         ?>
         <?php
      } else {
         echo '<p class="empty">Your cart is empty.</p>';
      }
   ?>

   </div>

   <div class="button-container">

      <!-- "Checkout" button -->
      <form action="" method="post">
         <button type="submit" name="checkout" class="button" a href="orders.php">Checkout</button>
      </form>
               
      <!-- "Return to Shop" button -->
      <a href="shop.php" class="button">Return to Shop</a>

      <!-- "Clear Cart" button -->
      <form action="" method="post">
         <button type="submit" name="clear_cart" class="button">Clear Cart</button>
      </form>

   </div>

</section>


<section class="home-contact">

   <div class="content">
      <h3>have any questions?</h3>
      <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Atque cumque exercitationem repellendus, amet ullam voluptatibus?</p>
      <a href="contact.php" class="white-btn">contact us</a>
   </div>

</section>



<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>