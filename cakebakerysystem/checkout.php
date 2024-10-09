<?php
session_start();
error_reporting(0);
include_once('includes/dbconnection.php');
if (strlen($_SESSION['fosuid']==0)) {
  header('location:logout.php');
  } else{ 

//placing order

if(isset($_POST['placeorder'])){
//getting address
$fnaobno=$_POST['flatbldgnumber'];
$street=$_POST['streename'];
$area=$_POST['area'];
$lndmark=$_POST['landmark'];
$city=$_POST['city'];
$cod=$_POST['cod'];
$userid=$_SESSION['fosuid'];
//genrating order number
$orderno= mt_rand(100000000, 999999999);
$query="update tblorders set OrderNumber='$orderno',IsOrderPlaced='1',CashonDelivery='$cod' where UserId='$userid' and IsOrderPlaced is null;";
$query.="insert into tblorderaddresses(UserId,Ordernumber,Flatnobuldngno,StreetName,Area,Landmark,City) values('$userid','$orderno','$fnaobno','$street','$area','$lndmark','$city');";

$result = mysqli_multi_query($con, $query);
if ($result) {

echo '<script>alert("Your order placed successfully. Order number is "+"'.$orderno.'")</script>';
echo "<script>window.location.href='my-order.php'</script>";

}
}    

    ?>
<!DOCTYPE html>
<html lang="en">
    
<head>
        <title>Cake Bakery System || Checkout Page</title>

        <!-- Icon css link -->
        <link href="css/font-awesome.min.css" rel="stylesheet">
        <link href="vendors/linearicons/style.css" rel="stylesheet">
        <link href="vendors/flat-icon/flaticon.css" rel="stylesheet">
        <link href="vendors/stroke-icon/style.css" rel="stylesheet">
        <!-- Bootstrap -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Rev slider css -->
        <link href="vendors/revolution/css/settings.css" rel="stylesheet">
        <link href="vendors/revolution/css/layers.css" rel="stylesheet">
        <link href="vendors/revolution/css/navigation.css" rel="stylesheet">
        <link href="vendors/animate-css/animate.css" rel="stylesheet">
        
        <!-- Extra plugin css -->
        <link href="vendors/owl-carousel/owl.carousel.min.css" rel="stylesheet">
        <link href="vendors/magnifc-popup/magnific-popup.css" rel="stylesheet">
        <link href="vendors/jquery-ui/jquery-ui.min.css" rel="stylesheet">
        <link href="vendors/nice-select/css/nice-select.css" rel="stylesheet">
        
        <link href="css/style.css" rel="stylesheet">
        <link href="css/responsive.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        
        <!--================Main Header Area =================-->
		<?php include_once('includes/header.php');?>
        <!--================End Main Header Area =================-->
        
        <!--================End Main Header Area =================-->
        <section class="banner_area">
        	<div class="container">
        		<div class="banner_text">
        			<h3>Checkout</h3>
        			<ul>
        				<li><a href="index.php">Home</a></li>
        				<li><a href="checkout.php">Checkout</a></li>
        			</ul>
        		</div>
        	</div>
        </section>
        <!--================End Main Header Area =================-->
        
        <!--================Billing Details Area =================-->    
        <?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['placeorder'])) {
    // Get the current time and day of the week
    date_default_timezone_set('Asia/Kolkata'); // Set your timezone
    $current_time = date("H:i");
    $current_day = date("l"); // Get the full name of the day

    // Define the time constraints
    $weekday_start_time = "08:00";
    $weekday_end_time = "20:00";
    $saturday_start_time = "09:00";
    $saturday_end_time = "16:00";

    // Check the time constraints based on the day of the week
    $is_valid_time = false;
    if ($current_day == "Sunday") {
        echo "<script>alert('The shop is closed on Sunday. Please try again another day.'); window.history.back();</script>";
        exit();
    } elseif ($current_day == "Saturday") {
        if ($current_time >= $saturday_start_time && $current_time <= $saturday_end_time) {
            $is_valid_time = true;
        }
    } else {
        if ($current_time >= $weekday_start_time && $current_time <= $weekday_end_time) {
            $is_valid_time = true;
        }
    }

    // If the time is valid, proceed with placing the order
    if ($is_valid_time) {
        // Your existing code to place the order
        $userid = $_SESSION['fosuid'];
        $query = mysqli_query($con, "SELECT tblfood.Image, tblfood.ItemName, tblfood.ItemDes, tblfood.Weight, tblfood.ItemPrice, tblfood.ItemQty, tblorders.FoodId FROM tblorders JOIN tblfood ON tblfood.ID=tblorders.FoodId WHERE tblorders.UserId='$userid' AND tblorders.IsOrderPlaced IS NULL");
        $num = mysqli_num_rows($query);
        $grandtotal = 0;
        $cnt = 1;

        if ($num > 0) {
            while ($row = mysqli_fetch_array($query)) {
                $total = $row['ItemPrice'];
                $grandtotal += $total;
                $cnt++;
            }
        }

        // Insert order into database or update the order status here
        // Assuming you have an order insertion/update code here

        echo "<script>alert('Your order has been placed successfully!'); window.location.href='your_order_success_page.php';</script>";
    } else {
        echo "<script>alert('Orders can only be placed between 8 AM and 8 PM on weekdays, 9 AM and 4 PM on Saturdays.'); window.history.back();</script>";
    }
}
?>


