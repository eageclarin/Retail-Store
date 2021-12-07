<?php
    include_once '../env/connection.php';

	/* FOR FILTER */
    //variables
	$categ = $branch = $id = $item = "";

	if (isset($_GET['categ']) && isset($_GET['branch']) && isset($_GET['id'])) {
		$categ = $_GET['categ'];
        $branch = $_GET['branch'];
        $id = $_GET['id'];
	}

    if ($categ == "PastaNoodles") {
        $categ = "Pasta & Noodles";
    }

    //select item under category from branch
	if ($categ != "All") {
		$sqlCateg = "SELECT * FROM Item i
                    INNER JOIN BI_has_I bii ON (i.item_ID = bii.item_ID)
                    INNER JOIN branchInventory bi ON (bi.inventory_ID = bii.inventory_ID)
                    INNER JOIN B_has_BI bbi ON (bbi.inventory_ID = bi.inventory_ID)
                    INNER JOIN Branch b on (b.branch_ID = bbi.branch_ID)
                    WHERE i.item_Category = '$categ'
                        AND bii.item_Stock > 0
                        AND b.branch_ID = '$branch'
                ";
		$resCateg = mysqli_query($conn, $sqlCateg);
	} else {
		$sqlCateg = "SELECT * FROM Item i
                    INNER JOIN BI_has_I bii ON (i.item_ID = bii.item_ID)
                    INNER JOIN branchInventory bi ON (bi.inventory_ID = bii.inventory_ID)
                    INNER JOIN B_has_BI bbi ON (bbi.inventory_ID = bi.inventory_ID)
                    INNER JOIN Branch b on (b.branch_ID = bbi.branch_ID)
                    WHERE bii.item_Stock > 0
                        AND b.branch_ID = '$branch'
                ";
		$resCateg = mysqli_query($conn, $sqlCateg);
	}
	
?>

<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body style="background-color:transparent">
    <?php
        echo $categ;
		if ($resCateg) {
			$i = $row = 0;
			$count = mysqli_num_rows($resCateg); //number of rows sa table
	?>
			<div class="col-12 d-flex flex-wrap mt-3 h-20">
                <ul class="nav col-md-12 mb-3 justify-content-between">
	<?php
			    while (($rowCateg = mysqli_fetch_assoc($resCateg))) {
					$itemID = $rowCateg['item_ID'];
					$itemName = $rowCateg['item_Name']; //item name
					$itemPrice = $rowCateg['item_RetailPrice']; //item price
					$itemImg = $rowCateg['item_Image']; //item image
					$itemWeight = "0.00g";
	?>
				<li style="width: 18%">
					<a href="addItem.php?action=add&id=<?php echo $id ?>&item=<?php echo $itemID ?>&branch=<?php echo $branch ?>&categ=<?php echo $categ ?>" class="card shadow bg-light" style="border-radius: 15px; text-decoration: none">
                        <img class="card-img-top w-100" style="border-radius: 15px 15px 0 0;" src="../img/main/brand.jpg" alt="Card image cap">
                        <div class="card-body">
							<h7 class="card-title"><?php echo $itemName ?> &nbsp; P<?php echo $itemPrice ?></h7>
                            <p class="card-text text-light" style="font-size: 11px"><?php echo $itemWeight ?></p>
                        </div>
					</a>
				</li>
	<?php
					$i++; //number of items in row
					if($i % 5 == 0) { //4 items per row display
						echo "</ul><ul>"; //next row display
					}

					if(++$row == $count) {
						while ($i % 5 != 0) { //if less than 4 in row display, add extra hidden item until 4 items
							echo '<li style="visibility:hidden; width: 18%">
							<a href="" class="card shadow bg-light" style="border-radius: 15px;">
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


