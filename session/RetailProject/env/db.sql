-- create database if not exists
CREATE DATABASE IF NOT EXISTS CMSC127RetailProject;

-- table for item
CREATE TABLE `Item` (
	`item_ID` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`item_Name` varchar(75) NOT NULL,
	`item_Weight` varchar(50) NOT NULL,
	`item_RetailPrice` float(53) NOT NULL,
	`item_WholesalePrice` float(53) NOT NULL,
	`item_Category` varchar(50) NOT NULL,
	`item_Image` varchar(100) NOT NULL,
	`item_Brand` varchar(50) NOT NULL,
	`item_Status` TINYINT
);

-- table for customer
CREATE TABLE `Customer` (
	`cust_ID` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`cust_Username` varchar(255) NOT NULL,
	`cust_Password` varchar(255) NULL,
	`cust_FName` varchar(25) NULL,
	`cust_LName` varchar(25) NULL,
	`cust_Contact` varchar(11) NOT NULL,
	`cust_Email` varchar(50) NULL,
	`cust_ABrgy` varchar(25) NULL,
	`cust_ACity` varchar(25) NULL,
	`cust_AProvince` varchar(25) NULL,
	`cust_APostal` int NULL
);


-- table for cart
CREATE TABLE `Cart` (
	`cart_ID` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`total` decimal(10,2) NOT NULL
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
	`admin_Firstname` varchar(255) NOT NULL,
	`admin_Lastname` varchar(255) NOT NULL,
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
	`status` TINYINT,
	FOREIGN KEY (cart_ID) REFERENCES Cart(cart_ID),
	FOREIGN KEY (customer_ID) REFERENCES Customer(cust_ID)
);

