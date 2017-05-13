<?php
include 'security.php';
include 'deploy_data_array.php';
$root_path_jackfry = 'root@107.170.53.142:/var/www/html/';
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Deployment</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<div id="header-area">
	</div>
	<div id="sub_header_bg">
		<div id="sub_header_wrap">
			<div id="sub_header">
				<div id="application_title_table">
					<div id="application_title">
						<h2><span class="label label-warning">!</span>Deployment - This deployment page is in a repository itself, so please commit any changes you make.</h2>
					</div>
				</div>
			</div>
		</div>
	</div>

	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,800,400italic' rel='stylesheet' type='text/css'>

	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/bootstrap.css">
	<script src="js/jquery.min.js" type="text/javascript"></script>
	<script src="js/bootstrap.js" type="text/javascript"></script>
	<script src="js/main.js" type="text/javascript"></script>
	<style>
	button {
		display:block !important;
		margin:10px auto !important;
		width: 200px;
	}
	</style>
</head>
<body>

	<div class="wrapper">
		<ul class="nav">
			<!-- <li class="nav-item" data-id="production">Production</li> -->
			<li class="nav-item active" data-id="staging">JackFry.net</li>
		</ul>
	</div>

	<div class="section-wrapper">

		<!-- ////////////////////////////////////////////////////////////////////////// -->
		<!--                                Staging                                     -->
		<!-- ////////////////////////////////////////////////////////////////////////// -->
			<div id="staging" class="section-wrap active">
				<ul class="list">
					<?php foreach ($repo_array as $repo_name => $server) :
						if ( '' !== $server['staging']) :
								$is_auto = false;
							if ( 'auto' == $server['staging'] ) $is_auto = true; ?>
							<li class="list-item">
									<span class="list-title"><?php echo $repo_name; if ($is_auto) echo '&nbsp(Auto)';?></span>
									<span class="list-details"><span class="author-name">FirstName LastName</span><span class="author-date">Mon Mar 17 21:52:11 2016</span></span>
									<button onclick="return are_you_sure( 'staging' ,'<?php echo $repo_name; ?>', '<?php echo $server['repo_name']; ?>', '<?php echo $server['staging']; ?>', '', '' );" <?php echo $is_auto ? 'disabled' : 'type="submit"'; ?> name="submit" class="<?php echo $is_auto ? 'auto_deploy_button btn btn-secondary': 'btn btn-primary'; ?>">Push</button>
							</li>
						<?php endif;
					endforeach; ?>
				</ul>
			</div>

	</div>

	<style>
		#loading_img { position: absolute; display: none; }
		#success_img { position: absolute; display: none; }
		#failed_img { position: absolute; display: none; }
		#loading_bg { z-index:9999; position: fixed; margin:0px; padding:0px; top:0px; right:0px; bottom:0px; left: 0px; display:none; background-color: rgba(0,0,0,0.8) }
	</style>
	<div id="loading_bg">
		<img src="success.gif" id="success_img" data-src="success.gif" style="display:none;">
		<img src="failed.png" id="failed_img" data-src="failed.png" style="display:none;">
		<img src="loading<?php echo rand(1, 10); ?>.gif" id="loading_img" style="display:none;">
	</div>
</body>
</html>

<script>
	function startLoading() {
		var $loading = $('#loading_img');
		var $bg = $("#loading_bg");
		$bg.fadeIn();
		$loading.css({
			'top': Math.floor((window.innerHeight - $loading.height()) / 2) + 'px',
			'left': Math.floor((window.innerWidth - $loading.width()) / 2) + 'px'
		});
		$loading.fadeIn();
	}
	function endLoading(success) {
		var $bg = $("#loading_bg");
		var $loading = $('#loading_img');
		var $img = success ? $("#success_img") : $("#failed_img");
		$img.css({
			'top': Math.floor((window.innerHeight - $img.height()) / 2) + 'px',
			'left': Math.floor((window.innerWidth - $img.width()) / 2) + 'px'
		});
		$loading.fadeOut();
		$img.fadeIn(function() {
			setTimeout(function() {
				$img.fadeOut(function() {
					$bg.fadeOut();
					var src = $img.attr("src");
					$img.attr("src", "");
					$img.attr("src", src);
				});
			}, 2300);
		});
	}

	function are_you_sure(server, repository, git_repository, deploy_path, sync_from_path, additional_deploy_paths) {
		
	  	var push_pull;
	  	var warning;

	  	if ( 'production' == server ) {
	  		warning = 'THIS IS THE '+repository+' REPOSITORY ON THE PRODUCTION SERVER! ONCE YOU PUSH THIS OUT, IT WILL BE LIVE AND THERE IS NO TURNING BACK! YOU HAVE BEEN WARNED!';
		} else {
		warning = 'Do you really want to possibly break '+ server +' by deleting and pulling the ' + repository + ' repository?';
		}

		var confirmation = confirm(warning);

		if( false === confirmation ) {
			return confirmation;
		} else {
			console.log( "server: ", server);
			console.log( "pretty_repository: ", repository);
			console.log( "git_repository: ", git_repository);
			console.log( "deploy_path: ", deploy_path);
			console.log( "sync_from_path: ", sync_from_path);
			console.log( "additional_deploy_paths: ", additional_deploy_paths);
			startLoading();
			$.ajax({
				url: 'execute_deploy.php',
				data: {
					'server': server,
					'pretty_repository': repository,
					'git_repository': git_repository,
					'deploy_path': deploy_path,
					'sync_from_path': sync_from_path,
					'additional_deploy_paths': additional_deploy_paths
				},
				type: 'post',
				// dataType: 'json',
				success: function( data ) {
					endLoading(true);
				},
				error: function(data) {
					console.log(arguments);
					endLoading(false);
				}
			});		
		}

	}

</script>
