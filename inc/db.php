<?php

F3::sql(
	array(
		'CREATE TABLE IF NOT EXISTS kul ('.
			'tc INT UNSIGNED NOT NULL,'.
			'ad CHAR (15),'.
			'soyad CHAR (20),'.
			'PRIMARY KEY(tc)'.
		');' 
	)
);

?>
