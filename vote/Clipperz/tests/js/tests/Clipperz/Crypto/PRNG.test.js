var tests = {

    //-------------------------------------------------------------------------

	'main_test': function (someTestArgs) {
		return Clipperz.Async.callbacks("Clipperz.Crypto.PRNG.main_test", [
			MochiKit.Base.method(Clipperz.Crypto.PRNG.defaultRandomGenerator(), 'fastEntropyAccumulationForTestingPurpose'), 
			function () {
				var	rand1, rand2;
	
				rand1 = Clipperz.Crypto.PRNG.defaultRandomGenerator().getRandomBytes(1);
				is(rand1.byteAtIndex(0) <= 255, true, "getRandomByte returns always a single byte");

				rand2 = Clipperz.Crypto.PRNG.defaultRandomGenerator().getRandomBytes(1);
				is(rand1.equals(rand2), false, "getRandomByte should almost always return two different values when called into sequence");


				rand1 = Clipperz.Crypto.PRNG.defaultRandomGenerator().getRandomBytes(32);
				rand2 = Clipperz.Crypto.PRNG.defaultRandomGenerator().getRandomBytes(32);
				is(rand1.equals(rand2), false, "getRandomByte should almost always return two different values when called into sequence");
				is(rand1.split(0,1).equals(rand2.split(0,1)), false, "getRandomByte should almost always return two different values when called into sequence");
//				is(Clipperz.Crypto.PRNG.defaultRandomGenerator().getRandomBytes(10000).toHexString(), "rand 1", "Value for random test");
//				is(Clipperz.Crypto.PRNG.defaultRandomGenerator().getRandomBytes(10000).toHexString(), "rand 2", "Value for random test");
//				is(Clipperz.Crypto.PRNG.defaultRandomGenerator().getRandomBytes(10000).toHexString(), "rand 3", "Value for random test");
//				is(Clipperz.Crypto.PRNG.defaultRandomGenerator().getRandomBytes(10000).toHexString(), "rand 4", "Value for random test");
//				is(Clipperz.Crypto.PRNG.defaultRandomGenerator().getRandomBytes(10000).toHexString(), "rand 5", "Value for random test");
//				is(Clipperz.Crypto.PRNG.defaultRandomGenerator().getRandomBytes(10000).toHexString(), "rand 6", "Value for random test");
//				is(Clipperz.Crypto.PRNG.defaultRandomGenerator().getRandomBytes(10000).toHexString(), "rand 7", "Value for random test");
//				is(Clipperz.Crypto.PRNG.defaultRandomGenerator().getRandomBytes(10000).toHexString(), "rand 8", "Value for random test");
//				is(Clipperz.Crypto.PRNG.defaultRandomGenerator().getRandomBytes(10000).toHexString(), "rand 9", "Value for random test");
//				is(Clipperz.Crypto.PRNG.defaultRandomGenerator().getRandomBytes(10000).toHexString(), "rand 10", "Value for random test");
//				is(Clipperz.Crypto.PRNG.defaultRandomGenerator().getRandomBytes(10000).toHexString(), "rand 11", "Value for random test");
//				is(Clipperz.Crypto.PRNG.defaultRandomGenerator().getRandomBytes(10000).toHexString(), "rand 12", "Value for random test");
//				is(Clipperz.Crypto.PRNG.defaultRandomGenerator().getRandomBytes(10000).toHexString(), "rand 13", "Value for random test");
//				is(Clipperz.Crypto.PRNG.defaultRandomGenerator().getRandomBytes(10000).toHexString(), "rand 14", "Value for random test");
			}
		], {trace:false})
	},
	
    //-------------------------------------------------------------------------
    'syntaxFix': MochiKit.Base.noop
};

//#############################################################################

SimpleTest.runDeferredTests("Clipperz.Crypto.PRNG", tests, {trace:false});