<section class="billing_details_area p_100">
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <div class="main_title">
                    <h2>Billing Details</h2>
                </div>
                <div class="billing_form_area">
                    <form class="billing_form row" action="" method="post" id="contactForm">
                        <div class="form-group col-md-6">
                            <label for="first">Flat or Building Number *</label>
                            <input type="text" name="flatbldgnumber" placeholder="Flat or Building Number" class="form-control" required="true">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="last">Street Name *</label>
                            <input type="text" name="streename" placeholder="Street Name" class="form-control" required="true">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="company">Area</label>
                            <input type="text" name="area" placeholder="Area" class="form-control" required="true">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="address">Landmark if any</label>
                            <input type="text" name="landmark" placeholder="Landmark if any" class="form-control">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="city">Town / City *</label>
                            <input type="text" name="city" placeholder="City" class="form-control" required="true">
                        </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="order_box_price">
                    <div class="main_title">
                        <h2>Your Order</h2>
                    </div>
                    <div class="payment_list">
                        <div class="price_single_cost">
                            <h5>Product <span>Total</span></h5>
                            <?php 
                            $userid = $_SESSION['fosuid'];
                            $query = mysqli_query($con, "select tblfood.Image,tblfood.ItemName,tblfood.ItemDes,tblfood.Weight,tblfood.ItemPrice,tblfood.ItemQty,tblorders.FoodId from tblorders join tblfood on tblfood.ID=tblorders.FoodId where tblorders.UserId='$userid' and tblorders.IsOrderPlaced is null");
                            $num = mysqli_num_rows($query);
                            if($num > 0){
                                while ($row = mysqli_fetch_array($query)) {
                            ?>
                            <h5><?php echo $row['ItemName']?> <span>₹<?php echo $total = $row['ItemPrice'];?>
                            <?php 
                            $grandtotal += $total;
                            $cnt = $cnt + 1; 
                            ?>
                            </span></h5>
                            <?php 
                                $cnt++; 
                                } 
                            } 
                            ?>
                            <h4>Subtotal <span>₹<?php echo $grandtotal;?></span></h4>
                            <h5>Shipping And Handling<span class="text_f">Free Shipping</span></h5>
                            <h3>Total <span>₹<?php echo $grandtotal;?></span></h3>
                        </div>
                        <div id="accordion" class="accordion_area">
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <h5 class="mb-0">
                                        <input type="checkbox" name="cod" id="cod" value="Cash on Delivery" required="true">
                                        Cash on Delivery(cod)
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <button type="submit" value="submit" name="placeorder" class="btn pest_btn">Place Order</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

        <!--================End Billing Details Area =================-->   
        
       <?php include_once('includes/footer.php');?>
        
        
        
        
        
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="js/jquery-3.2.1.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <!-- Rev slider js -->
        <script src="vendors/revolution/js/jquery.themepunch.tools.min.js"></script>
        <script src="vendors/revolution/js/jquery.themepunch.revolution.min.js"></script>
        <script src="vendors/revolution/js/extensions/revolution.extension.actions.min.js"></script>
        <script src="vendors/revolution/js/extensions/revolution.extension.video.min.js"></script>
        <script src="vendors/revolution/js/extensions/revolution.extension.slideanims.min.js"></script>
        <script src="vendors/revolution/js/extensions/revolution.extension.layeranimation.min.js"></script>
        <script src="vendors/revolution/js/extensions/revolution.extension.navigation.min.js"></script>
        <!-- Extra plugin js -->
        <script src="vendors/owl-carousel/owl.carousel.min.js"></script>
        <script src="vendors/magnifc-popup/jquery.magnific-popup.min.js"></script>
        <script src="vendors/isotope/imagesloaded.pkgd.min.js"></script>
        <script src="vendors/isotope/isotope.pkgd.min.js"></script>
        <script src="vendors/datetime-picker/js/moment.min.js"></script>
        <script src="vendors/datetime-picker/js/bootstrap-datetimepicker.min.js"></script>
        <script src="vendors/nice-select/js/jquery.nice-select.min.js"></script>
        <script src="vendors/jquery-ui/jquery-ui.min.js"></script>
        <script src="vendors/lightbox/simpleLightbox.min.js"></script>
        
        
        
        <script src="js/theme.js"></script>
    </body>

</html><?php }?>