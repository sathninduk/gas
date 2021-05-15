<?php
session_start();
// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["admin_loggedin"]) || $_SESSION["admin_loggedin"] !== true) {
	// Logged out
	$log = false;
	header("location: ./");
	exit;
} else {
	//Logged in
	$log = true;
	include "../php/connection.php";
	$permission = $_SESSION["permission"];

	$sql = "SELECT * FROM supplier WHERE status=1";
	$result = $conn->query($sql);
	if ($result->num_rows == 0) {
		echo "<script>";
        echo "  alert('Please add suppliers first!');";
        echo "  window.location = './suppliers.php';";
        echo "</script>";
	}

	$today = date("Y-m-d");
?>
	<!DOCTYPE html>
	<html>

	<head>
		<title>Delivery</title>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/font-awesome-line-awesome/css/all.min.css">
		<link rel="stylesheet" href="../style/Admin.css">
	</head>

	<body>
	<div class="sidenav">
			<div class="sidenav-header">
				<h3 class="brand">
					<i class="fa fa-unlink"></i>
					<span>Admin</span>
				</h3>
				<!--<span class="fa fa-bars"></span>-->
			</div>
			<div class="sidenav-menu">
				<ul>
					<li>
						<a href="./" class="nav-item">
							<i class="fa fa-tachometer-alt"></i>
							<span>Dashboard</span>
						</a>
					</li>
<?php if ($permission == 1 || $permission == 3 || $permission == 4 || $permission == 5) {?>
					<li>
						<a href="./customers.php" class="nav-item">
							<i class="fa fa-users"></i>
							<span>Customers</span>
						</a>
					</li>
<?php } ?>

<?php if ($permission == 1 || $permission == 3 || $permission == 4 || $permission == 5) {?>
					<div class="dropdown">
						<li>
							<a class="nav-item">
								<i class="fa fa-list"></i>
								<span>Orders<i class="fa fa-caret-down"></i></span>
							</a>
						</li>
						<div class="dropdown-content">
							<a class="nav-item" href="./delivery.php">Delivery</a>
							<?php if ($permission != 5) { ?>
							<a class="nav-item" href="./pickup.php">Pickup</a>
							<?php } ?>
						</div>
					</div>
<?php } ?>

<?php if ($permission == 1 || $permission == 5) {?>
					<li>
						<a class="nav-item" href="./drivers.php">
							<i class="fa fa-truck"></i>
							<span>Drivers</span>
						</a>
					</li>
<?php } ?>
<?php if ($permission == 1 || $permission == 2 || $permission == 4) {?>
<li>
						<a class="nav-item" href="./suppliers.php">
							<i class="fa fa-link"></i>
							<span>Suppliers</span>
						</a>
					</li>
<?php } ?>
<?php if ($permission == 1 || $permission == 2 || $permission == 4) {?>
					<li>
						<a class="nav-select" href="./supplier-orders.php">
							<i class="fa fa-parachute-box"></i>
							<span>Supply Orders</span>
						</a>
					</li>
<?php } ?>
<?php if ($permission == 1 || $permission == 2 || $permission == 4 || $permission == 5) {?>
					<li>
						<a class="nav-item" href="./stock.php">
							<i class="fa fa-cubes"></i>
							<span>Stock</span>
						</a>
					</li>
<?php } ?>
<?php if ($permission == 1 || $permission == 3 || $permission == 4) {?>
					<li>
						<a class="nav-item" href="./sales.php">
							<i class="fa fa-bar-chart"></i>
							<span>Sales</span>
						</a>
					</li>
<?php } ?>
<?php if ($permission == 1) {?>
					<li>
						<a class="nav-item" href="./feedback.php">
							<i class="fa fa-comment"></i>
							<span>Feedback</span>
						</a>
					</li>
<?php } ?>
<?php if ($permission == 1) {?>
					<div class="dropdown">
						<li>
							<a class="nav-item">
								<i class="fa fa-user-circle"></i>
								<span>Account<i class="fa fa-caret-down"></i></span>
							</a>
						</li>
						<div class="dropdown-content">
							<a class="nav-item" href="./change-pw.php">Change Password</a>
							<a class="nav-item" href="./php/logout.php">Logout</a>
						</div>
					</div>
<?php } ?>

				</ul>
			</div>
		</div>
		<div class="admin-content">
				<div class="content">
				<h1>Supply Orders - History<a onclick="window.print();" class="print">Print</a></h1>
				<h3 class="print-name">Sethmith Enterprise</h3>
				<br>
					<style>
						.tbl {
							font-size: 14px;
							margin-left: 0px;
							width: 65vw;
							margin-top: 0px;
						}
					</style>
					<?php
					
					// Order Data Fetch
					//$sql = "SELECT * FROM supply_orders WHERE status=1";
					//$sql = "SELECT * FROM orders INNER JOIN customer ON orders.customer_id=customer.customer_id";

					$sql = "SELECT * FROM ((supply_orders INNER JOIN supplier ON supply_orders.supplier_id = supplier.supplier_id) INNER JOIN products ON supply_orders.product_id = products.product_id) WHERE supply_orders.status=0 ORDER BY supply_orders.supply_order_id";

					$result = $conn->query($sql);

					if ($result->num_rows > 0) {
						echo "
						<table class=\"tbl\">
									<thead>
										<th style=\"max-width: 40px;\">Order</th>
										<th style=\"max-width: 120px;\">Date</th>
										<th style=\"max-width: 200px;\">Product</th>
										<th style=\"max-width: 40px;\">Unit Price (LKR)</th>
										<th style=\"max-width: 80px;\">Quantity</th>
										<th style=\"max-width: 80px;\">Total Price (LKR)</th>
										<th style=\"max-width: 80px;\">Supplier</th>
									</thead>
									<tbody>";
						// output data of each row
						while ($row = $result->fetch_assoc()) {

							echo "<tr>
									<td style=\"text-align: center;\">" . $row["supply_order_id"] . "</td>
									<td>" . $row["date"] . "</td>
									<td style=\"text-transform: capitalize;\">" . $row["name"] . "</td>
									<td>" . $row["unit_price"] . ".00</td>
									<td>" . $row["quantity"] . "</td>
									<td>" . ($row["unit_price"] * $row["quantity"]) . ".00</td>
									<td>" . $row["company_name"] . "</td>
								</tr>";
						}
						echo "</tbody>
						</table>";
					} else {
						echo "0 results";
					}

					?>


				</div>



		</div>

		

		<div class="main-content">
			<header class="header">
				<div class="search-bar">
				<span><b>Sethmith Enterprise</b></span>
				</div>
				<div class="social-icons">
					<style>
						.ad-top-btn {
							color: rgba(0, 0, 0, 1);
							font-size: .9rem;
						}

						.ad-top-btn:hover {
							color: rgba(0, 0, 0, .6);
						}
					</style>
					<a class="ad-top-btn" href="./"><span class="fa fa-user"><font style="font-weight: 400; font-family: 'Poppins', sans-serif;"> Admin</font></span></a>
					<a class="ad-top-btn" href="./php/logout.php"><span class="fa fa-sign-out-alt"><font style="font-weight: 400; font-family: 'Poppins', sans-serif;"> Logout</font></span></a>
					<div></div>
				</div>
			</header>
		</div>
	</body>

	</html>
<?php
}
?>