-- table for cart contains item
CREATE TABLE `Ca_contains_I` (
	`item_ID` int NOT NULL,
	`cart_ID` int NOT NULL,
	`quantity` TINYINT(255) NOT NULL,
	`total` decimal(10,2) NOT NULL,
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
    `item_Name`, `item_Weight`, `item_RetailPrice`, `item_WholesalePrice`, `item_Category`, `item_Image`, `item_Brand`,`item_
	Status`
)
VALUES
	('Corned Beef','150g',71.0,65.0,'Canned Goods','../img/cg/pf-cornedbeef.png','Purefoods',0),
	('Corned Chicken Regular','150g',62.0,61.0,'Canned Goods','../img/cg/pf-cornedchick.png','Purefoods',0),
	('Luncheon Meat Chicken','210g',150.0,149.0,'Canned Goods','../img/cg/pf-luncheonchick.png','Purefoods',0),
	('Sizzling Delights Sisig','150g',43.50,40.0,'Canned Goods','../img/cg/pf-sisig.png','Purefoods',0),
	('Luncheon Meat','350g',87.0,85.0,'Canned Goods','../img/cg/pf-luncheon.png','Purefoods',0),
	('Vienna Sausage Original','230g',55.0,53.25,'Canned Goods','../img/cg/pf-vienna.png','Purefoods',0),
    ('Tuna Flakes in Oil Small','155g',31.25,29.25,'Canned Goods','../img/cg/c-tunaflakes.png','Century',0),
	('Tuna Flakes in Oil Big','420g',89.50,89.25,'Canned Goods','../img/cg/c-tunaflakes420g.png','Century',0),
	('Tuna Flakes Calamansi','155g',33,30,'Canned Goods','../img/cg/c-tunaflakescalamansi.png','Century',0),
	('Chunky Corned Tuna','150g',26.50,25,'Canned Goods','../img/cg/c-tunachunky.png','Century',0),
	('Chili Corned Tuna','85g',22,20.25,'Canned Goods','../img/cg/c-chilicornedtuna.png','Century',0),
	('Tuna Paella','180g',38.00,36.25,'Canned Goods','../img/cg/c-tunapaella.png','Century',0),
	('Mackerel in Natural Oil','425g',62,59,'Canned Goods','../img/cg/m-mackerel.png','Mega'),
	('Sardines in Tomato Sauce','155g',19.25,18.25,'Canned Goods','../img/cg/m-sardines.png','Mega',0),
	('Tuna Flakes Sweet and Spicy','180g',44.75,43,'Canned Goods','../img/cg/m-tunaflakes.png','Mega',0),
	('Fried Sardines with Tausi','155g',32.75,31,'Canned Goods','../img/cg/m-friedsardines.png','Mega',0),
	('Fried Sardines Hot & Spicy','155g',32.75,31,'Canned Goods','../img/cg/m-friedsardineshot.png','Mega',0),
	('Mackerel Tomato Sauce','425',61.50,59,'Canned Goods','../img/cg/m-mackereltomato.png','Mega',0),
    ('Soy Sauce Small','350ml',18.50,17,'Condiments','../img/cond/dp-soysauce.png','Datu Puti',0),
	('Soy Sauce Tipid','385ml',20,19.00,'Condiments','../img/cond/dp-soysauce385ml.png','Datu Puti',0),
	('Vinegar','1L',42.00,41.00,'Condiments','../img/cond/dp-vinegar.png','Datu Puti',0),
	('Patis','350ml',24,23,'Condiments','../img/cond/dp-patis.png','Datu Puti',0),
    ('Patis Regular','1L',61.25,59.00,'Condiments','../img/cond/lor-patis.png','Lorins',0),
	('Patis Pet Bottle','350ml',21.25,20,'Condiments','../img/cond/lor-patispet.png','Lorins',0),
    ('Fiesta Elbow Macaroni','1kg',71,67,'Pasta & Noodles','../img/pasta/wk-macaroni.png','White King',0),
	('Fiesta Spaghetti Good for 12','800g',59.75,58,'Pasta & Noodles','../img/pasta/wk-spag.png','White King',0),
	('Fiesta Spaghetti Good for 9','450g',35.50,34,'Pasta & Noodles','../img/pasta/wk-spag9.png','White King',0),
	('Spaghetti Flat','400g',44.25,43,'Pasta & Noodles','../img/pasta/er-spagflat.png','El Real',0),
    ('Elbow Macaroni','400g',35.75,35,'Pasta & Noodles','../img/pasta/er-macaroni.png','El Real',0),
    ('Double Cups Coffee','33g',8,7,'Beverages','../img/bev/k-doublecups.png','Kopiko',0),
    ('Black Twinpack','50g',11.25,10.25,'Beverages','../img/bev/k-blacktwinpack.png','Kopiko',0),
	('Brown Twinpack','53g',11.25,10.25,'Beverages','../img/bev/k-browntwinpack.png','Kopiko',0),
	('Creamy Caramelo Twinpack','50g',11.25,10.25,'Beverages','../img/bev/k-creamytwinpack.png','Kopiko',0),
	('Apple Green Tea Solo','230ml x 24pcs',260,259,'Beverages','../img/bev/c2-apple.png','C2',0),
	('Sugar-Free Apple Green Tea','355ml',20.50,19,'Beverages','../img/bev/c2-sugarapple.png','C2',0),
	('Classic Green Tea','335ml',20.50,19,'Beverages','../img/bev/c2-greentea.png','C2',0),
	('Lemon Green Tea','335ml',20.50,19,'Beverages','../img/bev/c2-lemontea.png','C2',0),
	('Milk Tea Wintermelon','270ml',25,24,'Beverages','../img/bev/c2-wintermelon.png','C2',0),
	('Milk Tea Chocolate','270ml',25,24,'Beverages','../img/bev/c2-chocolate.png','C2',0),
    ('Bravo Biscuits','30g x 10packs',53.50,50.0,'Biscuits','../img/bisc/r-bravo.png','Rebisco',0),
	('Maxi Mix Biscuit Collection','1.5kg',259.50,255.0,'Biscuits','../img/bisc/r-maximix.png','Rebisco',0),
	('Special Assorted Biscuits','3kg',277.50,275.0,'Biscuits','../img/bisc/r-specialassorted.png','Rebisco',0),
	('Marie Time Biscuit','9g x 20packs',18.75,18.0,'Biscuits','../img/bisc/r-marie.png','Rebisco',0),
	('Butter Cream-Filled Cracker','32g x 10packs',53.50,53.0,'Biscuits','../img/bisc/r-buttercreamfilled.png','Rebisco',0),
	('Choco Cream-Filled Cracker','32g x 10packs',53.50,53.0,'Biscuits','../img/bisc/r-chococreamfilled.png','Rebisco',0),
	('PButter Cream-Filled Cracker','32g x 10packs',53.50,53.0,'Biscuits','../img/bisc/r-peanutbuttercreamfilled.png','Rebisco',0),
	('Combi Triple Choco Wafer','30g x 10packs',53.50,53.0,'Biscuits','../img/bisc/r-combi.png','Rebisco',0),
	('Extreme Choco Coated','25g',53.50,53.0,'Biscuits','../img/bisc/r-extreme.png','Rebisco',0),
	('Hansel Plain Crackers','30g x 10packs',52.0,50.0,'Biscuits','../img/bisc/r-hansel.png','Rebisco',0),
	('Frootees Strawberry','30g',52.00,50.0,'Biscuits','../img/bisc/r-frootees.png','Rebisco',0)
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
	-- (4,4889876)
;

-- insert admin
INSERT INTO `Admin` (`admin_Username`, `admin_Firstname`,`admin_Lastname`, `admin_Password`)
VALUES
    ('jaemie1','Jaemie','Campo','admin1p@ss'),
    ('eigram2','Eigram','Eclarin','admin2p@ss'),
    ('elymer3','Elymer','Reyno','admin3p@ss'),
    ('maam4','Nikka','Boquio','admin4p@ss')
