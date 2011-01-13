<?php

	$db = get_db();

	// adjust ranking of questions
	$sql = sprintf( '
		SELECT
			q.*
		FROM
			form_vote_questions q
		WHERE
			q.id = %d
		LIMIT
			1
		',
		$_GET['_param']
	);
	
	$question = $db->GetRow( $sql );
	
	$sql = sprintf( '
		UPDATE
			form_vote_questions
		SET
			rank = rank - 1
		WHERE
			rank = %d
		',
		( $question['rank'] + 1 )
	);
	
	$db->Execute( $sql );
	
	$sql = sprintf( '
		UPDATE
			form_vote_questions
		SET
			rank = %d
		WHERE
			id = %d
		',
		( $question['rank'] + 1 ),
		$_GET['_param']
	);
	
	$db->Execute( $sql );
	
	$_SESSION['message'] = 'Updated ranking';
	header( 'Location: /admin/elections/' . $question['election_id'] . '/', true, 301 );