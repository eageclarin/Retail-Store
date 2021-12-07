<?php
    include_once '../env/connection.php';
	session_start();
	/* FOR FILTER */
    //variables
	$branch = $brand = $item = $id = ""; $categ="All";

	if (isset($_SESSION)) {
		$branch = $_SESSION['branch'];
        $name = $_SESSION['username'];
		$id = $_SESSION['userID'];
	}

    if (isset($_GET['categ']) && $_GET['categ'] == "PastaNoodles") {
        $categ = "Pasta & Noodles";
    }

	if (!empty($_GET['brand'])) {
		$brand = $_GET['brand'];
		$sqlFilter = "SELECT * FROM Item i
						INNER JOIN BI_has_I bii ON (i.item_ID = bii.item_ID)
						INNER JOIN branchInventory bi ON (bi.inventory_ID = bii.inventory_ID)
						INNER JOIN B_has_BI bbi ON (bbi.inventory_ID = bi.inventory_ID)
						INNER JOIN Branch b on (b.branch_ID = bbi.branch_ID)
						WHERE i.item_Brand = '$brand'
							AND bii.item_Stock > 0
							AND b.branch_ID = '$branch'
					";
		$resFilter = mysqli_query($conn, $sqlFilter);
	}
    //select item under category from branch
	if (!empty($_GET['categ'])) {
		if ($categ != "All") {
			$sqlFilter = "SELECT * FROM Item i
						INNER JOIN BI_has_I bii ON (i.item_ID = bii.item_ID)
						INNER JOIN branchInventory bi ON (bi.inventory_ID = bii.inventory_ID)
						INNER JOIN B_has_BI bbi ON (bbi.inventory_ID = bi.inventory_ID)
						INNER JOIN Branch b on (b.branch_ID = bbi.branch_ID)
						WHERE i.item_Category = '$categ'
							AND bii.item_Stock > 0
							AND b.branch_ID = '$branch'
					";
			$resFilter = mysqli_query($conn, $sqlFilter);
		} else {
			$sqlFilter = "SELECT * FROM Item i
						INNER JOIN BI_has_I bii ON (i.item_ID = bii.item_ID)
						INNER JOIN branchInventory bi ON (bi.inventory_ID = bii.inventory_ID)
						INNER JOIN B_has_BI bbi ON (bbi.inventory_ID = bi.inventory_ID)
						INNER JOIN Branch b on (b.branch_ID = bbi.branch_ID)
						WHERE bii.item_Stock > 0
							AND b.branch_ID = '$branch'
					";
			$resFilter = mysqli_query($conn, $sqlFilter);
		}
	}
?>

<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body style="background-color:transparent">
    <?php
		if ($resFilter) {
			$i = $row = 0;
			$count = mysqli_num_rows($resFilter); //number of rows sa table
	?>
			<div class="col-12 d-flex flex-wrap mt-3 h-20">
                <ul class="nav col-md-12 mb-3 justify-content-between">
	<?php
			    while (($rowFilter = mysqli_fetch_assoc($resFilter))) {
					$itemID = $rowFilter['item_ID'];
					$itemName = $rowFilter['item_Name']; //item name
					$itemPrice = $rowFilter['item_RetailPrice']; //item price
					$itemImg = $rowFilter['item_Image']; //item image
					$itemWeight = "0.00g";
	?>
				<li style="width: 18%">
					<form action="addItem.php?action=add" method="post" target="_top">
					<a href="" class="card shadow bg-light" style="border-radius: 15px; text-decoration: none">
                        <img class="card-img-top w-100" style="border-radius: 15px 15px 0 0;" src="../img/main/brand.jpg" alt="Card image cap">
                        <div class="container card-body">
							<div class="row">
								<div class="col-md-8">
									<h7 class="card-title text-dark"><?php echo $itemName ?></h7>
									<p class="card-text text-dark" style="font-size: 11px"><?php echo $itemWeight ?></p>
									<input type="hidden" name="itemID" value="<?php echo $itemID ?>" />
									<input class="btn btn-primary" type="submit" name="add" value="Add to Cart"/>
								</div>
								<div class="col-md-4 text-end">
									<h7 class="card-title text-primary">P<?php echo $itemPrice ?></h7>
								</div>
							</div>
                        </div>
					</a>
					<form>
				</li>
	<?php
					$i++; //number of items in row
					if($i % 5 == 0) { //4 items per row display
						echo "</ul><ul class='nav col-md-12 mb-3 justify-content-between'>"; //next row display
					}

					if(++$row == $count) {
						while ($i % 5 != 0) { //if less than 4 in row display, add extra hidden item until 4 items
							echo '<li style="visibility:hidden; width: 18%">
							<a href="" class="card bg-light" style="border-radius: 15px;visibility:hidden">
								<img class="card-img-top w-100" style="border-radius: 15px 15px 0 0;" src="">
								<div class="card-body">
									<h5 class="card-title"></h5>
									<p class="card-text text-light" style="font-size: 11px"></p>
								</div>
							</a>
							</li
							';

							$i++;
						}
					}
				}
					echo "</ul>";
					echo "</div>";
		}
	?>
</body>
</html>


