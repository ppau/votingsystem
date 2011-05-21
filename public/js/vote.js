// loading scheme:
// 1. Client    10%
// 2. Server    30%
// 3. Client    40%
// 4. Server    60%
// 5. Client    70%
// 6. Server   100%

function submitForm()
{
	// finish the animation before loading since this can cause browser laggg
	var message = "Now securely submitting your vote.<br />Older browsers/computers may freeze for a few moments while we sign your vote.<br /><b>Please do not interrupt this process.</b>";
	setProgress(0);

	startLoading(message,function() {
		setProgress(10);
		setTimeout(submitRequest,200);
	});	

	return false;
}

/* unused */
function submitQuestions()
{
	// finish the animation before loading since this can cause browser laggg
	var message = "Now securely submitting your vote.<br />Older browsers/computers may freeze for a few moments while we sign your vote.<br /><b>Please do not interrupt this process.</b>";

	startLoading(message,function() {
		var key = window.location.href.split('#')[1];
		var group = Clipperz.Crypto.ECC.StandardCurves.P256();
		var d = new Clipperz.Crypto.BigInt(key, 16);
		var hash = 'sha256';
		
		for(index in questions)
		{
			submitQuestionRequest(questions[index], d, group, hash);
		}
	});	

	return false;
}

// step one
function submitRequest()
{
	$j('#progressBar').progressBar(0);

	var key = window.location.href.split('#')[1];
	var group = Clipperz.Crypto.ECC.StandardCurves.P256();
	var d = new Clipperz.Crypto.BigInt(key, 16);
	var hash = 'sha256';
	// serialise, base64 encode and sign
	var reqobj = { 'id': pollid, 'time': Math.round( ( ( new Date() ).getTime() - Date.UTC( 1970, 0, 1 ) ) / 1000 ) };
	var req = $j.base64Encode( $j.toJSON( reqobj ) );
	var sigobj = Clipperz.Crypto.ECDSA.sign( req, d, group, hash );
	var sig = $j.base64Encode( $j.toJSON( { 'r': sigobj.r.asString( 16 ), 's': sigobj.s.asString( 16 ) } ) );
	var Q = group.multiply( d, group.G() );
	var pk = $j.base64Encode( $j.toJSON( Q.asJSONObj() ) );
	var uri = '/vote/request/req/' + encodeURIComponent(req) + '/sig/' + encodeURIComponent(sig) + '/pk/' + encodeURIComponent(pk);
	
	$j('#progressBar').progressBar(10);
	$j.ajax( {
		url: uri,
		dataType: 'json',
		success: function( data ) {
			if(data.status != 0)
			{
				showError("Sorry, an error occurred during the 'request' phase of vote submission. The error the server responded with was: "+data.error);
				return;
			}
			submitSign( data, pollid, group, hash );
		},
		error: function( jqXHR, error ) {
			showError("Sorry, a '"+error+"' error occurred during the 'request' phase of vote submission.");
		}
	} );
}

// step two
function submitSign(data, pollid, group, hash)
{
	$j('#progressBar').progressBar(30);

	var vote = $j.base64Encode( $j.toJSON( fetchFormData() ) );
	var tx = new Clipperz.Crypto.BigInt( data.key.x, 16 );
	var ty = new Clipperz.Crypto.BigInt( data.key.y, 16 );
	var Rcap = new Clipperz.Crypto.ECC.PrimeField.Point( { x:tx, y:ty, z: new Clipperz.Crypto.BigInt( '1' ) } );
	var blindness = Clipperz.Crypto.ECBlind.blindness( vote, Rcap, group, hash );
	var blindSign = $j.base64Encode( $j.toJSON( { 'hcap': blindness.hcap.asString( 16 ), 'rcap': Rcap.asJSONObj() } ) );
	
	$j('#progressBar').progressBar(40);
	$j.ajax( {
		url: '/vote/sign/req/' + encodeURIComponent(blindSign),
		dataType: 'json',
		success: function( data ) {
			if(data.status != 0)
			{
				showError("Sorry, an error occurred during the 'sign' phase of vote submission. The error the server responded with was: "+data.error);
				return;
			}
			submitProcess( data, pollid, blindness, vote, Rcap, group, hash );
		},
		error: function(jqXHR, error) {
			showError("Sorry, a '"+error+"' error occurred during the 'sign' phase of vote submission.");
		}
	} );
}

