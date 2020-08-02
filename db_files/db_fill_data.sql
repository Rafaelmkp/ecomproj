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

select * from tb_persons;