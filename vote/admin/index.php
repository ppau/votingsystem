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
					*
				FROM
					form_vote_admins
				WHERE
					username = \'%s\'
				LIMIT
					1
				',
				$_SERVER['PHP_AUTH_USER']
			);
			
			$user = $db->GetOne( $sql );
			
			if( !empty( $user ) )
			{
				$input_pass = sha1( $conf['salt_start'] . $_SERVER['PHP_AUTH_PW'] . $conf['salt_end'] );
				if( strcmp( $input_pass, $user['password'] ) === 0 )
				{
					$_SESSION['vote_admin_auth'] = true;
					return true;
				}
			}
		}
		
		header( 'WWW-Authenticate: Basic realm="Login"' );
		header( 'HTTP/1.0 401 Unauthorized' );
		die( 'Please login to continue.' );
	}
	
	session_start();
	
	passwordProtect();