// loading scheme:
// 1. Client    10%
// 2. Server    30%
// 3. Client    40%
// 4. Server    60%
// 5. Client    70%
// 6. Server   100%

//-----

var CryptoVote = {};

CryptoVote.updateProgress = function(progress) {}
CryptoVote.showError = function(error) {}
CryptoVote.showSuccess = function() {}
CryptoVote.doReady = function() {}

CryptoVote.init = function() {
	CryptoVote.isReady = false;
	
	// Add a callback to let us know when we have enough entropy for the PRNG
	Clipperz.Async.callbacks('Clipperz.Crypto.PRNG.main_test',[
		MochiKit.Base.method(Clipperz.Crypto.PRNG.defaultRandomGenerator(),'deferredEntropyCollection'),
		CryptoVote.ready
	]);
}

CryptoVote.ready = function() {
	CryptoVote.isReady = true;
	CryptoVote.doReady();
}

// kick off the process
CryptoVote.vote = function(data, key, id)
{
	if(CryptoVote.voting || !CryptoVote.isReady)
		return;
	
	CryptoVote.voting = true;
	CryptoVote.data = data;
	CryptoVote.key = key;
	CryptoVote.id = id;

	// start it up	
	CryptoVote.request();
}

// step one
CryptoVote.request = function()
{
	CryptoVote.updateProgress(0);

	var key = CryptoVote.key;
	var id = CryptoVote.id;
	var group = Clipperz.Crypto.ECC.StandardCurves.P256();
	var d = new Clipperz.Crypto.BigInt(key, 16);
	var hash = 'sha256';
	// serialise, base64 encode and sign
	var reqobj = { 'id': id, 'time': Math.round( ( ( new Date() ).getTime() - Date.UTC( 1970, 0, 1 ) ) / 1000 ) };
	var req = $j.base64Encode( $j.toJSON( reqobj ) );
	var sigobj = Clipperz.Crypto.ECDSA.sign( req, d, group, hash );
	var sig = $j.base64Encode( $j.toJSON( { 'r': sigobj.r.asString( 16 ), 's': sigobj.s.asString( 16 ) } ) );
	var Q = group.multiply( d, group.G() );
	var pk = $j.base64Encode( $j.toJSON( Q.asJSONObj() ) );
	var uri = '/vote/request/req/' + encodeURIComponent(req) + '/sig/' + encodeURIComponent(sig) + '/pk/' + encodeURIComponent(pk);
	
	CryptoVote.updateProgress(10);
	
	$j.ajax( {
		url: uri,
		dataType: 'json',
		success: function( data ) {
			if(data.status != 0)
			{
				CryptoVote.showError("Sorry, an error occurred during the 'request' phase of vote submission. The error the server responded with was: "+data.error);
				return;
			}
			CryptoVote.sign( data, id, group, hash );
		},
		error: function( jqXHR, error ) {
			CryptoVote.showError("Sorry, a '"+error+"' error occurred during the 'request' phase of vote submission.");
		}
	} );
}

// step two
CryptoVote.sign = function(data, id, group, hash)
{
	CryptoVote.updateProgress(30);

	var vote = $j.base64Encode( CryptoVote.data );
	var tx = new Clipperz.Crypto.BigInt( data.key.x, 16 );
	var ty = new Clipperz.Crypto.BigInt( data.key.y, 16 );
	var Rcap = new Clipperz.Crypto.ECC.PrimeField.Point( { x:tx, y:ty, z: new Clipperz.Crypto.BigInt( '1' ) } );
	var blindness = Clipperz.Crypto.ECBlind.blindness( vote, Rcap, group, hash );
	var blindSign = $j.base64Encode( $j.toJSON( { 'hcap': blindness.hcap.asString( 16 ), 'rcap': Rcap.asJSONObj() } ) );
	
	CryptoVote.updateProgress(40);
	$j.ajax( {
		url: '/vote/sign/req/' + encodeURIComponent(blindSign),
		dataType: 'json',
		success: function( data ) {
			if(data.status != 0)
			{
				CryptoVote.showError("Sorry, an error occurred during the 'sign' phase of vote submission. The error the server responded with was: "+data.error);
				return;
			}
			CryptoVote.process( data, id, blindness, vote, Rcap, group, hash );
		},
		error: function(jqXHR, error) {
			CryptoVote.showError("Sorry, a '"+error+"' error occurred during the 'sign' phase of vote submission.");
		}
	} );
}

// step three
CryptoVote.process = function(data, id, blindness, vote, Rcap, group, hash)
{
	CryptoVote.updateProgress(60);

	var hcapOut = new Clipperz.Crypto.BigInt( data.hcap, 16 );
	if( !hcapOut.equals( blindness.hcap ) )
	{
		CryptoVote.showError("Either something REALLY bad happened or you have already submitted your vote.");
		return;
	}

	var scap = new Clipperz.Crypto.BigInt( data.scap, 16 )
	var s = Clipperz.Crypto.ECBlind.unblindness( scap, blindness.R, Rcap, vote, blindness.beta, group, hash );
	var bsig = $j.base64Encode( $j.toJSON( { 's': s.asString( 16 ), 'R': blindness.R.asJSONObj() } ) );

	CryptoVote.updateProgress(70);

	$j.ajax( {
		url: '/vote/process/id/' + id + '/data/' + vote + '/bsig/' + bsig,
		dataType: 'json',
		success: function( data ) {
			if(data.status != 0)
			{
				CryptoVote.showError("Sorry, an error occurred during the 'process' phase of vote submission. The error the server responded with was: "+data.error);
				return;
			}
			CryptoVote.showSuccess();
		},
		error: function(jqXHR, error) {
			CryptoVote.showError("Sorry, a '"+error+"' error occurred during the 'process' phase of vote submission.");
		}
	} );
}

$j(CryptoVote.init);

