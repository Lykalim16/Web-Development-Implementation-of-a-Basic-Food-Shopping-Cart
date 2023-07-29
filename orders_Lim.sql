create database mp2_system;

create table product(
    id int(11) AUTO_INCREMENT PRIMARY KEY NOT Null,
    product_name varchar(100) not null,
    product_price varchar(50) not null,
    product_image varchar(255) not null,
    product_code varchar(10) not null
);
insert into items (product_name,product_price,product_image,product_code) values ('Sweet and Spicy Chicken Wings', '140' , './img/Spicy.jpg', 'p1');
insert into items (product_name,product_price,product_image,product_code) values ('Kimbap (Korean Sushi Rolls)','150','./img/Kimbap.jpg','p2');
insert into items (product_name,product_price,product_image,product_code) values ('Garlic Parmesan Chicken Wings','140','./img/GarlicParm.jpg','p3');
insert into items (product_name,product_price,product_image,product_code) values ('Chocolate Chip Cookies','130','./img/Cookies.jpg','p4');
insert into items (product_name,product_price,product_image,product_code) values ('Mini Blueberry Cheesecake','300','./img/Blueberry.jpg','p5');
insert into items (product_name,product_price,product_image,product_code) values ('Salted Egg Chicken Wings','140','./img/SaltedEgg.jpg','p6');
insert into items (product_name,product_price,product_image,product_code) values ('Banana Bread with Chocolate Chips','120','./img/BananaBread.jpg','p7');
insert into items (product_name,product_price,product_image,product_code) values ('Soy Garlic Chicken Wings','140','./img/Soy.jpg','p8');

create table cart(
    id int(11) AUTO_INCREMENT PRIMARY KEY NOT Null,
    product_name varchar(100) not null,
    product_price varchar(50) not null,
    product_image varchar(255) not null,
    qty int(20) not null,
    total_price varchar(100) not null,
    product_code varchar(10) not null
);

create table orders(
    id int(11) AUTO_INCREMENT PRIMARY KEY NOT Null,
    name varchar(100) not null,
    phone varchar(20) not null,
    address varchar(255) not null,
    products varchar(255) not null,
    amount_paid varchar(100) not null
);
