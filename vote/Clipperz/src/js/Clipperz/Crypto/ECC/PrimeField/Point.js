if (typeof(Clipperz.Crypto.ECC) == 'undefined') { Clipperz.Crypto.ECC = {}; }
if (typeof(Clipperz.Crypto.ECC.PrimeField) == 'undefined') { Clipperz.Crypto.ECC.PrimeField = {}; }

Clipperz.Crypto.ECC.PrimeField.Point = function(args) {
	args = args || {};	

	this._x = args.x;
	this._y = args.y;
	this._z = args.z;
	this._isInfty = false;

	return this;
};
MochiKit.Base.update(Clipperz.Crypto.ECC.PrimeField.Point.prototype, {

	'asString': function(aBase) {
		return "x :"+this.x().asString(aBase) + ", y:" + this.y().asString(aBase);
	},

	//-----------------------------------------------------------------------------

	'x': function() {
		return this._x;
	},
	
	'y': function() {
		return this._y;
	},
	
	'z': function() {
		return this._z;
	},
	'asJSONObj' : function() {
		return {'x':this.x().asString(16), 'y':this.y().asString(16)};
	},
	'isInfty' : function() {
		if(this.x().isZero && this.y().isZero() && this.z().equals(Clipperz.Crypto.BigInt.ONE)){
			return true;
		} else {
			return false;
		}
		return false;
	},
	//-----------------------------------------------------------------------------

	'isZero': function() {
		return (this.x().isZero() && this.y().isZero())
	},

	//-----------------------------------------------------------------------------

	'isAffine' : function() {
		return this.z().compare(Clipperz.Crypto.BigInt.ONE) == 0;
	},
	
	//-----------------------------------------------------------------------------
	__syntaxFix__: "syntax fix"
});
