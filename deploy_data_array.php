
<?php
include 'security.php';
$root_path_jackfry = '/var/www/html/';

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
	"Portfolio" => array(
		'production' => '',
		'staging' => $root_path_jackfry.'portfolio',
		'repo_name' => 'portfolio',
		), 
	'Deployment' => array(
		'production' => '',
		'staging' => 'auto',
		'repo_name' => 'deployment',
		),
	);

ksort($repo_array);


// git clone git@github.com:jack-freiermuth/RadiantBlades.git 

// http://jackfry.net/git_scripts/deployment_repository.php