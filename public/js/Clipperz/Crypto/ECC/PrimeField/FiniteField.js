if (typeof(Clipperz.Crypto.ECC) == 'undefined') { Clipperz.Crypto.ECC = {}; }
if (typeof(Clipperz.Crypto.ECC.PrimeField) == 'undefined') { Clipperz.Crypto.ECC.PrimeField = {}; }

Clipperz.Crypto.ECC.PrimeField.FiniteField = function(modulus) {
	this._modulus = modulus;

	return this;
};

Clipperz.Crypto.ECC.PrimeField.FiniteField.prototype = MochiKit.Base.update(null, {
	'randomMemberNOZero': function(){
		var randGMPVar = function(a, b){
			var rand1 = Clipperz.Crypto.PRNG.defaultRandomGenerator().getRandomBytes(1024);
			var bignum = new Clipperz.Crypto.BigInt(rand1.toHexString(), 16);
			var one = new Clipperz.Crypto.BigInt("1");
			var modulus = a.subtract(b).add(one);
			var temp = bignum.module(modulus);
			return temp.add(a);
			}
		var one = new Clipperz.Crypto.BigInt("1");
		var temp = this._modulus.subtract(one);
		return randGMPVar(one, temp);
	},
	
	'asString': function(arg) {
		return this.modulus().toString(arg);
	},	

	'modulus': function() {
		return this._modulus;
	},

	'add' : function(a , b) {
		return a.add(b).module(this._modulus);
	},

	'subtract' : function(a , b) {
		return a.add(this.negate(b)).module(this._modulus);
	},
	
	'times2' : function(a){
		return a.add(a).module(this._modulus);
	},
	'times3' : function(a){
		return a.add(a).add(a).module(this._modulus);
	},

	'multiply' : function(a , b) {
		return a.multiply(b).module(this._modulus);
	},

	'fastMultiply' : function(a , b) {
		return a.multiply(b).module(this._modulus);
	},
	
	'square' : function(a) {
		var result;
		var t1;
		result = a.multiply(a , this._modulus);
		
		return result;
	},

	'invert' : function(a) {
		return a.invert(this._modulus);
	},

	'negate' : function(a) {
		return this._modulus.subtract(a);
	},
	//-----------------------------------------------------------------------------
	__syntaxFix__: "syntax fix"
});
