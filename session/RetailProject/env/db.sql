-- table for item
CREATE TABLE `Item` (
	`item_ID` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`item_Name` varchar(50) NOT NULL,
	`item_RetailPrice` float(53) NOT NULL,
	`item_WholesalePrice` float(53) NOT NULL,
	`item_Category` varchar(50) NOT NULL,
	`item_Image` varchar(100) NOT NULL,
	`item_Brand` varchar(50) NOT NULL
);

-- table for customer
CREATE TABLE `Customer` (
	`cust_ID` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`cust_Username` varchar(255) NOT NULL,
	`cust_Password` varchar(255) NULL,
	`cust_FName` varchar(25) NULL,
	`cust_LName` varchar(25) NULL,
	`cust_Email` varchar(50) NULL,
	`cust_ABrgy` varchar(25) NULL,
	`cust_ACity` varchar(25) NULL,
	`cust_AProvince` varchar(25) NULL,
	`cust_APostal` int NULL
);

-- table for customer contact
CREATE TABLE `Customer_Contact` (
	`cust_ID` int NOT NULL ,
	`contact` int(12) NOT NULL,
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
	`contact` int(12) NOT NULL,
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
	`contact` int(12) NOT NULL,
	PRIMARY KEY(admin_ID,contact),
	FOREIGN KEY(admin_ID) REFERENCES Admin(admin_ID) ON UPDATE CASCADE
);

-- table for customer orders cart
CREATE TABLE `Cu_orders_Ca` (
	`cart_ID` int NOT NULL PRIMARY KEY,
	`customer_ID` int NOT NULL,
	`branch_ID` int NOT NULL,
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
	FOREIGN KEY(item_ID) REFERENCES Item(item_ID) ON UPDATE CASCADE
);

-- table for admin manages branch
CREATE TABLE `A_manages_B` (
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

-- insert items
INSERT INTO `Item` (
    `item_Name`, `item_RetailPrice`, `item_WholesalePrice`, `item_Category`, `item_Image`, `item_Brand`
)
VALUES
	('Corned Beef 150g',71.0,65.0,'Canned Goods','img/cg/pf-cornedbeef.png','Purefoods'),
    ('Tuna Flakes in Oil 155g',31.25,29.25,'Canned Goods','img/cg/c-tunaflakes.png','Century'),
    ('Soy Sauce 1L',50.75,48.00,'Condiments','img/cond/dp-soysauce.png','Datu Puti'),
    ('Patis Regular 1L',61.25,59.00,'Condiments','img/cond/lor-patis.png','Lorins'),
    ('Elbow Macaroni Pasta 200g',20.75,19.50,'Pasta & Noodles','img/pasta/WKmacaroni.png','White King'),
    ('Sweet Spaghettipid 1kg',113.00,110.00,'Pasta & Noodles','img/pasta/f-spag.png','Fiesta'),
    ('Brown Coffee Polybag 27.5gx30',217.00,215.00,'Beverages','img/bev/k-browncoffe.png','Kopiko'),
    ('Black Twinpack 50g',11.25,10.25,'Beverages','img/bev/k-blacktwinpack.png','Kopiko'),
    ('Bravo Biscuits 30g x 10packs',53.50,50.0,'Biscuits','img/bisc/bravo/bravo.png','Rebisco')
;

-- insert branch
INSERT INTO `Branch` (`branch_Name`, `branch_Address`)
VALUES 
    ('Paoay','Paoay, Ilocos Norte'),
    ('Vicas','Camarin, Caloocan City'),
    ('Cordon','Isabela')
;

-- insert branch contact
INSERT INTO `Branch_Contact` (`branch_ID`,`contact`)
VALUES
    (1,4401234),
    (2,4415678),
    (3,4429876)
;

-- insert admin
INSERT INTO `Admin` (`admin_Username`, `admin_Password`)
VALUES
    ('jaemie1','admin1p@ss'),
    ('eigram2','admin2p@ss'),
    ('elymer3','admin3p@ss'),
    ('maam4','admin4p@ss')
;

UPDATE `Admin` SET 
	(`admin_Password` = MD5('admin1p@ss') WHERE `Admin`.`admin_ID` = 1),
	(`admin_Password` = MD5('admin2p@ss') WHERE `Admin`.`admin_ID` = 2),
	(`admin_Password` = MD5('admin3p@ss') WHERE `Admin`.`admin_ID` = 3),
	(`admin_Password` = MD5('admin4p@ss') WHERE `Admin`.`admin_ID` = 4),
;

-- insert admin contact
INSERT INTO `Admin_Contact` (`admin_ID`,`contact`)
VALUES
    (1,0998779154),
    (2,0988765432),
    (3,0567483920)
;

-- insert branch managers
INSERT INTO `A_manages_B` (`admin_ID`,`branch_ID`)
VALUES
    (1,1),
    (1,2),
    (1,3),
    (2,2),
    (3,3)
;

-- insert branch has branch inventory
INSERT INTO `branchInventory` (`inventory_ID`)
VALUES
    (1),
    (2),
    (3)
;

-- insert branch has branch inventory
INSERT INTO `B_has_BI` (`branch_ID`,`inventory_ID`)
VALUES
    (1,1),
    (2,2),
    (3,3)
;

-- insert branch has branch inventory
INSERT INTO `BI_has_I` (`inventory_ID`,`item_ID`,`item_Stock`)
VALUES
    (1,1,50),
    (1,2,50),
    (1,3,50),
    (1,4,50),
    (1,5,50),
    (1,6,50),
	(1,7,50),
    (1,8,50),
    (1,9,50),
    (2,1,50),
    (2,2,50),
    (2,3,50),
    (2,4,50),
    (2,5,50),
    (2,6,50),
	(2,7,50),
    (2,8,50),
    (2,9,50),
    (3,1,50),
    (3,2,50),
    (3,3,50),
    (3,4,50),
    (3,5,50),
    (3,6,50),
	(3,7,50),
    (3,8,50),
    (3,9,50)
;