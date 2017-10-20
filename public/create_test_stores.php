<?php

//$mysqli = new mysqli('10.6.166.213','gpsol_active','Blund3rbu55','gpsol_active');
$mysqli = new mysqli('localhost','dasypodidan','X3n@rthr3N','armadealo_vendor');

for ($vid = 12345; $vid < 12845; $vid++)
{
	$username = "armadealo".$vid."@armadealo.com";
	$password = md5("armadealo");
	$emailadd = $username;

	$sql =
"
insert into `vendors` (`vid`,`username`,`password`,`emailadd`)
			   values ($vid, \"$username\", \"$password\", \"$emailadd\")
";
	$mysqli->query($sql);

	for ($s = 0; $s < 200; $s++)
	{
	
		$lat = rand(350000000,450000000);
		$lon = rand(-650000000,-750000000);
		$phone = rand(200,999)."-".rand(200,999)."-".rand(200,999);

		$sql =
"
insert into `stores` (`vid`,`store_lat`,`store_lon`,`store_open`,`store_close`,`store_title`,`store_phone`,`store_country`,`store_address`,`store_state`,`store_city`,`store_zip`,`direction`)
			  values ($vid, $lat, $lon, \"08:00:00\", \"18:00:00\", \"Store of $vid\", \"$phone\", \"US\", \"".rand(1000,9999)." Armadealo Dr.\", \"CA\", \"Los Angeles\", \"".rand(10000,99999)."\", \"second floor of the mall, between footlocker and the tinder box.\");
";
		$mysqli->query($sql);
	
	}
}

?>