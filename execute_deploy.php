<?php 
include 'security.php';
include 'deploy_data_array.php';
if ( isset($_POST['server'])) {
	if( 'production' == $_POST['server'] ) {
		syncStagingToProduction( $repo_array, $_POST['pretty_repository'], $_POST['git_repository'], $_POST['server'], $_POST['deploy_path'], $_POST['sync_from_path'] );
		if( isset($_POST['additional_deploy_paths']) && !empty($_POST['additional_deploy_paths'])) {
			foreach ($_POST['additional_deploy_paths'] as $path) {
				echo '<pre>';
				echo '<br>path: ';print_r($path);
				echo '</pre>';
				syncStagingToProduction( $repo_array, $_POST['pretty_repository'], $_POST['git_repository'], $_POST['server'], $path, $_POST['sync_from_path'] );
			}
		}
	} elseif ( 'staging' == $_POST['server'] ) {
		pullToStaging( $repo_array, $_POST['git_repository'], $_POST['pretty_repository'], $_POST['deploy_path'], $_POST['server'] );
	} else {

	}
}

function syncStagingToProduction($repo_array, $pretty_repository, $git_repository, $server, $deploy_path, $sync_from_path) {
	$validation = checkValidDeployment( $repo_array, $pretty_repository, $git_repository, 'production', $deploy_path, $sync_from_path );

	if( $validation['is_valid'] ){
		$command = 'sudo rsync -avpg -e ssh '.$sync_from_path.' '.$deploy_path.';';
		echo $command;
		shell_exec($command);
		echo '<br><br>Deployment Complete!';
		
		// header('Location: '.$_SERVER['HTTP_HOST']);
	} else {
		echo '<pre>';
		print_r($validation['message']);
		die();
	}
}

function pullToStaging( $repo_array, $git_repository, $pretty_repository, $deploy_path, $server ) {
	$validation = checkValidDeployment( $repo_array, $pretty_repository, $git_repository, $server, $deploy_path );
	if( false === strpos($deploy_path, '/var/www/html/') 
		) {
		die( 'Invalid Deployment Path. Does not contain valid base path.');
	} else {
		if( $validation['is_valid'] ){
			echo '<br> rm -rf '.$deploy_path.'/.*;';
			echo '<br> rm -rf '.$deploy_path.'/**;';
			echo '<br> git clone git@github.com:jack-freiermuth/'.$git_repository.'.git '.$deploy_path.'/;';
			echo '<br> chmod -R 775 '.$deploy_path.';';
			echo '<br> chgrp -R www-data '.$deploy_path.';';


			shell_exec( 'rm -rf '.$deploy_path.'/.*;');
			shell_exec( 'rm -rf '.$deploy_path.'/**;');
			shell_exec( 'git clone git@github.com:jack-freiermuth/'.$git_repository.'.git '.$deploy_path.'/;');
			shell_exec( 'chmod -R 775 '.$deploy_path.';');
			shell_exec( 'chown -R apache:www-data '.$deploy_path.';');

			echo '<br><br>Deployment Complete!';

			// header('Location: '.$_SERVER['HTTP_HOST']);
		} else {
			echo '<pre>';
			print_r($validation['message']);
			die();
		}
	}
}

function checkValidDeployment( $repo_array, $pretty_repository, $git_repository, $server, $path, $sync_from_path='' ) {
	$validation = array(
		'is_valid' => false,
		'message' => array(),
		);

	if( 'production' == $server ) {
		if ( !isset( $repo_array[$pretty_repository][$server]['path'] ) ) {
			$validation['is_valid'] = false;
			$validation['message'][] = $server.' - Server Path is not set.';
		}
		if(  '' == $repo_array[$pretty_repository][$server]['path'] ) {
			$validation['is_valid'] = false;
			$validation['message'][] = $server.' - Server Path is empty.';
		}

		if(  ' ' == $repo_array[$pretty_repository][$server]['path'] ) {
			$validation['is_valid'] = false;
			$validation['message'][] = $server.' - Server Path is a space.';
		}

		if( $path != $repo_array[$pretty_repository][$server]['path'] 
			&& !in_array($path, $repo_array[$pretty_repository][$server]['additional_deploy_paths'] ) 
		) {
			$validation['is_valid'] = false;
			$validation['message'][] = $server.' - Server Path does not match set path.';
		}


		//Check if sync from path matches
		if( !isset( $repo_array[$pretty_repository][$server]['sync_from_path'] ) ) {
			$validation['is_valid'] = false;
			$validation['message'][] = $server.' - Sync from path is not set.';
		}

		if(  '' == $repo_array[$pretty_repository][$server]['sync_from_path'] ) {
			$validation['is_valid'] = false;
			$validation['message'][] = $server.' - Sync from path is empty.';
		}

		if(  ' ' == $repo_array[$pretty_repository][$server]['sync_from_path'] ) {
			$validation['is_valid'] = false;
			$validation['message'][] = $server.' - Sync from path is a space.';
		}

		if(  $sync_from_path != $repo_array[$pretty_repository][$server]['sync_from_path']) {
			$validation['is_valid'] = false;
			$validation['message'][] = $server.' - Sync from path does not match set path.';
		}


		//Check if repo matches
		if( !isset( $repo_array[$pretty_repository]['repo_name'] ) ) {
			$validation['is_valid'] = false;
			$validation['message'][] = $server.' - Repository is not set.';
		}

		if(  $git_repository != $repo_array[$pretty_repository]['repo_name'] ) {
			$validation['is_valid'] = false;
			$validation['message'][] = $server.' - Repository does not match set Repository.';
		}

		if ( empty($validation['message']) ) {
			$validation['is_valid'] = true;
		}
	} elseif ( 'staging' == $server ) {
		if ( !isset( $repo_array[$pretty_repository][$server] ) ) {
			$validation['is_valid'] = false;
			$validation['message'][] = $server.' - Server is not set.';
		}
			
		if ( '' == $repo_array[$pretty_repository][$server] ) {
			$validation['is_valid'] = false;
			$validation['message'][] = $server.' - Server name is empty.';
		}

		if ( ' ' == $repo_array[$pretty_repository][$server] ) {
			$validation['is_valid'] = false;
			$validation['message'][] = $server.' - Server name is a space.';
		}

		if ( $path != $repo_array[$pretty_repository][$server] ) {
			$validation['is_valid'] = false;
			$validation['message'][] = $server.' - Deployment path doesn\'t match set path.';
		}

		if ( !isset( $repo_array[$pretty_repository]['repo_name'] ) ) {
			$validation['is_valid'] = false;
			$validation['message'][] = $server.' - Repository name is not set.';
		}
		
		if ( $git_repository != $repo_array[$pretty_repository]['repo_name'] ) {
			$validation['is_valid'] = false;
			$validation['message'][] = $server.' - Repository name doesn\'t match set name.';
		}

		if ( empty($validation['message']) ) {
			$validation['is_valid'] = true;
		}
	}
	return $validation;
}