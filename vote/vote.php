<html>
	<head>
		<title>PPAU Voting system</title>
		<style>
			body, select { font-size:14px; }
			form { margin:5px; }
			p { color:red; margin:5px; }
			b { color:blue; }
		</style>
		<script type="text/javascript" src="Clipperz/src/js/MochiKit/MochiKit.js"></script>
		
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
		<script src="jquery.json-2.2.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="jquery.base64.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript"> 
			jQuery._$ = MochiKit.DOM.getElement; 
			var $j = jQuery.noConflict(); 
		</script>
		<script type='text/javascript' src='Clipperz/src/js/Clipperz/YUI/Utils.js'></script>
		<script type='text/javascript' src='Clipperz/src/js/Clipperz/YUI/DomHelper.js'></script>
		<script type='text/javascript' src='Clipperz/src/js/Clipperz/Base.js'></script>
		<script type='text/javascript' src='Clipperz/src/js/Clipperz/Logging.js'></script>
		<script type='text/javascript' src='Clipperz/src/js/Clipperz/ByteArray.js'></script>
		<script type='text/javascript' src='Clipperz/src/js/Clipperz/Async.js'></script>
		
		<script type='text/javascript' src='Clipperz/src/js/Clipperz/Crypto/BigInt.js'></script>
		<script type='text/javascript' src='Clipperz/src/js/Clipperz/Crypto/Base.js'></script>
		<script type='text/javascript' src='Clipperz/src/js/Clipperz/Crypto/AES.js'></script>
		<script type='text/javascript' src='Clipperz/src/js/Clipperz/Crypto/SHA.js'></script>
		<script type='text/javascript' src='Clipperz/src/js/Clipperz/Crypto/PRNG.js'></script>
		
		<script type='text/javascript' src='Clipperz/src/js/Clipperz/Crypto/ECC/PrimeField/FiniteField.js'></script>
		<script type='text/javascript' src='Clipperz/src/js/Clipperz/Crypto/ECC/PrimeField/Curve.js'></script>
		<script type='text/javascript' src='Clipperz/src/js/Clipperz/Crypto/ECC/PrimeField/Point.js'></script>
		<script type='text/javascript' src='Clipperz/src/js/Clipperz/Crypto/ECC/StandardCurves.js'></script>
		
		<script type='text/javascript' src='Clipperz/src/js/Clipperz/Crypto/ecdsa.js'></script>
		<script type='text/javascript' src='Clipperz/src/js/Clipperz/Crypto/ECBlind.js'></script>
	</head>
<?php

	if( empty( $_GET['id'] ) )
	{
		die( 'Invalid election request.' );
	}

	require_once dirname( __FILE__ ) . '/database.php';
	
	$db = get_db();
	
	$sql = sprintf( '
		SELECT
			v.name, v.start, v.end
		FROM
			form_vote v
		WHERE
			v.id = %d AND
			v.active = \'yes\'
		LIMIT
			1
		',
		intval( $_GET['id'] )
	);
	
	$election = $db->GetRow( $sql );
	
	if( empty( $election ) )
	{
		die( 'Invalid election campaign.' );
	}
	
	$sql = sprintf( '
		SELECT
			q.id, q.question, q.info, q.type, q.options
		FROM
			form_vote_questions q
		WHERE
			q.vote_id = %d
		ORDER BY
			q.rank ASC
		',
		intval( $_GET['id'] )
	);
	
	$questions = $db->GetAll( $sql );

