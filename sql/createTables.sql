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
CREATE TABLE `BI_has I` (
	`inventory_ID` int NOT NULL,
`item_ID` int NOT NULL,
	`item_Stock` int NOT NULL,
	PRIMARY KEY (inventory_ID, item_ID),
FOREIGN KEY(inventory_ID) REFERENCES branchInventory(inventory_ID) ON UPDATE CASCADE,
	FOREIGN KEY(item_ID) REFERENCES Item(item_ID) ON UPDATE CASCADE
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
