<?php

	$db = get_db();
	
	// get rank
	$sql = sprintf( '
		SELECT
			rank, election_id
		FROM
			form_vote_questions
		WHERE
			id = %d
		LIMIT
			1
		',
		$_GET['_param']
	);
	
	$rank = $db->GetRow( $sql );
	
	$sql = sprintf( '
		DELETE FROM
			form_vote_questions
		WHERE
			id = %d
		LIMIT
			1
		',
		$_GET['_param']
	);
	
	$db->Execute( $sql );
	
	$sql = sprintf( '
		UPDATE
			form_vote_questions
		SET
			rank = rank - 1
		WHERE
			rank > %d
		',
		$rank['rank']
	);
	
	$db->Execute( $sql );

	$_SESSION['message'] = 'Successfully removed question from election.';
	header( 'Location: /admin/elections/' . $rank['election_id'] . '/', true, 301 );
	