?>
	<body>
		<p><b>Results:</b>
		<span id="results" style="visibility:hidden;">
		<?php
			// draw html for each result to be shown here, but make it invisible for now
			// exactly how we want to do this feedback I'm not sure yet
			foreach( $questions as $question )
			{
				echo '<span id="result-'.$question['id'].'"></span>';
			}
		?>
		</span>
		</p>
		<?php
		
			$a = array(
				'Rodney',
				'Brendan',
			);
			
			$b = serialize( $a );
			echo $b;
		
			echo '<form id="'.$id.'">';
			
			// create a form for each vote
			foreach( $questions as $question )
			{
				echo '<h3>'.$question['question'].'</h3>';
				if( !empty( $question['info'] ) )
				{
					echo $a[1].'<br />';
				}
				
				switch( $question['type'] )
				{
					case 'check_yes_no':
						echo <<< HTML
						<b>Yes:</b> <input type="radio" name="{$question['id']}" value="Yes">
						<b>No:</b> <input type="radio" name="{$question['id']}" value="No">
HTML;
						break;
					case 'check_custom':
						$options = unserialize( $question['options'] );
						
						if( is_array( $options ) && !empty( $options ) )
						{
							foreach( $options as $option )
							{
								echo <<< HTML
						<b>{$option}:</b> <input type="radio" name="{$question['id']}" value="{$option}">
HTML;
							}
						}
						break;
				}
			}
			
			echo '</form>'."\n";
		?>
	<table>
		<tr style="background:#FFF"><td></td>
			<td><b>Warning: Once this form has been submitted, it can not be changed</b><br /><INPUT TYPE="button" id="submit" NAME="myButton" VALUE="Submit" onClick=""><div id="noentropy">You need to generate more entropy by moving your mouse around the screen before you can submit</div></td></tr>
	</table>
		<script language="JavaScript">
			//document.write (getArray);
			function sendVotes()
			{
				var fullHref = new String( window.location.href );
				var hrefParts = fullHref.split( '#pKey=' );
				var group = Clipperz.Crypto.ECC.StandardCurves.P256();
				var hash = 'sha256';
				var d = new Clipperz.Crypto.BigInt( hrefParts[1], 16 );
				<?php
					// need to send all votes here
					foreach( $questions as $question )
					{
						echo 'sendVote( ' . $question['id'] . ', d, group, hash );' . "\n";
					}
				?>
			}
			
			function sendVote( election, d, group, hash )
			{
				//serialise the form
				var reqobj = { 'election': election, 'stamp': Math.round( ( ( new Date() ).getTime() - Date.UTC( 1970, 0, 1 ) ) / 1000 ) };
				var req = $j.base64Encode( $j.toJSON( reqobj ) );
				var sigobj = Clipperz.Crypto.ECDSA.sign( req, d, group, hash );
				var sig = $j.base64Encode( $j.toJSON( { 'r': sigobj.r.asString( 16 ), 's': sigobj.s.asString( 16 ) } ) );
				var Q = group.multiply( d, group.G() );
				var pk = $j.base64Encode( $j.toJSON( Q.asJSONObj() ) );
				var uri = 'http://vote.pirateparty.org.au/blindReq.php?req='+req+'&sig='+sig+'&pk='+pk+'&callback=?';
				$j.ajax( {
					url: uri,
					dataType: 'jsonp',
					success: function( data ) {
						sendVote2( data, election, group, hash );
					}
				} );
			}
			
			function sendVote2( jsonp, election, group, hash )
			{
				if( jsonp === false )
				{
					//error -- wrong VoterID
				}
				else
				{
					var vote = $j.base64Encode( $j.toJSON( $j( '#' + election ).serializeArray() ) );
					var tx = new Clipperz.Crypto.BigInt( jsonp.x, 16 );
					var ty = new Clipperz.Crypto.BigInt( jsonp.y, 16 );
					var Rcap = new Clipperz.Crypto.ECC.PrimeField.Point( { x:tx, y:ty, z: new Clipperz.Crypto.BigInt( '1' ) } );
					var blindness = Clipperz.Crypto.ECBlind.blindness( vote, Rcap, group, hash );
					var blindSign = $j.base64Encode( $j.toJSON( { 'hcap': blindness.hcap.asString( 16 ), 'Rcap': Rcap.asJSONObj() } ) );
					var uri = 'http://vote.pirateparty.org.au/blindSign.php?req='+blindSign+'&callback=?';
					$j.ajax( {
						url: uri,
						dataType: 'jsonp',
						success: function( data ) {
							sendVote3( data, election, blindness, vote, Rcap, group, hash );
						}
					} );
				}
			}
			
			function sendVote3( data,election, blindness, vote, Rcap, group, hash )
			{
				if( data === false )
				{
					//error submitting vote	contact support errorID 1
				}
				else
				{
					var hcapOut = new Clipperz.Crypto.BigInt( data.hcap, 16 );
					if( hcapOut.equals( blindness.hcap ) )
					{
						var scap = new Clipperz.Crypto.BigInt( data.scap, 16 )
						var s = Clipperz.Crypto.ECBlind.unblindness( scap, blindness.R, Rcap, vote, blindness.beta, group, hash );
						var bsig = $j.base64Encode( $j.toJSON( { 's': s.asString( 16 ), 'R': blindness.R.asJSONObj() } ) );
						var uri = 'http://vote.pirateparty.org.au/addVote.php?vote='+vote+'&election='+election+'&bsig='+bsig+'&callback=?';
						$j.ajax( {
							url: uri,
							dataType: 'jsonp',
							success: function( data ) {
								sendVote4( data );
							}
						} );
					}
				}
			}
			
			function sendVote4( data )
			{
				if( data === false )
				{
					//error submitting vote contact support errorID 2
				}
				else
				{
					$j("#results").empty();
					<?php // we'll want to append something more meaningful later ?>
					$j("#results").append( $j.toJSON( data ) );
				}
			}
			
			$j('#submit').attr("disabled", true);
			$j('#submit').click(function(){sendVotes()});
			Clipperz.Async.callbacks( "Clipperz.Crypto.PRNG.main_test", [
				MochiKit.Base.method( Clipperz.Crypto.PRNG.defaultRandomGenerator(), 'deferredEntropyCollection' ), 
				function() { $j( '#submit' ).attr( "disabled", false ); }
			] );
			//$j("input").change(showValues);
			//$j("select").change(showValues);
			//showValues();
		</script>
	</body>
</html>