;

UPDATE `Admin` SET 
	`admin_Password` = MD5('admin1p@ss') WHERE `Admin`.`admin_ID` = 1;
UPDATE `Admin` SET 
	`admin_Password` = MD5('admin2p@ss') WHERE `Admin`.`admin_ID` = 2;
UPDATE `Admin` SET 
	`admin_Password` = MD5('admin3p@ss') WHERE `Admin`.`admin_ID` = 3;
UPDATE `Admin` SET 
	`admin_Password` = MD5('admin4p@ss') WHERE `Admin`.`admin_ID` = 4;

-- insert admin contact
INSERT INTO `Admin_Contact` (`admin_ID`,`contact`)
VALUES
    (1,0998779154),
    (2,0988765432),
    (3,0567483920),
	(4,0935483920)
;

-- insert branch managers
INSERT INTO `A_manages_B` (`admin_ID`,`branch_ID`)
VALUES
    (1,1),
    (1,2),
    (1,3),
    (2,1),
    (3,2),
	(4,3)
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
    (1,1,560),
    (1,2,560),
    (1,3,560),
    (1,4,560),
    (1,5,560),
    (1,6,560),
	(1,7,560),
    (1,8,560),
    (1,9,560),
	(1,10,650),
	(1,11,850),
    (1,12,950),
    (1,13,850),
    (1,14,850),
    (1,15,850),
    (1,16,850),
	(1,17,850),
    (1,18,850),
    (1,19,580),
	(1,20,580),
	(1,21,580),
    (1,22,580),
    (1,23,580),
    (1,24,580),
    (1,25,580),
    (1,26,580),
	(1,27,590),
    (1,28,590),
    (1,29,590),
	(1,30,590),
    (1,31,590),
	(1,32,590),
    (1,33,590),
    (1,34,590),
    (1,35,590),
    (1,36,590),
	(1,37,590),
    (1,38,950),
    (1,39,950),
	(1,40,950),
    (1,41,950),
	(1,42,950),
    (1,43,950),
    (1,44,950),
    (1,45,950),
    (1,46,950),
	(1,47,950),
    (1,48,950),
    (1,49,950),
	(1,50,950),
    (2,1,950),
    (2,2,950),
    (2,3,950),
    (2,4,950),
    (2,5,950),
    (2,6,950),
	(2,7,950),
    (2,8,950),
    (2,9,950),
	(2,10,950),
	(2,11,950),
    (2,12,950),
    (2,13,950),
    (2,14,950),
    (2,15,950),
    (2,16,950),
	(2,17,950),
    (2,18,950),
    (2,19,950),
	(2,20,950),
	(2,21,950),
    (2,22,950),
    (2,23,950),
    (2,24,950),
    (2,25,950),
    (2,26,950),
	(2,27,950),
    (2,28,950),
    (2,29,950),
	(2,30,950),
	(2,31,950),
    (2,32,950),
    (2,33,950),
    (2,34,950),
    (2,35,950),
    (2,36,950),
	(2,37,950),
    (2,38,950),
    (2,39,950),
	(2,40,950),
	(2,41,950),
    (2,42,950),
    (2,43,950),
    (2,44,950),
    (2,45,950),
    (2,46,950),
	(2,47,950),
    (2,48,950),
    (2,49,950),
	(2,50,950),
    (3,1,950),
    (3,2,950),
    (3,3,950),
    (3,4,950),
    (3,6,950),
	(3,7,950),
    (3,8,950),
    (3,9,950),
	(3,10,950),
	(3,11,950),
    (3,12,950),
    (3,13,950),
    (3,14,950),
    (3,15,950),
	(3,17,950),
    (3,18,950),
    (3,19,950),
	(3,20,950),
	(3,21,950),
    (3,23,950),
    (3,24,950),
    (3,25,950),
    (3,26,950),
    (3,28,950),
    (3,29,950),
	(3,30,950),
	(3,31,950),
    (3,32,950),
    (3,33,950),
    (3,36,950),
	(3,37,950),
    (3,38,950),
    (3,39,950),
	(3,40,950),
	(3,46,950),
	(3,47,950),
    (3,48,950),
    (3,49,950),
	(3,50,950)
;
INSERT INTO `customer` (`cust_Username`, `cust_Password`, `cust_FName`, `cust_LName`, `cust_Contact`, `cust_Email`, `cust_ABrgy`, `cust_ACity`, `cust_AProvince`, `cust_APostal`)
VALUES 
('customer1', 'customer1', 'Customer1', 'Surname1','09123456789', 'customer@email.com', '111', 'Baguio City', 'Benguet', '1234');
UPDATE `customer` SET 
	`cust_Password` = MD5('customer1') WHERE `customer`.`cust_ID` = 1;