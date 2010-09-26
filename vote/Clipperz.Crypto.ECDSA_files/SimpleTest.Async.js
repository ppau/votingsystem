MochiKit.Base.update(Clipperz.Async.Deferred.prototype, {
/*
	'_addTest': function(anExpectedValue, aDescription, isDeep, aResult) {
		if (isDeep) {
			SimpleTest.isDeeply(aResult, anExpectedValue, aDescription);
		} else {
			SimpleTest.is(aResult, anExpectedValue, aDescription);
		}

		return aResult;
	},
*/
	'addTest': function (anExpectedValue, aDescription, isDeep) {
//		this.addMethod(this, '_addTest', anExpectedValue, aDescription, isDeep);
//		this.addCallback(Clipperz.Async.test, anExpectedValue, aDescription, isDeep);

		if (isDeep) {
//			SimpleTest.isDeeply(aResult, anExpectedValue, aDescription);
			this.addCallback(Clipperz.Async.Test.isDeeply(anExpectedValue, aDescription));
		} else {
//			SimpleTest.is(aResult, anExpectedValue, aDescription);
			this.addCallback(Clipperz.Async.Test.is(anExpectedValue, aDescription));
		}
	},

	//-------------------------------------------------------------------------

	'shouldSucceed': function (aDescription) {
		this.addCallbackPass(SimpleTest.ok, true,  aDescription);
		this.addErrbackPass (SimpleTest.ok, false, aDescription);
		this.addBoth(MochiKit.Async.succeed, null);
	},

	'shouldFail': function (aDescription) {
		this.addCallbackPass(SimpleTest.ok, false, aDescription);
		this.addErrbackPass (SimpleTest.ok, true,  aDescription);
		this.addBoth(MochiKit.Async.succeed, null);
	},

	//-------------------------------------------------------------------------

});


Clipperz.Async.Test = {};
MochiKit.Base.update(Clipperz.Async.Test, {

	'is': function (anExpectedResult, aDescription) {
		return MochiKit.Base.partial(function (anExpectedResult, aDescription, aResult) {
			SimpleTest.is(aResult, anExpectedResult, aDescription);
		
			return aResult;
		}, anExpectedResult, aDescription);
	},

	//-------------------------------------------------------------------------

	'ok': function (aDescription) {
		return MochiKit.Base.partial(function (aDescription, aResult) {
			SimpleTest.ok(aResult, aDescription);
		
			return aResult;
		}, aDescription);
	},

	//-------------------------------------------------------------------------

	'fail': function(aDescription) {
		return MochiKit.Base.partial(function (aDescription, aResult) {
			SimpleTest.ok(!aResult, aDescription);
		
			return aResult;
		}, aDescription);
	},

	//-------------------------------------------------------------------------

	'isDeeply': function (anExpectedResult, aDescription) {
		return MochiKit.Base.partial(function (anExpectedResult, aDescription, aResult) {
			SimpleTest.isDeeply(aResult, anExpectedResult, aDescription);
		
			return aResult;
		}, anExpectedResult, aDescription);
	},
	
	//-------------------------------------------------------------------------
	__syntaxFix__: "syntax fix"
});


SimpleTest.runDeferredTests = function (aName, aTestSet, aTestArguments) {
	try {
		var deferredTests;
		var aTestName;
		var title;
		
		deferredTests = new Clipperz.Async.Deferred(aName + " <deferred test set>", aTestArguments);

		title = aName;

		aTestName = window.location.href.match(/#.*/);
		if (aTestName && (aTestName != '#')) {
			aTestName = aTestName[0].slice(1);
			if (aTestName in aTestSet) {
	//Clipperz.log("single test execution, via fragment identifier", aTestName);
				title += ' [' + aTestName + ']';
				deferredTests.addCallback(aTestSet[aTestName], aTestArguments);
				deferredTests.addErrback(SimpleTest.ok, false, aTestName);
			} else {
				title = 'WRONG TEST NAME'
				deferredTests.addBoth(is, aTestName, null, "Wrong test name selected to run");
			}
		} else {
			for (aTestName in aTestSet) {
				deferredTests.addCallback(aTestSet[aTestName], aTestArguments);
				deferredTests.addErrback(SimpleTest.ok, false, aTestName);
				deferredTests.addBoth(MochiKit.Async.wait, 0.5);
			}
			deferredTests.addBoth(is, true, true, "FINISH: completed the full stack of tests");
		}

		MochiKit.DOM.currentDocument().title = title;

		deferredTests.addBoth(SimpleTest.finish);
		deferredTests.callback();

		SimpleTest.waitForExplicitFinish();
	} catch (err) {
		var s = "test suite failure!\n";
		var o = {};
		var k = null;
		for (k in err) {
			// ensure unique keys?!
			if (!o[k]) {
				s +=  k + ": " + err[k] + "\n";
				o[k] = err[k];
			}
		}
		ok ( false, s );
	}
}
