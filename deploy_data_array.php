
<?php
include 'security.php';
$root_path_jackfry = 'root@107.170.53.142:/var/www/html/';

$repo_array = array(
	'RadiantBlades' => array(
		'production' => '',
		'staging' => $root_path_jackfry.'RadiantBlades',
		'repo_name' => 'RadiantBlades',
		),
	'Group Invite' => array(
		'production' => '',
		'staging' => $root_path_jackfry.'group_invite',
		'repo_name' => 'group_invite',
		),
	'Deployment' => array(
		'production' => '',
		'staging' => 'auto',
		'repo_name' => 'deployment',
		),
	);

ksort($repo_array);


