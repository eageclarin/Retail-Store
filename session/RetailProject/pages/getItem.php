<?php
    include_once '../env/userconnection.php';
	/* FOR FILTER */
    //variables
	$branch = $item = $id = $for = "";
	$brand = $categ = "All";
	$sort = "ASC"; $order = "Name";

	if (isset($_SESSION)) {
		$branch = $_SESSION['branch'];
        $name = $_SESSION['username'];
		$id = $_SESSION['userID'];
	}

	if(isset($_SESSION['categ'])) {
		$categ = $_SESSION['categ'];
	} if (isset($_SESSON['brand'])) {
		$brand = $_SESSION['brand'];
	} if (isset($_SESSION['sort'])) {
		$sort = $_SESSION['sort'];
	} if (isset($_SESSION['order'])){
		$order = $_SESSION['order'];
	}

    if (!empty($_GET['categ'])) {
		$categ = $_GET['categ'];
		$_SESSION['categ'] = $categ;
		if ($categ == "PastaNoodles") { $categ = "Pasta & Noodles"; }
    }
	if (!empty($_GET['sort'])) {
		$sort = $_GET['sort'];
		$order = $_GET['order'];
		$_SESSION['sort'] = $sort;
		$_SESSION['order'] = $order;
	}
	if (!empty($_GET['brand'])){
		$brand = $_GET['brand'];
		$_SESSION['brand'] = $brand;
	}

	if (!empty($_GET['for'])) {
		$for = $_GET['for'];
		switch($_GET['for']) {
			case "brand":
				if ($brand != "All") {
					if ($categ == "All") {
						$sqlFilter = "SELECT * FROM Item i
									INNER JOIN BI_has_I bii ON (i.item_ID = bii.item_ID)
									INNER JOIN branchInventory bi ON (bi.inventory_ID = bii.inventory_ID)
									INNER JOIN B_has_BI bbi ON (bbi.inventory_ID = bi.inventory_ID)
									INNER JOIN Branch b on (b.branch_ID = bbi.branch_ID)
									WHERE i.item_Brand = '$brand'
										AND bii.item_Stock > 0
										AND bbi.branch_ID = '$branch'
										OR i.item_Category = '$categ'
									ORDER BY i.item_$order $sort
								";
						$resFilter = mysqli_query($conn, $sqlFilter);
					} else if ($categ != "All") {
						$sqlFilter = "SELECT * FROM Item i
									INNER JOIN BI_has_I bii ON (i.item_ID = bii.item_ID)
									INNER JOIN branchInventory bi ON (bi.inventory_ID = bii.inventory_ID)
									INNER JOIN B_has_BI bbi ON (bbi.inventory_ID = bi.inventory_ID)
									INNER JOIN Branch b on (b.branch_ID = bbi.branch_ID)
									WHERE i.item_Brand = '$brand'
										AND bii.item_Stock > 0
										AND bbi.branch_ID = '$branch'
										AND i.item_Category = '$categ'
									ORDER BY i.item_$order $sort
								";
						$resFilter = mysqli_query($conn, $sqlFilter);
					}
					
				} else {
					if ($categ == "All") {
						$sqlFilter = "SELECT * FROM Item i
								INNER JOIN BI_has_I bii ON (i.item_ID = bii.item_ID)
								INNER JOIN branchInventory bi ON (bi.inventory_ID = bii.inventory_ID)
								INNER JOIN B_has_BI bbi ON (bbi.inventory_ID = bi.inventory_ID)
								INNER JOIN Branch b on (b.branch_ID = bbi.branch_ID)
								WHERE bii.item_Stock > 0 
									AND b.branch_ID = '$branch'
									OR i.item_Category = '$categ'
								ORDER BY i.item_$order $sort
							";
						$resFilter = mysqli_query($conn, $sqlFilter);
					} else if ($categ != "All") {
						$sqlFilter = "SELECT * FROM Item i
								INNER JOIN BI_has_I bii ON (i.item_ID = bii.item_ID)
								INNER JOIN branchInventory bi ON (bi.inventory_ID = bii.inventory_ID)
								INNER JOIN B_has_BI bbi ON (bbi.inventory_ID = bi.inventory_ID)
								INNER JOIN Branch b on (b.branch_ID = bbi.branch_ID)
								WHERE bii.item_Stock > 0 
									AND b.branch_ID = '$branch'
									AND i.item_Category = '$categ'
								ORDER BY i.item_$order $sort
							";
						$resFilter = mysqli_query($conn, $sqlFilter);
					}
					
				}
				break;
			case "categ":
				if ($categ != "All") {
					if ($brand == "All") {
						$sqlFilter = "SELECT * FROM Item i
									INNER JOIN BI_has_I bii ON (i.item_ID = bii.item_ID)
									INNER JOIN branchInventory bi ON (bi.inventory_ID = bii.inventory_ID)
									INNER JOIN B_has_BI bbi ON (bbi.inventory_ID = bi.inventory_ID)
									INNER JOIN Branch b on (b.branch_ID = bbi.branch_ID)
									WHERE i.item_Category = '$categ'
										AND bii.item_Stock > 0
										AND bbi.branch_ID = '$branch'
										OR i.item_Brand = '$brand'
									ORDER BY i.item_$order $sort
								";
						$resFilter = mysqli_query($conn, $sqlFilter);
					} else if ($brand != "All") {
						$sqlFilter = "SELECT * FROM Item i
									INNER JOIN BI_has_I bii ON (i.item_ID = bii.item_ID)
									INNER JOIN branchInventory bi ON (bi.inventory_ID = bii.inventory_ID)
									INNER JOIN B_has_BI bbi ON (bbi.inventory_ID = bi.inventory_ID)
									INNER JOIN Branch b on (b.branch_ID = bbi.branch_ID)
									WHERE i.item_Category = '$categ'
										AND bii.item_Stock > 0
										AND bbi.branch_ID = '$branch'
										AND i.item_Brand = '$brand'
									ORDER BY i.item_$order $sort
								";
						$resFilter = mysqli_query($conn, $sqlFilter);
					}
				} else {
					if ($brand == "All") {
						$sqlFilter = "SELECT * FROM Item i
								INNER JOIN BI_has_I bii ON (i.item_ID = bii.item_ID)
								INNER JOIN branchInventory bi ON (bi.inventory_ID = bii.inventory_ID)
								INNER JOIN B_has_BI bbi ON (bbi.inventory_ID = bi.inventory_ID)
								INNER JOIN Branch b on (b.branch_ID = bbi.branch_ID)
								WHERE bii.item_Stock > 0 
									AND b.branch_ID = '$branch'
									OR i.item_Brand = '$brand'
								ORDER BY i.item_$order $sort
							";
						$resFilter = mysqli_query($conn, $sqlFilter);
					} else if ($brand != "All") {
						$sqlFilter = "SELECT * FROM Item i
								INNER JOIN BI_has_I bii ON (i.item_ID = bii.item_ID)
								INNER JOIN branchInventory bi ON (bi.inventory_ID = bii.inventory_ID)
								INNER JOIN B_has_BI bbi ON (bbi.inventory_ID = bi.inventory_ID)
								INNER JOIN Branch b on (b.branch_ID = bbi.branch_ID)
								WHERE bii.item_Stock > 0 
									AND b.branch_ID = '$branch'
									AND i.item_Brand = '$brand'
								ORDER BY i.item_$order $sort
							";
						$resFilter = mysqli_query($conn, $sqlFilter);
					}
					
				}
				break;
		}
	}
