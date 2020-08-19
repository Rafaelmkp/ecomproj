insert into tb_persons
	   values (default, 'rafatata', 'rafa@mail.com', 999999999, default);

insert into tb_persons
	   values (default, 'rafatata', 'rafa@mail.com', 999999999, default),
			  (default,	'tatalita', 'tata@mail.com', 991122334, default),
			  (default, 'raquelll', 'raquel@mail.com', 998765432, default);
              
insert into tb_users
	   values (default, 1, 'rafatata', 'admin', default, default),
	          (default, 2, 'rafadmin', 'admin', default, default),
              (default, 3, 'tatalita', '12345', default, default),
              (default, 4, 'raquell', '12345', default, default);

update tb_users set despassword = '$2y$10$YcQ1ivkNPkpIBPH1dvIzyeigUrbXOpUwZRVK/JVYKkRgKOtZCyqRe' where iduser = 1;
update tb_users set despassword = '$2y$10$YcQ1ivkNPkpIBPH1dvIzyeigUrbXOpUwZRVK/JVYKkRgKOtZCyqRe' where iduser = 2;               
update tb_users set despassword = '$2y$10$s9GL.O8gLXC4KPqTPRzHbO0v.UBfQC8/UGGlU/C5GAPOcR3LOTfe.' where iduser = 3;
update tb_users set despassword = '$2y$10$s9GL.O8gLXC4KPqTPRzHbO0v.UBfQC8/UGGlU/C5GAPOcR3LOTfe.' where iduser = 4;

update tb_users set inadmin = 1 where iduser = 1;
update tb_users set inadmin = 1 where iduser = 2;
update tb_users set inadmin = 1 where iduser = 3;
update tb_users set inadmin = 1 where iduser = 4;

CALL sp_users_save
            ('rafaelmkp', 'rafaelmkp', 'rafaelmkp', 'rafaelmkp@mail.com', 519123456789, 1);

select * from tb_users;

select * from tb_persons;

select * from tb_persons 
inner join tb_users using(idperson)
where desemail = 'raquel@mail.com';

select * from tb_categories;

call sp_categories_save(NULL, 'Google');

SELECT * FROM tb_products;	

CALL sp_products_save(null, 
					  'iPad 7 32GB Wi-Fi Tela 10,2'' 8 MP Cinza Espacial Apple',
                      3799.00, 
                      12.01,
                      5.01, 
                      24.01,
                      0.93, 
                      'http://ipad-7-geracao-32gb');
                      
INSERT INTO tb_products (desproduct, vlprice, vlwidth, vlheight, vllength, vlweight, desurl) VALUES
('Smartphone Motorola Moto G5 Plus', 1135.23, 15.2, 7.4, 0.7, 0.160, 'smartphone-motorola-moto-g5-plus'),
('Smartphone Moto Z Play', 1887.78, 14.1, 0.9, 1.16, 0.134, 'smartphone-moto-z-play'),
('Smartphone Samsung Galaxy J5 Pro', 1299, 14.6, 7.1, 0.8, 0.160, 'smartphone-samsung-galaxy-j5'),
('Smartphone Samsung Galaxy J7 Prime', 1149, 15.1, 7.5, 0.8, 0.160, 'smartphone-samsung-galaxy-j7'),
('Smartphone Samsung Galaxy J3 Dual', 679.90, 14.2, 7.1, 0.7, 0.138, 'smartphone-samsung-galaxy-j3');

-- query for products related to x category
SELECT * FROM tb_products WHERE idproduct IN(
	SELECT a.idproduct
    FROM tb_products a
    INNER JOIN tb_productscategories b ON a.idproduct = b.idproduct
    WHERE b.idcategory = 6
);

-- query for products NOT related to x category
SELECT * FROM tb_products WHERE idproduct NOT IN(
	SELECT a.idproduct
    FROM tb_products a
    INNER JOIN tb_productscategories b ON a.idproduct = b.idproduct
    WHERE b.idcategory = 6
);