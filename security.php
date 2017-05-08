<?php
if ( $_SERVER['HTTP_HOST'] != 'localhost' && $_SERVER['HTTP_HOST'] != '127.0.0.1' ) {
	if(preg_match("((((107\.170\.53\.142)\..*).*)$)", $_SERVER['REMOTE_ADDR']) == 0){
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
}