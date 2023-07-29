<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main page</title>
    <!-- CSS Style -->
    <link rel="stylesheet" href="mystyle.css?v=<?php echo time(); ?>">
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
<div class="bg"></div>
    <div id = "msg"></div>
    <div class="row mt-4 pb-5">
        <?php
            $conn = new mysqli("localhost","root","","mp2_system");
            if($conn->connect_error){
                die("Connection FAILED!".$conn->connect_error);
            }
            $stmt = $conn->prepare("SELECT * FROM product");
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()):
        ?>
        <div class="col-sm-6 col-md-4 col-lg-3 mb-2">
            <div class="card-deck">
                <div class="card p-3 border-0 mb-2">
                    <img src="<?= $row['product_image']?>" class = "card-img-top" height = "270">
                    <div class="card-body p-2">
                        <h4 class = "card-title text-dark"><?= $row['product_name']?></h4>
                        <h5 class="card-subtitle mb-1 text-muted">₱<?= $row['product_price']?></h6>
                    </div>
                    <div class="card footer border-0">
                        <form action="" class = "form-submit">
                            <input type="hidden" class = "pid" value = "<?= $row['id'] ?>">
                            <input type="hidden" class = "pname" value = "<?= $row['product_name'] ?>">
                            <input type="hidden" class = "pprice" value = "<?= $row['product_price'] ?>">
                            <input type="hidden" class = "pimage" value = "<?= $row['product_image'] ?>">
                            <input type="hidden" class = "pcode" value = "<?= $row['product_code'] ?>">
                            <button class = "btn btn-outline-info my-1 addBtn text-dark"><i class = "fas fa-shopping-cart text-dark"></i> Add to Food Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile;?>
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
        $(".addBtn").click(function(a){
            a.preventDefault();
            var $form = $(this).closest(".form-submit");
            var pid = $form.find(".pid").val();
            var pname = $form.find(".pname").val();
            var pprice = $form.find(".pprice").val();
            var pimage = $form.find(".pimage").val();
            var pcode = $form.find(".pcode").val();

            $.ajax({
                url: 'add.php', method: 'post', data: {pid:pid, pname:pname, pprice:pprice, pimage:pimage, pcode:pcode}, 
                success:function(response){
                    $("#msg").html(response);
                    load_cart_item_number();
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