// step three
function submitProcess(data, pollid, blindness, vote, Rcap, group, hash)
{
	$j('#progressBar').progressBar(60);

	var hcapOut = new Clipperz.Crypto.BigInt( data.hcap, 16 );
	if( !hcapOut.equals( blindness.hcap ) )
	{
		//showError("Sorry, but I couldn't match the thing we made with the thing we got from the server so I have to bail out here.");
		showError("Either something REALLY bad happened or you have already submitted your vote.");
		return;
	}

	var scap = new Clipperz.Crypto.BigInt( data.scap, 16 )
	var s = Clipperz.Crypto.ECBlind.unblindness( scap, blindness.R, Rcap, vote, blindness.beta, group, hash );
	var bsig = $j.base64Encode( $j.toJSON( { 's': s.asString( 16 ), 'R': blindness.R.asJSONObj() } ) );

	$j('#progressBar').progressBar(70);

	$j.ajax( {
		url: '/vote/process/id/' + pollid + '/data/' + vote + '/bsig/' + bsig,
		dataType: 'json',
		success: function( data ) {
			if(data.status != 0)
			{
				showError("Sorry, an error occurred during the 'process' phase of vote submission. The error the server responded with was: "+data.error);
				return;
			}
			submitQuestionResults(data);
		},
		error: function(jqXHR, error) {
			showError("Sorry, a '"+error+"' error occurred during the 'process' phase of vote submission.");
		}
	} );
}

// step four: process results
function submitQuestionResults(data)
{
	/*$j( '#results' ).empty();
	$j( '#results' ).append( $j.toJSON( data ) );*/
	$j('#progressBar').progressBar(100);
	setTimeout("showResults('')",200);
}

function readyToSubmit()
{
	$j('.needMoreEntropy').slideUp();
	$j('#vote').unbind('submit');
	$j('#vote').submit(submitForm);
}

// progress bar
function setProgress(progress)
{
	$j('#progressBar').progressBar(progress);
}

// animation/transitions
function startLoading(message,finishedCallback)
{
	if(message != null)
	{
		$j('#loadingText').html(message).show();
	}
	$j('#content').slideUp(200,function() {
		if(finishedCallback)
			$j('#loading').slideDown(400,finishedCallback);
		else
			$j('#loading').slideDown(400);
	});
}

function finishLoading(finishedCallback)
{
	$j('#loading').slideUp(200,function() {
		$j('#loadingText').hide();
		if(finishedCallback)
			finishedCallback();
	});
}


function showContent()
{
	finishLoading(function() {
		$j('#content').slideDown(400);
	});
}

function showResults(results)
{
	finishLoading(function() {
//		$j('#results').html(results);
		$j('#results').slideDown(400);
	});
}

function showError(error)
{
	finishLoading(function() {
		$j('#error').html(error);
		$j('#error').slideDown(400);
	});
}

function fetchFormData()
{
	var elements = $j('#vote').serializeArray();
	var data = new Object();
	for(e in elements)
	{
		// if an element with this name already exists make it an array
		if(data[elements[e].name])
		{
			// if we already have an array, push value onto it
			if(data[elements[e].name] instanceof Array)
			{
				data[elements[e].name].push(elements[e].value);
			}
			else // make this an array so it can hold multiple values
			{
				data[elements[e].name] = [data[elements[e].name],elements[e].value];
			}
		}
		else
		{
			data[elements[e].name] = elements[e].value;
		}
	}
	return data;
}

// INIT: callback when page loaded
$j(function() {
	$j('#progressBar').progressBar({showText: false});

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
	
	showContent();
});
