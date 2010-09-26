<?php

	date_default_timezone_set( 'Australia/Sydney' );

	$elections = get_elections();
	
	$election_count = count( $elections );
	
	echo <<< HTML
	<div class="right"><a href="/admin/elections/add/">Add Election</a></div>
	<div class="gap"></div>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<thead>
			<td width="50">ID</td>
			<td>Election Name</td>
			<td width="100">Start</td>
			<td width="100">End</td>
			<td width="80" align="center">Status</td>
			<td width="80">Action</td>
		</thead>

HTML;
	
	foreach( $elections as $election )
	{
		$start_date = date( 'd/m/Y', $election['start'] );
		$end_date = date( 'd/m/Y', $election['end'] );
		
		$status = ( strcmp( $election['active'], 'yes' ) === 0 ) ? 'tick' : 'cross';
		
		echo <<< HTML
		<tbody>
			<td>{$election['id']}</td>
			<td>{$election['name']}</td>
			<td>{$start_date}</td>
			<td>{$end_date}</td>
			<td align="center"><img src="/images/{$status}.png" width="16" height="16" /></td>
			<td><a href="/admin/elections/{$election['id']}/">Edit</a></td>
		</tbody>

HTML;
	}
	
	echo <<< HTML
	</table>
HTML;
