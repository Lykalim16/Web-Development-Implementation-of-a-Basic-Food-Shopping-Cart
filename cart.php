<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart page</title>
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
        <a class="nav-link" href="index.php">Products</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="cart.php"><i class = "fas fa-shopping-cart"> Cart<span class = "badge badge-light" id = "cart-item"></span></i>
        </a>
      </li>
    </ul>
  </div>
</nav> 
<div class="container fluid">
    <div class="row px-5">
        <div class="col-md-11">
            <div class="shopping-cart">
                <h6>My Food Cart</h6>
                <hr>
            </div>
    <table class="table-responsive mt-2">
        <table class="table table-bordered text-center border-white">
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
                    <input type="hidden" class = "pid" value = "<?=$row['id']?>">
                    <td><img src="<?=$row['product_image']?>" width = "100"><hr><a href="add.php?remove=<?= $row['id']?>"><button type = "submit" class = "btn btn-dark mx-2" name = "remove"><i class="fas fa-trash-alt"></i>  Remove Item</button></a></td>
                    <td><?=$row['product_name']?></td>
                    <td>₱<?=$row['product_price']?>.00</td>
                    <input type="hidden" class = "pprice" value = "<?=$row['product_price']?>">
                    <td><input type="number" class = "form-control itemQty text-center" value = "<?=$row['qty']?>" style="width:70px;"></td>
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
    <!-- PRICE DETAILS -->
    <div class="pt-4">
                <h6>PRICE DETAILS</h6>
                <hr>
                <div class="row price-details">
                    <div class="col-md-6">
                        <h6>Price</h6>
                        <hr>
                        <h6>Discount (Sem-end Voucher)</h6>
                        <h6>Total amount</h6>
                    </div>
                    <div class="col-md-6">
                        <h6><?php
                            echo "₱$overall_total.00";
                        ?></h6>
                        <hr>
                        <h6>-₱100.00</h6>
                        <h6><?php
                            if ($overall_total < 1) {
                                echo "<b>₱$overall_total.00</b>";
                            }else{
                                $overall_total = $overall_total - (int)100;
                                echo "<b>₱$overall_total.00</b>";
                            }
                        ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="placeorder.php" class = "btn btn-warning <?=($overall_total < 1)?"disabled":"";?>"><i class="fab fa-paypal"></i> Place Order </a></h6>
                    </div>
                </div>
            </div>
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
        $(".itemQty").on('change',function(){
            var $el = $(this).closest('tr');
            var pid = $el.find(".pid").val();
            var pprice = $el.find(".pprice").val();
            var qty = $el.find(".itemQty").val();
            location.reload(true);
            $.ajax({
                url: 'add.php', method: 'post', cache: false, data: {qty:qty, pid:pid, pprice:pprice}, 
                success:function(response){
                    console.log(response);
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