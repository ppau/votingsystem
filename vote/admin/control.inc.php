<?php

	date_default_timezone_set( 'Australia/Sydney' );

	$db = get_db();
	
	$sql = sprintf( '
		SELECT
			id, username
		FROM
			form_vote_admins
		ORDER BY
			id ASC
		'
	);
	
	$accounts = $db->GetAll( $sql );
	
	$account_count = count( $accounts );
	
	echo <<< HTML
	<div class="right"><a href="/admin/control/add/">Add Admin</a></div>
	<div class="gap"></div>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<thead>
			<td width="50">ID</td>
			<td>Username</td>
			<td width="80">Action</td>
		</thead>

HTML;
	
	foreach( $accounts as $account )
	{
		echo <<< HTML
		<tbody>
			<td>{$account['id']}</td>
			<td>{$account['username']}</td>
			<td><a href="/admin/control/{$account['id']}/">Edit</a></td>
		</tbody>

HTML;
	}
	
	echo <<< HTML
	</table>
HTML;
