<?php

$cid = (isset($argv['c']) ? $argv['c'] : false);
$lon = (isset($argv['o']) ? $argv['o'] : false);
$lat = (isset($argv['a']) ? $argv['a'] : false);
$dis = (isset($argv['d']) ? $argv['d'] : false);
$tid = (isset($argv['t']) ? $argv['t'] : false);
$utc = (isset($argv['u']) ? $argv['u'] : false);
//var_dump($argv);

	 if (!$lon) $vendors['error'] = "longitude";
else if (!$lat) $vendors['error'] = "latitude";
else if (!$utc) $vendors['error'] = "timestamp";
else if (!$cid || $cid == '0') $cid = addClient();
	 if (!$cid || $cid == '0') $vendors['error'] = "identity";

if ($lon < 200 && $lon > -200) $lon *= 10000000; $lon = ceil($lon);
if ($lat < 200 && $lat > -200) $lat *= 10000000; $lat = ceil($lat);

$vendors['client'] = $cid;

if (!$vendors['error'])
$vendors['vendor'] = armadealo($lon,$lat,$dis,$tid);

echo json_encode($vendors);



function armadealo($lon,$lat,$dis,$tid)
{

//	20 miles in meters
	$range = 32186.88; //units from origin
	$limit = 100; //stores to find
//	69 miles in meters
	$max_lat = ceil($lat + ($range/111044.736)*10000000);
	$min_lat = ceil($lat - ($range/111044.736)*10000000);
	$max_lon = ceil($lon + ($range/abs(cos(deg2rad($lat/10000000))*111044.736))*10000000);
	$min_lon = ceil($lon - ($range/abs(cos(deg2rad($lat/10000000))*111044.736))*10000000);

	if (!$dis) $dis = '0';
	
$pythagoras_eq =
"
((SQRT(POWER(($lat - s.store_lat), 2) + POWER(($lon - s.store_lon), 2)) / 10000000) * 102536.7638)
";
//63.7133912 miles for equation

$haverstine_eq =
"
(6366.564864 * 2 * ASIN(SQRT(POWER(SIN(($lat/10000000 - abs(s.store_lat/10000000)) * pi()/180 / 2), 2)
 + COS($lat/10000000 * pi()/180 ) * COS(abs(s.store_lat/10000000) * pi()/180)
 * POWER(SIN(($lon/10000000 - s.store_lon/10000000) * pi()/180 / 2), 2) )))
";
//3956 radius of earth

	$db = mysql_connect('localhost','dasypodidan','X3n@rthr3N');
	if (!mysql_query("use armadealo_vendor",$db)) return "mysql error";

$sql =
"
select
s.vid,
s.sid,
s.store_lat,
s.store_lon,
s.store_open,
s.store_close,
s.store_phone,
s.store_title,
s.store_country,
s.store_address,
s.store_state,
s.store_city,
s.store_zip,
s.direction,
$pythagoras_eq as distance
from
offers2stores `o2s`,
offers `o`,
stores `s`
where s.store_lat between $min_lat and $max_lat
  and s.store_lon between $min_lon and $max_lon
  and s.disable = 0
  and s.sid = o2s.sid
  and o2s.oid = o.oid
  and o.expires >= CURDATE()
  ".($tid > 123 ? "and o.tid = $tid" : "")."
  and o.disable = 0
  and o2s.disable = 0
having distance between $dis and $range
order by distance, o.created
limit $limit
";

//  and s.store_close >= \"$tim\"
//  and s.store_open <= \"$tim\"

$result = mysql_query($sql,$db);
if (mysql_error($db)) return "error";
while ($row = mysql_fetch_assoc($result))
if (!$vendors[$row['vid']] && count($vendors) < 11)
	$vendors[$row['vid']]['store'] = $row;

mysql_free_result($result);

///////////////////////////////////// BIG IF
if (is_array($vendors) && count($vendors) > 0)
{

foreach ($vendors as $vendor)
{

$sql =
"
select
o.vid,
o.oid,
o.expires,
o.offer_code,
o.offer_head,
o.offer_body,
o.offer_link
from
offers2stores `o2s`,
offers `o`
where o2s.sid = ".$vendor[store][sid]."
  and o2s.oid = o.oid
  and o.expires >= CURDATE()
  ".($tid > 123 ? "and o.tid = $tid" : "")."
  and o.disable = 0
  and o2s.disable = 0
order by o.created desc
";

$result = mysql_query($sql,$db);
if (mysql_error($db)) return "error";
while ($row = mysql_fetch_assoc($result))
$vendors[$row['vid']]['store']['offer'][$row['oid']] = $row;

mysql_free_result($result);

}

foreach ($vendors as $vendor)
foreach ($vendor['store']['offer'] as $offer)	
{

$sql =
"
select
a.aid,
a.affects,
o2a.value
from
offers2augments `o2a`,
augments `a`
where o2a.oid = $offer[oid]
  and o2a.aid = a.aid
  and a.disable = 0
  and o2a.disable = 0
order by o2a.aid 
";	

$result = mysql_query($sql,$db);
if (mysql_error($db)) return "error";
while ($row = mysql_fetch_assoc($result))
$vendors[$vendor['store']['vid']]['store']['offer'][$offer['oid']]['augment'][$row['aid']] = $row;

mysql_free_result($result);

}

mysql_close($db);
return $vendors;

}

mysql_close($db);
return array();

}








function getRealIpAddr()
{
	if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
   		$ip=$_SERVER['HTTP_CLIENT_IP'];
   	}
   	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
   	{
  		$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
   	}
   	else
   	{
   		$ip=$_SERVER['REMOTE_ADDR'];
   	}
   	return $ip;
}



function addClient()
{
	$mysqli = new mysqli('localhost','dasypodidan','X3n@rthr3N','armadealo_vendor');
	
	if ($mysqli->connect_error) {
    	die('ERROR: MySQL Connection ('
    		. $mysqli->connect_errno . ') '
           	. $mysqli->connect_error);
	}
	
	$sql = "insert into `clients` (`ggsnsip`) values ('".getRealIpAddr()."')";

	try {
		
	$mysqli->autocommit(false);
	$mysqli->query("insert into `clients` (`ggsnsip`) values ('".getRealIpAddr()."')");
	$cid = $mysqli->insert_id;
	$mysqli->commit();
	
	} catch ( Exception $e ) {

	$mysqli->rollback();
	$mysqli->close();
    return 0;
	exit;
	
	}

	$mysqli->close();
	return $cid;
}



?>