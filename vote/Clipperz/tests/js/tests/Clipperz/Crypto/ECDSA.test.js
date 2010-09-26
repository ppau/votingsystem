var tests = {

    //-------------------------------------------------------------------------
    // tests come from http://point-at-infinity.org/ecc/nisttv 
	'ecdsa_test': function (someTestArgs) {
		return Clipperz.Async.callbacks("Clipperz.Crypto.ECDSA.main_test", [
			MochiKit.Base.method(Clipperz.Crypto.PRNG.defaultRandomGenerator(), 'deferredEntropyCollection'), 
			function () {
				var group = Clipperz.Crypto.ECC.StandardCurves.P256();
				var M = "This is only a test message. It is 48 bytes long";
				//var d = group.finiteField_n().randomMemberNOZero();
				var d = new Clipperz.Crypto.BigInt("6eedf9e45d6e6a4e5dce2f7341d7aaabb55f4ae2b04c2a591410324b41eda2e4", 16);
				var Q = group.multiply(d,group.G());
				var hash = 'sha256';
				var signature = Clipperz.Crypto.ECDSA.sign(M, d, group, hash);
				
				var result = Clipperz.Crypto.ECDSA.verify(M, Q, signature.r, signature.s, group, hash);
				is(result, true, "Does signature work");
				
				//var k = group.finiteField_n().randomMemberNOZero();
				var k = new Clipperz.Crypto.BigInt("c083d8a342125e2f25c11628c3e0f85e7a5bc75445fa4c9cd8b6ceb4b486a842", 16)
				var Rcap = group.multiply(k,group.G());
				var blindness = Clipperz.Crypto.ECBlind.blindness(M, Rcap, group, hash);
				var hcap = blindness.hcap;
				var beta = blindness.beta;
				var R = blindness.R;
				var scap = Clipperz.Crypto.ECBlind.sign(Rcap, d, hcap, k, group);
				var s = Clipperz.Crypto.ECBlind.unblindness(scap, R, Rcap, M, beta, group, hash);
				var result = Clipperz.Crypto.ECBlind.verify(R, s, M, Q, group, hash);
				is(result, true, "Does blind signature work");
				return;
			}
		], {trace:false})
	},
    //-------------------------------------------------------------------------
    'syntaxFix': MochiKit.Base.noop
};



//#############################################################################

SimpleTest.runDeferredTests("Clipperz.Crypto.ECDSA", tests, {trace:false});