?>

<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	<style>
		.addCart:hover {
			background-color: white;
			border-color: #0d6efd;
			color: #0d6efd;
            transition: .5s ease;
		}
	</style>
</head>
<body style="background-color:transparent">
    <?php
		if ($resFilter) {
			$i = $row = 0;
			$count = mysqli_num_rows($resFilter); //number of rows sa table

			if ($count < 1) {
				echo "<br><h5><i>No $categ for $brand</i></h5>";
			}
	?>
			<div class="col-12 d-flex flex-wrap mt-3 h-20">
                <ul class="nav col-md-12 mb-3 justify-content-between">
	<?php
			    while (($rowFilter = mysqli_fetch_assoc($resFilter))) {
					$itemID = $rowFilter['item_ID'];
					$itemName = $rowFilter['item_Name']; //item name
					$itemWeight = $rowFilter['item_Weight']; //item weight
					$itemPrice = $rowFilter['item_RetailPrice']; //item price
					$itemImg = $rowFilter['item_Image']; //item image
	?>
				<li style="width:24%;" style="h-100" class="py-2">
					<form action="addItem.php?itemID=<?php echo $itemID ?>&for=<?php echo $for ?>" method="post" target="_top">
					<div class="card h-100 shadow bg-light" style="border-radius: 15px; text-decoration: none">
                        <img class="card-img-top w-100" style="border-radius: 15px 15px 0 0;" src="<?php echo $itemImg ?>" alt="<?php echo $itemName ?>">
                        <div class="container card-body">
							<div class="row">
								<div class="col-md-9">
									<h7 class="card-title text-dark text-wrap" ><?php echo $itemName ?></h7>
									<p class="card-text text-dark" style="font-size: 10px"><?php echo $itemWeight ?></p>
								</div>
								<div class="col-md-3 text-end">
									<h7 class="card-title text-primary">P<?php echo $itemPrice ?></h7>
								</div>
							</div>
                        </div>
						<div class="card-footer pb-3 pt-0 bg-transparent border-0">
							<input class="btn btn-primary addCart" type="submit" name="add" value="Add to Cart"/>
						</div>
					</div>
					</form>
				</li>
	<?php
					$i++; //number of items in row
					if($i % 4 == 0) { //5 items per row display
						echo "</ul><ul class='nav col-md-12 mb-3 mt-3 justify-content-between'>"; //next row display
						$i = 0;
					}

					if(++$row == $count) {
						while ($i % 4 != 0) { //if less than 5 in row display, add extra hidden item until 4 items
							echo '<li style="width:24%;visibility:hidden" style="h-100" class="py-2">
							<form action="" method="" target="">
							<div class="card h-100 shadow bg-light" style="border-radius: 15px; text-decoration: none">
								<img class="card-img-top w-100" style="border-radius: 15px 15px 0 0;" src="<?php echo $itemImg ?>" alt="<?php echo $itemName ?>">
								<div class="container card-body">
									<div class="row">
										<div class="col-md-9">
											<h7 class="card-title text-dark text-wrap" ><?php echo $itemName ?></h7>
											<p class="card-text text-dark" style="font-size: 10px"><?php echo $itemWeight ?></p>
										</div>
										<div class="col-md-3 text-end">
											<h7 class="card-title text-primary">P<?php echo $itemPrice ?></h7>
										</div>
									</div>
								</div>
								<div class="card-footer pb-3 pt-0 bg-transparent border-0">
									<input class="btn btn-primary" type="submit" name="add" value="Add to Cart"/>
								</div>
							</div>
							</form>
							</li>
							';

							$i++;
						}
					}
				}
					echo "</ul>";
					echo "</div><br><br><br><br><br><br>";
		}
	?>
</body>
</html>


