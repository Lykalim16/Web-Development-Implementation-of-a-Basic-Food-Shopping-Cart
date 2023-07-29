<?php
    session_start();
    $conn = new mysqli("localhost","root","","mp2_system");
    if($conn->connect_error){
        die("Connection FAILED".$conn->connect_error);
    }

    if (isset($_POST['pid'])) {
        $pid = $_POST['pid'];
        $pname = $_POST['pname'];
        $pprice = $_POST['pprice'];
        $pimage = $_POST['pimage'];
        $pcode = $_POST['pcode'];
        $pqty = 1;

        $stmt = $conn->prepare("SELECT product_code FROM cart WHERE product_code=?");
        $stmt -> bind_param("s", $pcode);
        $stmt-> execute();
        $res = $stmt->get_result();
        $r = $res->fetch_assoc();
        $code = $r['product_code'];

        if (!$code) {
            $query = $conn->prepare("INSERT INTO cart (product_name, product_price, product_image, qty, total_price, product_code) VALUES (?,?,?,?,?,?)");
            $query -> bind_param("sssiss", $pname, $pprice, $pimage, $pqty, $pprice, $pcode);
            $query -> execute();
                echo "<script>alert('Product successfully added to your cart :>')</script>";
                echo "<script>window.location = 'index.php'</script>";
        }else{
                echo "<script>alert('Product is already in your food cart :>')</script>";
                echo "<script>window.location = 'index.php'</script>";
        }
    }

    if (isset($_GET['cartItem']) && isset($_GET['cartItem']) == 'cart_item') {
        $stmt = $conn->prepare("SELECT * FROM cart");
        $stmt -> execute();
        $stmt -> store_result();
        $rows = $stmt->num_rows;

        echo $rows;
    }

    if (isset($_POST['qty'])) {
        $qty = $_POST['qty'];
        $pid = $_POST['pid'];
        $pprice = $_POST['pprice'];

        $totalprice = $qty*$pprice;

        $stmt = $conn->prepare("UPDATE cart SET qty=?, total_price=? WHERE id=?");
        $stmt->bind_param("isi",$qty,$totalprice,$pid);
        $stmt->execute();
        
    }

    if (isset($_GET['remove'])) {
        $id = $_GET['remove'];
        $stmt = $conn->prepare("DELETE FROM cart WHERE id=?");
        $stmt -> bind_param("i", $id);
        $stmt -> execute();
        header('location:cart.php');
    }


    if (isset($_POST['action']) && isset($_POST['action']) == 'order') {
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $products = $_POST['products'];
        $overall_total = $_POST['overall_total'];
        $address = $_POST['address'];

        $data = '';

        $stmt = $conn->prepare("INSERT INTO orders (name, phone, address, products, amount_paid) VALUES(?,?,?,?,?)");
        $stmt->bind_param("sssss", $name,$phone,$address,$products,$overall_total);
        $stmt->execute();
        $data = '
        <div class="text-center">
            <h1 class="display-4 mt-2 text-dark">Thank you for Purchasing!!</h1>
            <h3 class = "text-dark">Your driver will be there in 30 minutes</h3>
            <h4 class="bg-dark text-light rounded p-2">Today marks our semestral end for the 1st semester of school year 2020-2021. As a way to celebrate, aside from receiving your orders we have decided to pay you the total amount of your order. 
            Expect our payment to you upon the delivery as we live by our motto "We give what\'s ours". Thank you for dining with us. Till our next transaction, ciao.</h4>
            <h4>Your Contact number: '.$phone.'</h4>
            <h4>Total Amount: ₱'.$overall_total.'.00</h4>
        </div>';
        echo $data;
        echo '<footer>
        <div class="row text-center">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <p class="text-dark">Submitted by: Lyka Raquel Lim</p>
            </div>
            <div class="col-md-4"></div>
        </div>
    </footer>';
    }
?>