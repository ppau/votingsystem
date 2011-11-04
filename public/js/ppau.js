var PPAUVote = {};

// add functions to this array to perform validation - return false to abort
PPAUVote.validators = [];

PPAUVote.init = function() {
	// show loading
	$j('#progressBar').progressBar({showText: false});
	
	// setup callbacks
	CryptoVote.updateProgress = PPAUVote.progress;
	CryptoVote.showError = PPAUVote.error;
	CryptoVote.showSuccess = PPAUVote.success;
	CryptoVote.doReady = PPAUVote.ready;

	// this handler will be removed when we're ready
	$j('#vote').submit(function() {
		$j('.needMoreEntropy').slideDown();	
		return false;
	});

	// animate in the main content	
	PPAUVote.finishLoading(function() {
		$j('#content').slideDown(400);
	});

	// if crypto is ready then allow the user to submit	
	if(CryptoVote.isReady)
		PPAUVote.ready();
}

PPAUVote.addValidator = function(validator)
{
	PPAUVote.validators.push(validator);
}

PPAUVote.error = function(message) {
	PPAUVote.finishLoading(function() {
		$j('#error').html(message);
		$j('#error').slideDown(400);
	});
}

PPAUVote.success = function() {
	PPAUVote.progress(100);
	setTimeout(function() {
		PPAUVote.finishLoading(function() {
			$j('#results').slideDown(400);
		});
	},200);
}

PPAUVote.ready = function() {
	$j('.needMoreEntropy').slideUp();
	$j('#vote').unbind('submit');
	$j('#vote').submit(PPAUVote.submit);
}

PPAUVote.progress = function(progress) {
	$j('#progressBar').progressBar(progress);
}

PPAUVote.submit = function() {
	// validate form first
	for(var i in PPAUVote.validators)
	{
		if(!PPAUVote.validators[i]())
			return false;
	}

	// finish the animation before loading since this can cause browser laggg
	var message = "Now securely submitting your vote.<br />Older browsers/computers may freeze for a few moments while we sign your vote.<br /><b>Please do not interrupt this process.</b>";
	$j('#progressBar').progressBar(0);

	PPAUVote.startLoading(message,function() {
		PPAUVote.progress(10);
		setTimeout(PPAUVote.submitVote, 200);
	});	

	return false;
}

PPAUVote.fetchFormData = function() {
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

PPAUVote.submitVote = function() {
	var data = $j.toJSON(PPAUVote.fetchFormData());
	CryptoVote.vote(data, window.location.href.split('#')[1], pollid);
}

PPAUVote.startLoading = function(message, finishedCallback)
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

PPAUVote.finishLoading = function(finishedCallback)
{
	$j('#loading').slideUp(200,function() {
		$j('#loadingText').hide();
		if(finishedCallback)
			finishedCallback();
	});
}
	
$j(PPAUVote.init);

