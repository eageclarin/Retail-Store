<<<<<<< HEAD
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
=======
CREATE DATABASE IF NOT EXISTS CMSC127RetailProject;

-- table for item
CREATE TABLE `Item` (
	`item_ID` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`item_Name` varchar(50) NOT NULL,
	`item_RetailPrice` float(53) NOT NULL,
	`item_WholesalePrice` float(53) NOT NULL,
	`item_Category` varchar(50) NOT NULL,
	`item_Image` varchar(25) NOT NULL,
	`item_Brand` varchar(50) NOT NULL
);

-- table for customer
CREATE TABLE `Customer` (
	`cust_ID` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`cust_Username` varchar(255) NOT NULL,
	`cust_Password` varchar(255) NOT NULL,
	`cust_FName` varchar(25) NOT NULL,
	`cust_LName` varchar(25) NOT NULL,
	`cust_Email` varchar(50) NOT NULL,
	`cust_ABrgy` varchar(25) NOT NULL,
	`cust_ACity` varchar(25) NOT NULL,
	`cust_AProvince` varchar(25) NOT NULL,
	`cust_APostal` int NOT NULL
);

-- table for customer contact
CREATE TABLE `Customer_Contact` (
	`cust_ID` int NOT NULL ,
	`contact` int(11) NOT NULL,
	PRIMARY KEY(cust_ID,contact),
	FOREIGN KEY(cust_ID) REFERENCES Customer(cust_ID) ON UPDATE CASCADE
);

-- table for cart
CREATE TABLE `Cart` (
	`cart_ID` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`total` float(53) NOT NULL
);

-- table for branch
CREATE TABLE `Branch` (
	`branch_ID` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`branch_Name` varchar(50) NOT NULL,
	`branch_Address` varchar(75) NOT NULL
);

-- table for branch contact
CREATE TABLE `Branch_Contact` (
	`branch_ID` int NOT NULL, 
	`contact` int(11) NOT NULL,
	PRIMARY KEY(branch_ID,contact),
	FOREIGN KEY(branch_ID) REFERENCES Branch(branch_ID) ON UPDATE CASCADE
);

-- table for branch inventory
CREATE TABLE `branchInventory` (
	`inventory_ID` int NOT NULL AUTO_INCREMENT PRIMARY KEY
);

-- table for admin
CREATE TABLE `Admin` (
	`admin_ID` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`admin_Username` varchar(255) NOT NULL,
	`admin_Password` varchar(255) NOT NULL
);
-- table for admin contact
CREATE TABLE `Admin_Contact` (
	`admin_ID` int NOT NULL,
	`contact` int(11) NOT NULL,
	PRIMARY KEY(admin_ID,contact),
	FOREIGN KEY(admin_ID) REFERENCES Admin(admin_ID) ON UPDATE CASCADE
);

-- table for customer orders cart
CREATE TABLE `Cu_orders_Ca` (
	`cart_ID` int NOT NULL PRIMARY KEY,
	`customer_ID` int NOT NULL,
	`order_Date` datetime NOT NULL,
	`status` bit,
FOREIGN KEY (cart_ID) REFERENCES Cart(cart_ID),
FOREIGN KEY (customer_ID) REFERENCES Customer(cust_ID)
);

-- table for cart contains item
CREATE TABLE `Ca_contains_I` (
	`item_ID` int NOT NULL,
	`cart_ID` int NOT NULL,
	`quantity` TINYINT(255) NOT NULL,
	`total` float NOT NULL,
	PRIMARY KEY (item_ID, cart_ID),
	FOREIGN KEY (item_ID) REFERENCES Item(item_ID),
	FOREIGN KEY (cart_ID) REFERENCES Cart(cart_ID)
);

-- table for branch inventory has item
CREATE TABLE `BI_has_I` (
	`inventory_ID` int NOT NULL,
`item_ID` int NOT NULL,
	`item_Stock` int NOT NULL,
	PRIMARY KEY (inventory_ID, item_ID),
FOREIGN KEY(inventory_ID) REFERENCES branchInventory(inventory_ID) ON UPDATE CASCADE,
	FOREIGN KEY(item_ID) REFERENCES Item(item_ID) ON UPDATE CASCADE ON DELETE CASCADE
);

-- table for admin manages branch
CREATE TABLE A_manages_B(
	`admin_ID` int NOT NULL,
	`branch_ID` int NOT NULL,
	PRIMARY KEY (admin_ID, branch_ID),
	FOREIGN KEY(admin_ID) REFERENCES Admin(admin_ID) ON UPDATE CASCADE,
	FOREIGN KEY(branch_ID) REFERENCES Branch(branch_ID) ON UPDATE CASCADE
);

-- table for branch has branch inventory
CREATE TABLE `B_has_BI` (
	`branch_ID` int NOT NULL PRIMARY KEY,
	`inventory_ID` int NOT NULL,
	FOREIGN KEY(inventory_ID) REFERENCES branchInventory(inventory_ID) ON UPDATE CASCADE,
FOREIGN KEY(branch_ID) REFERENCES Branch(branch_ID) ON UPDATE CASCADE
);
>>>>>>> Jaemie
