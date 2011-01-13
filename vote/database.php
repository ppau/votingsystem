<?php

	date_default_timezone_set( 'Australia/Sydney' );

	include_once dirname( __FILE__ ) . '/adodb5/adodb.inc.php';
	require_once dirname( __FILE__ ) . '/src/group.php';
	require_once dirname( __FILE__ ) . '/src/standardCurves.php';
	
	$__db = null;
	
	$__config = array(
		'db' => array(
			'driver' => 'mysql',
			'server' => 'localhost',
			'username' => 'voting',
			'password' => 'hvfdq2',
			'database' => 'ppau_database',
		),
		'salt_start' => 'J1#ueNl~Bw08^A',
		'salt_end' => ']K9jwg$jsI3Q',
	);
	
	function get_config()
	{
		global $__config;
		return $__config;
	}
	
	function get_db()
	{
		global $__db;
		
		if( is_object( $__db ) )
		{
			return $__db;
		}
		
		$conf = get_config();
		
		$__db = ADONewConnection( $conf['db']['driver'] ); # eg 'mysql' or 'postgres'
		$__db->debug = false;
		$__db->Connect( $conf['db']['server'], $conf['db']['username'], $conf['db']['password'], $conf['db']['database'] );
		$__db->SetFetchMode( ADODB_FETCH_ASSOC );
		
		return $__db;
	}

	function add_priv_key( $election, $publicKey, $privateKey )
	{
		$db = get_db();
		
		$scaled = $publicKey->scale();
		
		$sql = sprintf( '
			INSERT INTO
				`keys`
			( PubX, PubY, priv, election )
			VALUES
				( \'%s\', \'%s\', \'%s\', \'%s\' )
			',
			$scaled->x->asString( 16 ),
			$scaled->y->asString( 16 ),
			$privateKey->asString( 16 ),
			$election
		);
		
		$query = $db->Execute( $sql );
		
		return $query;
	}
	
	function does_election_exist( $election )
	{
		$db = get_db();
		
		$sql = sprintf( '
			SELECT
				index
			FROM
				`keys`
			WHERE
				election = \'%s\'
			LIMIT
				1',
			$election
		);

		$result = $db->GetRow( $sql );
		return !empty( $result );
	}
	
	function get_registration_accounts()
	{
		$db = get_db();
	
		$sql = sprintf( '
			SELECT
				*
			FROM
				`registrations`
			ORDER BY
				surname, firstname
			'
		);
		
		return $db->GetAll( $sql );
	}
	
	function get_elections()
	{
		$db = get_db();
	
		$sql = sprintf( '
			SELECT
				*
			FROM
				`form_vote`
			ORDER BY
				start DESC
			'
		);
		
		return $db->GetAll( $sql );
	}
	
	function get_elections_by_id( $id )
	{
		$db = get_db();
	
		$sql = sprintf( '
			SELECT
				*
			FROM
				`form_vote`
			WHERE
				id = %d
			LIMIT
				1
			',
			$id
		);
		
		return $db->GetRow( $sql );
	}
	
	function get_question( $id )
	{
		$db = get_db();
		
		$sql = sprintf( '
			SELECT
				q.*
			FROM
				`form_vote_questions` q
			WHERE
				q.id = %d
			',
			$id
		);
		
		return $db->GetRow( $sql );
	}
	
	function get_questions_for_election( $id )
	{
		$db = get_db();
		
		$sql = sprintf( '
			SELECT
				q.*, ( SELECT MAX(rank) FROM form_vote_questions q2 WHERE q.election_id = q2.election_id ) as max_rank
			FROM
				`form_vote_questions` q
			WHERE
				q.election_id = %d
			ORDER BY
				q.rank ASC
			',
			$id
		);
		
		return $db->GetAll( $sql );
	}
	
	function add_pub_key( $publicKey )
	{
		$db = get_db();
		
		$scaled = $publicKey->scale();
		$record = array();
		$record['pubKeyX'] = $scaled->x->asString( 16 );
		$record['pubKeyY'] = $scaled->y->asString( 16 );
		
		return $db->AutoExecute( 'keys', $record, 'INSERT' );
	}
	
	function check_voter_allowed( $voter )
	{
		$db = get_db();
		
		$sql = sprintf( '
			SELECT
				index
			FROM
				`keys`
			WHERE
				pubKeyX = \'%s\' AND
				pubKeyY = \'%s\'
			LIMIT
				1
			',
			$voter->x->asString( 16 ),
			$voter->y->asString( 16 )
		);
		
		$result = $db->GetRow( $sql );
		
		return $result != false;
	}
	
	function r_voter_exists( $voter, $election )
	{
		$db = get_db();
		
		$sql = sprintf( '
			SELECT
				index
			FROM
				`R`
			WHERE
				election = \'%s\' AND
				voterx = \'%s\' AND
				votery = \'%s\'
			LIMIT
				1
			',
			$election,
			$voter->x->asString( 16 ),
			$voter->y->asString( 16 )
		);

		$result = $db->GetRow();
		
		return $result != false;
	}
	
	function r_pub_key_exists( $Rcap )
	{
		$db = get_db();
		
		$sql = sprintf( '
			SELECT
				index
			FROM
				`R`
			WHERE
				pubRX = \'%s\' AND
				pubRY = \'%s\'
			LIMIT
				1',
			$Rcap->x->asString( 16 ),
			$Rcap->y->asString( 16 )
		);
		
		$result = $db->GetRow( $sql );
		
		return $result != false;
	}
	
	function bsig_exist( $Rcap )
	{
		$db = get_db();
		
		$sql = sprintf( '
			SELECT
				bsig
			FROM
				`pubKey`
			WHERE
				pubRX = \'%s\' AND
				pubRY = \'%s\'
			LIMIT
				1
			',
			$Rcap->x->asString( 16 ),
			$Rcap->y->asString( 16 )
		);
		
		$result = $db->GetRow( $sql );
		
		return ( $result != false ) && ( !empty( $result['bsig'] ) );
	}
	
	function r_get_pub( $voter, $election, $group )
	{
		$db = get_db();
		
		$sql = sprintf( '
			SELECT
				pubRX, pubRY
			FROM
				`R`
			WHERE
				election = \'%s\' AND
				voterx = \'%s\' AND
				votery = \'%s\'
			LIMIT
				1
			',
			$election,
			$voter->x->asString( 16 ),
			$voter->y->asString( 16 )
		);
		
		$result = $db->GetRow();
		
		return new elipticCurveValue( $group, $result['pubRX'], $result['pubRY'] , 16 );
	}
	
	function r_get_bsig( $Rcap )
	{
		$db = get_db();
		
		$sql = sprintf( '
			SELECT
				scap, hcap
			FROM
				`R`
			WHERE
				pubRX = \'%s\' AND
				pubRY = \'%s\'
			LIMIT
				1',
			$Rcap->x->asString( 16 ),
			$Rcap->y->asString( 16 )
		);
		
		$result = $db->GetRow( $sql );
		
		return array( new primeFieldValue( $group->n_field, $result['scap'], 16 ), new primeFieldValue( $group->n_field, $result['hcap'], 16 ) ) ;
	}
	
	function r_store_bsig( $scap, $hcap, $Rcap )
	{
		$db = get_db();
		
		$record = array();
		$record['scap'] = $scap->asString( 16 );
		$record['hcap'] = $hcap->asString( 16 );
		
		return $db->AutoExecute( 'R', $record, 'UPDATE', sprintf( 'pubRX = \'%s\' AND pubRY = \'%s\'', $Rcap->x->asString( 16 ), $Rcap->y->asString( 16 ) ) );
	}
	
	function r_get_priv_election( $Rcap, $group )
	{
		$db = get_db();
		
		$sql = sprintf( '
			SELECT
				privR, election
			FROM
				`R`
			WHERE
				pubRX = \'%s\' AND
				pubRY = \'%s\'
			LIMIT
				1
			',
			$Rcap->x->asString( 16 ),
			$Rcap->y->asString( 16 )
		);
		
		$result = $db->GetRow( $sql );
		
		return array( new primeFieldValue( $group->n_field, $result['privR'], 16 ), $result['election'] );
	}
	
	function r_store( $privateKey, $publicKey, $voter, $election )
	{
		$db = get_db();
		
		$scaled = $publicKey->scale();
		
		$record = array();
		$record['pubRX'] = $scaled->x->asString( 16 );
		$record['pubRY'] = $scaled->y->asString( 16 );
		$record['privR'] = $privateKey->asString( 16 );
		$record['election'] = $election;
		$record['voterx'] = $voter->x->asString( 16 );
		$record['votery'] = $voter->y->asString( 16 );
		
		return $db->AutoExecute( 'R', $record, 'INSERT' );
	}
	
	function add_vote( $vote, $bsig, $election )
	{
		$db = get_db();
	
		$record['Rx'] = $bsig['R']['x'];
		$record['Ry'] = $bsig['R']['x'];
		$record['s'] = $bsig['s'];
		$record['election'] = $election;
		$record['vote'] = $vote;
		
		return $db->AutoExecute( 'Votes', $record, 'INSERT' );
	}
	
	function get_priv_key( $election, $group )
	{
		$db = get_db();
		
		$table = 'keys';
		
		$sql = sprintf( '
			SELECT
				priv
			FROM
				`keys`
			WHERE
				election = \'%s\'
			LIMIT
				1
			',
			$election
		);

		$result = $db->GetRow( $sql );
		
		return new primeFieldValue( $group->n_field, $result['priv'], 16 );
	}
	
	function get_pub_key( $election, $group )
	{
		$db = get_db();
		
		$sql = sprintf( '
			SELECT
				PubX, PubY
			FROM
				`keys`
			WHERE
				election = \'%s\'
			LIMIT
				1
			',
			$election
		);
		
		$result = $db->GetRow( $sql );
		
		return new elipticCurveValue( $group, $result['PubX'], $result['PubY'] , 16 );
	}
