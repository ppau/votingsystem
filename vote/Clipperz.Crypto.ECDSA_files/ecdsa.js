try { if (typeof(Clipperz.ByteArray) == 'undefined') { throw ""; }} catch (e) {
	throw "Clipperz.Crypto.AES depends on Clipperz.ByteArray!";
}
try { if (typeof(Clipperz.Crypto.ECC) == 'undefined') { throw ""; }} catch (e) {
	throw "Clipperz.Crypto.AES depends on Clipperz.Crypto.ECC!";
}

if (typeof(Clipperz.Crypto.ECDSA) == 'undefined') { Clipperz.Crypto.ECDSA = {}; }
MochiKit.Base.update(Clipperz.Crypto.ECDSA, {
	'sign': function(m, da, group, hash){
		if (hash != 'sha256'){
			return 0;
		}
		var bigInt_0 = new Clipperz.Crypto.BigInt("0");
		var k = group.finiteField_n().randomMemberNOZero();
		var kinv = group.finiteField_n().invert(k);
		var R = group.multiply(k, group.G());
		var r = group.finiteField_n().add(R.x(), bigInt_0);
		
		var byteArrayValue = new Clipperz.ByteArray(m);
		var H = Clipperz.Crypto.SHA.sha256(byteArrayValue);
		var e1 = new Clipperz.Crypto.BigInt(H.toHexString(), 16);
		var e = group.finiteField_n().add(bigInt_0, e1);

		var s2 = group.finiteField_n().multiply(r, da);
		var s3 = group.finiteField_n().add(s2, e);
		var s = group.finiteField_n().multiply(kinv, s3);
		return {'r':r, 's':s};
	},
	'verify': function(m, Qa, r, s, group, hash){
		var bigInt_0 = new Clipperz.Crypto.BigInt("0");
		
		if (hash != 'sha256'){
			return 0;
		}
		
		var byteArrayValue = new Clipperz.ByteArray(m);
		var H = Clipperz.Crypto.SHA.sha256(byteArrayValue);
		var e1 = new Clipperz.Crypto.BigInt(H.toHexString(), 16);
		var e = group.finiteField_n().add(bigInt_0, e1);
		var w = group.finiteField_n().invert(s);
		var u1 = group.finiteField_n().multiply(e, w);
		var u2 = group.finiteField_n().multiply(r, w);
		var R1 = group.multiply(u1, group.G());
		var R2 = group.multiply(u2, Qa);
		var R  = group.add(R1, R2);
		return R.x().equals(r);
	},
	
	//-----------------------------------------------------------------------------
	__syntaxFix__: "syntax fix"
})