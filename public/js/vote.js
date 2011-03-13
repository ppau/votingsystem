function formSubmit()
{


}

function readyToSubmit()
{
	$j('.needMoreEntropy').slideUp();
	$j('#submit').unbind('click');
}

// INIT: callback when page loaded
$j(function() {
	$j('.showWhileLoading').slideUp();
	$j('.hideWhileLoading').slideDown();

	// Add a callback to let us know when we have enough entropy for the PRNG
	Clipperz.Async.callbacks('Clipperz.Crypto.PRNG.main_test',[
		MochiKit.Base.method(Clipperz.Crypto.PRNG.defaultRandomGenerator(),'deferredEntropyCollection'),
		readyToSubmit
	]);

	// if they click submit before we're ready then display a message saying why it doesnt work
	// this handler will be removed when we're ready
	$j('#submit').click(function() {
		$j('.needMoreEntropy').slideDown();	
		return false;
	});
	$j('#submit').attr('disabled',false);		
});
