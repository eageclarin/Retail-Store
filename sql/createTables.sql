-- ADD TO CART ITEM

-- search item if still available in branch
SELECT * FROM `Item` i
    INNER JOIN `BI_has_I` bii ON (i.item_ID = bii.item_ID)
    INNER JOIN `branchInventory` bi ON (bi.inventory_ID = bii.inventory_ID)
    INNER JOIN `B_has_BI` bbi ON (bbi.inventory_ID = bi.inventory_ID)
    INNER JOIN `Branch` b on (b.branch_ID = bbi.branch_ID)
    WHERE i.item_ID = '$item'
        AND bii.item_Stock > 0
        AND b.branch_ID = '$chosenBranch';

-- check if there exists cartID of customerID in branchID
SELECT * FROM `Cart` c
	INNER JOIN `Cu_orders_Ca` cca ON (c.cart_ID = cca.cart_ID)
	INNER JOIN `Branch` b ON (cca.branch_ID = b.branch_ID)
	INNER JOIN `Customer` cu ON (cca.customer_ID = cu.cust_ID)
	WHERE cu.cust_ID = '$id'
		AND b.branch_ID = '$chosenBranch';

-- if no cartID of customerID in branchID
INSERT INTO `Cart` (total) VALUES (0);
INSERT INTO `Cu_orders_Ca` VALUES (@@IDENTITY, $id, $chosenBranch, '$date', 0);

-- if there's existing cartID of customerID in branchID, check if item in cartID
SELECT * FROM `Ca_contains_I` cai
	INNER JOIN `Cu_orders_Ca` cca ON (cai.cart_ID = cca.cart_ID)
	INNER JOIN `Item` i ON (cai.item_ID = i.item_ID)
	WHERE i.item_ID = '$item' AND cca.customer_ID = '$id';

-- if in cartID, update qty, total, stock
UPDATE `Ca_contains_I` SET `quantity` = '$orderQty', `total` = '$orderTotal'
				WHERE `cart_ID` = (SELECT cart_ID FROM `Cu_orders_Ca` WHERE `customer_ID` = '$id');

UPDATE `Cart` SET `total` = (SELECT SUM(total) FROM `Ca_contains_I`
						WHERE `cart_ID` = (SELECT cart_ID FROM `Cu_orders_Ca` WHERE `customer_ID` = '$id'))
    WHERE `cart_ID` = (SELECT cart_ID FROM `Cu_orders_Ca` WHERE `customer_ID` = '$id');
            
UPDATE `BI_has_I` SET `item_Stock` = item_Stock - 1
    WHERE `inventory_ID` = (SELECT inventory_ID FROM `B_has_BI` WHERE `branch_ID` = '$chosenBranch')
    AND `item_ID` = '$item';

-- if not in cartID, insert and update qty, total, stock
INSERT INTO `Ca_contains_I` VALUES (
	$item, (SELECT cart_ID FROM `Cu_orders_Ca` WHERE `customer_ID` = '$id'),1, '$orderPrice');

UPDATE `Cart` SET `total` = (SELECT SUM(total) FROM `Ca_contains_I`
						WHERE cart_ID = (SELECT cart_ID FROM `Cu_orders_Ca` WHERE `customer_ID` = '$id'))
    WHERE `cart_ID` = (SELECT cart_ID FROM `Cu_orders_Ca` WHERE `customer_ID` = '$id');

UPDATE `BI_has_I` SET `item_Stock` = item_Stock - 1
    WHERE `inventory_ID` = (SELECT inventory_ID FROM `B_has_BI` WHERE `branch_ID` = '$chosenBranch')
        AND `item_ID` = '$item';
