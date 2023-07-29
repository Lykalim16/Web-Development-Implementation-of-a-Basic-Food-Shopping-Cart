<?php
    $conn = new mysqli("localhost","root","","mp2_system");
    if($conn->connect_error){
        die("Connection FAILED".$conn->connect_error);
    }

    $overall_total = 0;
    $allItems = '';
    $items = array();

    $sql = "SELECT CONCAT(product_name, '(',qty,')') AS ItemQty, total_price FROM cart";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $overall_total = $overall_total + $row['total_price'];
        $items[] = $row['ItemQty'];
    }
    $allItems = implode(", ", $items);
?>

<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Placing Order</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css" integrity="sha512-rqQltXRuHxtPWhktpAZxLHUVJ3Eombn3hvk9PHjV/N5DMUYnzKPC1i3ub0mEXgFzsaZNeJcoE0YHq0j/GFsdGg==" crossorigin="anonymous" />
</head>
<body>
<nav class="navbar navbar-expand-md bg-light navbar-light">
  <!-- Brand -->
  <a class="navbar-brand" href="index.php"><i class = "fas fa-utensils"></i> Yummers</a>

  <!-- Toggler/collapsibe Button -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  <!-- Navbar -->
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link active" href="index.php">Products</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="cart.php"><i class = "fas fa-shopping-cart"> Cart<span class = "badge badge-light" id = "cart-item"></span></i>
        </a>
      </li>
    </ul>
  </div>
</nav> 
<div class="container">
    <div class="row justify-content-center">
        <div class = "col-lg-6 px-4 pb-4" id="review">
        <h4 class = "text-info p-2">Review and payment</h4>
        <table class="table-responsive mt-2">
        <table class="table table-bordered table-striped text-center">
            <thead class = "bg-dark">
            <tr class = "text-light">
                <th>Image</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total Price</th>
            </tr>
            </thead>
            <tbody>
                <?php
                    $conn = new mysqli("localhost","root","","mp2_system");
                    if($conn->connect_error){
                        die("Connection FAILED".$conn->connect_error);
                    }
                    $stmt = $conn->prepare("SELECT * FROM cart");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $overall_total = 0;
                    while ($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td><img src="<?=$row['product_image']?>" width = "100"></td>
                    <td><?=$row['product_name']?></td>
                    <td>₱<?=$row['product_price']?>.00</td>
                    <td><?=$row['qty']?></td>
                    <td>₱<?=$row['total_price']?>.00</td>
                </tr>
                <?php
                    $overall_total += $row['total_price'];
                ?>
                <?php endwhile; ?>
            </div>
            </tbody>
        </table>
    </table>
        </div>
        <div class="col-lg-6 px-4 pb-4" id="order">
            <h4 class = "text-info p-2">Delivery Details: </h4>
            <div class="p-2 mb-2 text-center">
                <?php
                    $overall_total = $overall_total - 100;
                ?>
                <h5><b>Total Amount: ₱</b><?=$overall_total?>.00</h5>
            </div>
            <form action="" method="post" id="placeOrder">
                <input type="hidden" name="products" value = "<?=$allItems;?>">
                <input type="hidden" name="overall_total" value = "<?=$overall_total;?>">
                <div class="form-group">
                    <input type="text" name="name" class="form-control" placeholder = "Enter Full Name" required>
                </div>
                <div class="form-group">
                    <input type="tel" name="phone" class="form-control" placeholder = "Contact Number" required>
                </div>
                <div class="form-group">
                    <textarea name="address" class = "form-control" rows = "3" cols = "10" placeholder = "Delivery Address"></textarea>
                </div>
                <div class="form-group">
                    <input type="submit" name="submit" value = "Place Order" class = "btn btn-info btn-block">
                </div>
            </form>
        </div>
    </div>
</div>

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<script type = "text/javascript">
    $(document).ready(function(){
        $("#placeOrder").submit(function(e){
            e.preventDefault();
            $.ajax({
                url: 'add.php', method: 'post', data: $('form').serialize()+"&action=order",
                success: function(response) {
                    $("#order").html(response);
                }
            });
        });
        load_cart_item_number();

        function load_cart_item_number() {
            $.ajax({
                url: 'add.php', method: 'get', data:{cartItem:"cart_item"}, success:function(response){
                    $("#cart-item").html(response);
                }
            });
        }
    });
</script>
</body>
</html>