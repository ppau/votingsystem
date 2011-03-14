function submitForm()
{
	alert('lols');
	
	var key = window.location.href.split('#')[1];
	var group = Clipperz.Crypto.ECC.StandardCurves.P256();
	var d = new Clipperz.Crypto.BigInt(key, 16);
	var hash = 'sha256';
	
	for(id in questions)
	{
		submitQuestionRequest(id, d, group, hash);
	}

	return false;
}

// step one
function submitQuestionRequest(id, d, group, hash)
{
	// serialise, base64 encode and sign
	var reqobj = { 'id': id, 'time': Math.round( ( ( new Date() ).getTime() - Date.UTC( 1970, 0, 1 ) ) / 1000 ) };
	var req = $j.base64Encode( $j.toJSON( reqobj ) );
	var sigobj = Clipperz.Crypto.ECDSA.sign( req, d, group, hash );
	var sig = $j.base64Encode( $j.toJSON( { 'r': sigobj.r.asString( 16 ), 's': sigobj.s.asString( 16 ) } ) );
	var Q = group.multiply( d, group.G() );
	var pk = $j.base64Encode( $j.toJSON( Q.asJSONObj() ) );
	var uri = '/vote/request/req/' + encodeURIComponent(req) + '/sig/' + encodeURIComponent(sig) + '/pk/' + encodeURIComponent(pk);
	$j.ajax( {
		url: uri,
		dataType: 'json',
		success: function( data ) {
			if(data === false)
			{
				alert('Sorry, an error occurred during the \'request\' phase of submission for question ID: '+id);
				return;
			}
			submitQuestionSign( data, id, group, hash );
		},
		error: function( jqXHR, error ) {
			alert('Sorry, a '+error+' error occurred during the \'request\' phase of submission for question ID: '+id); 
		}
	} );
}

// step two
function submitQuestionSign(data, id, group, hash)
{
	var vote = $j.base64Encode( $j.toJSON( $j( '#' + id).serializeArray() ) );
	var tx = new Clipperz.Crypto.BigInt( data.x, 16 );
	var ty = new Clipperz.Crypto.BigInt( data.y, 16 );
	var Rcap = new Clipperz.Crypto.ECC.PrimeField.Point( { x:tx, y:ty, z: new Clipperz.Crypto.BigInt( '1' ) } );
	var blindness = Clipperz.Crypto.ECBlind.blindness( vote, Rcap, group, hash );
	var blindSign = $j.base64Encode( $j.toJSON( { 'hcap': blindness.hcap.asString( 16 ), 'Rcap': Rcap.asJSONObj() } ) );
	$j.ajax( {
		url: '/vote/sign/req/' + encodeURIComponent(blindSign),
		dataType: 'json',
		success: function( data ) {
			if(data === false)
			{
				alert('Sorry, an error occurred during the \'sign\' phase of submission for question ID: '+id);
				return;
			}
			submitQuestionProcess( data, id, blindness, vote, Rcap, group, hash );
		},
		error: function(jqXHR, error) {
			alert('Sorry, a '+error+' error occurred during the \'sign\' phase of submission for question ID: '+id);
		}
	} );
}

// step three
function submitQuestionProcess(data, id, blindness, vote, Rcap, group, hash)
{
	var hcapOut = new Clipperz.Crypto.BigInt( data.hcap, 16 );
	if( hcapOut.equals( blindness.hcap ) )
	{
		var scap = new Clipperz.Crypto.BigInt( data.scap, 16 )
		var s = Clipperz.Crypto.ECBlind.unblindness( scap, blindness.R, Rcap, vote, blindness.beta, group, hash );
		var bsig = $j.base64Encode( $j.toJSON( { 's': s.asString( 16 ), 'R': blindness.R.asJSONObj() } ) );
		$j.ajax( {
			url: '/vote/process/id/' + id + '/vote/' + vote + '/bsig/' + bsig,
			dataType: 'json',
			success: function( data ) {
				if(data === false)
				{
					alert('Sorry, an error occurred during the \'process\' phase of submission for question ID: '+id)
					return;
				}
				submitQuestionResults(data);
			},
			error: function(jqXHR, error) {
				alert('Sorry, a '+error+' error occurred during the \'process\' phase of submission for question ID: '+id);
			}
		} );
	}
}

// step four: process results
function submitQuestionResults(data)
{
	$j( '#results' ).empty();
	$j( '#results' ).append( $j.toJSON( data ) );
}

function readyToSubmit()
{
	$j('.needMoreEntropy').slideUp();
	$j('#vote').unbind('submit');
	$j('#vote').submit(submitForm);
}

// INIT: callback when page loaded
$j(function() {

	// Add a callback to let us know when we have enough entropy for the PRNG
	Clipperz.Async.callbacks('Clipperz.Crypto.PRNG.main_test',[
		MochiKit.Base.method(Clipperz.Crypto.PRNG.defaultRandomGenerator(),'deferredEntropyCollection'),
		readyToSubmit
	]);

	// if they click submit before we're ready then display a message saying why it doesnt work
	// this handler will be removed when we're ready
	$j('#vote').submit(function() {
		$j('.needMoreEntropy').slideDown();	
		return false;
	});
	
	$j('.showWhileLoading').slideUp();
	$j('.hideWhileLoading').slideDown();
});
