<?php
$http_hosts_to_allow = array('localhost', '127.0.0.1');
$remote_address_to_allow = array('66.41.227.234');
if ( !in_array($_SERVER['HTTP_HOST'], $http_hosts_to_block) && !in_array($_SERVER['REMOTE_ADDR'], $remote_address_to_allow)) {
	echo $_SERVER['REMOTE_ADDR'];
	die('<!DOCTYPE html>
		<html>
		<body>
			<script type="text/javascript" src="starwolf.js"></script>
			<pre> Access Denied </pre>
		</body>
		</html>
	');
}