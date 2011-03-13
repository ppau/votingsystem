try { if (typeof(Clipperz.ByteArray) == 'undefined') { throw ""; }} catch (e) {
	throw "Clipperz.Crypto.AES depends on Clipperz.ByteArray!";
}
try { if (typeof(Clipperz.Crypto.ECC) == 'undefined') { throw ""; }} catch (e) {
	throw "Clipperz.Crypto.AES depends on Clipperz.Crypto.ECC!";
}

if (typeof(Clipperz.Crypto.ECBlind) == 'undefined') { Clipperz.Crypto.ECBlind = {}; }
MochiKit.Base.update(Clipperz.Crypto.ECBlind, {
	'blindness': function(m, Rcap, group, hash){
		if (hash != 'sha256'){
			return 0;
		}
		var bigInt_0 = new Clipperz.Crypto.BigInt("0");
		
		var byteArrayValue = new Clipperz.ByteArray(m);
		var H = Clipperz.Crypto.SHA.sha256(byteArrayValue);
		var e = new Clipperz.Crypto.BigInt(H.toHexString(), 16);		
		var alpha = group.finiteField_n().randomMemberNOZero();
		var beta = group.finiteField_n().randomMemberNOZero();
		
		//var alpha = new Clipperz.Crypto.BigInt("ff38ae17683837fb58fd80110eebee59f81fd378b134eb265bd148bbf35aef02", 16)
	//	var beta = new Clipperz.Crypto.BigInt("87379bbb01986ac5b37629f6050d1eaaee756548cb718768e523c85f6504096d", 16)
		
		
		var R1 = group.multiply(beta, group.G());
		var R2 = group.multiply(alpha, Rcap);
		var R = group.fadd(R1, R2);
		var R = group.scale(R);
		
		var rinv = group.finiteField_n().invert(R.x());
		
		var hcap1 = group.finiteField_n().multiply(rinv, Rcap.x());
		var hcap2 = group.finiteField_n().multiply(alpha, e);
		var hcap  = group.finiteField_n().multiply(hcap1, hcap2);
		
		return {'hcap':hcap, 'beta':beta, 'R': R};
	},
	'sign': function(Rcap, d, hcap, k, group){
		var scap1 = group.finiteField_n().multiply(hcap, k);
		var scap2 = group.finiteField_n().multiply(Rcap.x(), d);
		var scap  = group.finiteField_n().add(scap1, scap2);
		return scap;
	},
	'unblindness': function(scap, R, Rcap, m, beta, group, hash){
		if (hash != 'sha256'){
			return 0;
		}
		var bigInt_0 = new Clipperz.Crypto.BigInt("0");
		
		var byteArrayValue = new Clipperz.ByteArray(m);
		var H = Clipperz.Crypto.SHA.sha256(byteArrayValue);
		var e = new Clipperz.Crypto.BigInt(H.toHexString(), 16);
		var s2 = group.finiteField_n().multiply(e, beta);
		
		var rcapinv = group.finiteField_n().invert(Rcap.x());
		
		var rrcapinv = group.finiteField_n().multiply(rcapinv, R.x());
		var s1 = group.finiteField_n().multiply(scap, rrcapinv);
		var s = group.finiteField_n().add(s1, s2);
		return s;
	},
	'verify': function(R, s, m, Q, group, hash){
		if (hash != 'sha256'){
			return 0;
		}
		var bigInt_0 = new Clipperz.Crypto.BigInt("0");
		
		var byteArrayValue = new Clipperz.ByteArray(m);
		var H = Clipperz.Crypto.SHA.sha256(byteArrayValue);
		var e = new Clipperz.Crypto.BigInt(H.toHexString(), 16);
		
		
		var L1 = group.multiply(e, R);
		var L2 = group.multiply(R.x(), Q);
		var L  = group.fadd(L1, L2);
		var L = group.scale(L);
		var R  = group.multiply(s, group.G());
		return R.x().equals(L.x()) && R.y().equals(L.y());
	},
	
	//-----------------------------------------------------------------------------
	__syntaxFix__: "syntax fix"
})