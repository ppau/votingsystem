<?php

	date_default_timezone_set( 'Australia/Sydney' );

	$users = get_registration_accounts();
	
	$user_count = count( $users );
	
	echo <<< HTML
	<div class="right"><a href="/admin/users/add/">Add User</a></div>
	<div class="gap"></div>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<thead>
			<td width="50">ID</td>
			<td>First Name</td>
			<td>Last Name</td>
			<td width="320">Email Address</td>
			<td width="80">Action</td>
		</thead>

HTML;
	
	foreach( $users as $user )
	{
		echo <<< HTML
		<tbody>
			<td>{$user['id']}</td>
			<td>{$user['firstname']}</td>
			<td>{$user['surname']}</td>
			<td>{$user['email']}</td>
			<td><a href="/admin/users/{$user['id']}/">Edit</a></td>
		</tbody>

HTML;
	}
	
	echo <<< HTML
	</table>
HTML;
