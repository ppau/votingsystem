<?php
	if( !empty( $_POST ) )
	{
		if( strcmp( $_POST['password'], $_POST['confirm'] ) !== 0 )
		{
			$error = 'Passwords do not match up';
		}
		else
		{
			$conf = get_config();
			$db = get_db();
			
			$sql = sprintf( '
				SELECT
					id
				FROM
					form_vote_admins
				WHERE
					username = \'%s\'
				LIMIT
					1
				',
				$_POST['username']
			);
			
			$user = $db->GetRow( $sql );
			
			if( !empty( $user ) )
			{
				$error = 'Username already exists';
			}
			else
			{
				$sql = sprintf( '
					INSERT INTO
						form_vote_admins
					SET
						username = \'%s\',
						password = SHA1( \'%s\' )
					',
					$_POST['username'],
					$conf['salt_start'] . $_POST['password'] . $conf['salt_end']
				);
				
				$db->Execute( $sql );
				
				header( 'Location: /admin/control/' );
				die();
			}
		}
	}
?>
Add new admin account.
<div class="gap"></div>
<?php echo $error; ?>
<form action="/admin/control/add/" enctype="application/x-www-form-urlencoded" method="post">
	<table>
		<tr>
			<td>Username:</td>
			<td><input type="text" name="username" /></td>
		</tr>
		<tr>
			<td>Password:</td>
			<td><input type="password" name="password" /></td>
		</tr>
		<tr>
			<td>Confirm Password:</td>
			<td><input type="password" name="confirm" /></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" name="add" value="Add Account" /></td>
		</tr>
	</table>
</form>
