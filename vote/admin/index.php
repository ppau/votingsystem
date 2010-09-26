<?php
	require_once dirname( __FILE__ ) . '/../database.php';

	function passwordProtect()
	{
		$conf = get_config();
		$db = get_db();

		if( isset( $_SERVER['PHP_AUTH_USER'] ) )
		{
			$sql = sprintf( '
				SELECT
					username, password
				FROM
					form_vote_admins
				WHERE
					username = \'%s\'
				LIMIT
					1
				',
				$_SERVER['PHP_AUTH_USER']
			);

			$user = $db->GetRow( $sql );

			if( !empty( $user ) )
			{
				$input_pass = sha1( $conf['salt_start'] . $_SERVER['PHP_AUTH_PW'] . $conf['salt_end'] );

				if( strcmp( $input_pass, $user['password'] ) === 0 )
				{
					$_SESSION['vote_admin_auth'] = 1;
					return;
				}
			}
		}

		header( 'WWW-Authenticate: Basic realm="PPAU Voting Administration"' );
		header( 'HTTP/1.0 401 Unauthorized' );
		die( 'Please login to continue.' );
	}

	session_start();

	if( !isset( $_SESSION['vote_admin_auth'] ) || strcmp( $_SESSION['vote_admin_auth'], 1 ) !== 0 )
	{
		passwordProtect();
	}
	
	$section = ( isset( $_GET['_section'] ) ) ? $_GET['_section'] : 'home';
	
	$menus = array(
		'Home' => array(
			'path' => '/admin/',
			'active' => ( $section == 'home' ) ? true : false,
		),
		'Elections' => array(
			'path' => '/admin/elections/',
			'active' => ( $section == 'elections' ) ? true : false,
		),
		'Registrants' => array(
			'path' => '/admin/users/',
			'active' => ( $section == 'users' ) ? true : false,
		),
		'Control' => array(
			'path' => '/admin/control/',
			'active' => ( $section == 'control' ) ? true : false,
		),
		'Log Off' => array(
			'path' => '/admin/logoff/',
			'active' => ( $section == 'logoff' ) ? true : false,
		),
	)

?>
<html>
	<head>
		<title>Pirate Party Australia - Voting administration panel</title>
		<style type="text/css" media="all">@import "/admin/admin.css";</style>
	</head>
	<body>
		<div id="menuholder">
			<div id="menu">
				<?php
					$output_menu = '';
					foreach( $menus as $menu => $options )
					{
						$output_menu .= '<a href="'.$options['path'].'"'.(($options['active'])?' class="active"':'').'>'.$menu.'</a> - ';
					}
					$output_menu = substr( $output_menu, 0, -2 );
					
					echo $output_menu;
				?>
			</div>
		</div>
		<div id="holder">
			<?php
				$ext = 'inc';
				if( isset( $_GET['_param'] ) )
				{
					switch( $_GET['_param'] )
					{
						case is_numeric( $_GET['_param'] ):
							$ext = 'edit';
							break;
						case 'add':
							$ext = 'add';
							break;
						default:
							$ext = 'inc';
							break;
					}
				}
			
				if( file_exists( dirname( __FILE__ ) . '/' . basename( $section ) . '.'.$ext.'.php' ) )
				{
					include_once dirname( __FILE__ ) . '/' . basename( $section ) . '.'.$ext.'.php';
				}
				else
				{
					include_once dirname( __FILE__ ) . '/home.inc.php';
				}
			?>
		</div>
	</body>
</html>