if (typeof(Clipperz.Crypto.ECC) == 'undefined') { Clipperz.Crypto.ECC = {}; }
if (typeof(Clipperz.Crypto.ECC.PrimeField) == 'undefined') { Clipperz.Crypto.ECC.PrimeField = {}; }

Clipperz.Crypto.ECC.PrimeField.Curve = function(args) {
	args = args || {};	
	
	this._p = args.p;
	this._a = args.a;
	this._b = args.b;
	this._G = args.G;
	this._n = args.n;
	this._h = args.h;

	return this;
};

Clipperz.Crypto.ECC.PrimeField.Curve.prototype = MochiKit.Base.update(null, {

	'asString': function() {
		return "Clipperz.Crypto.ECC.BinaryField.Curve";
	},
	//-----------------------------------------------------------------------------

	'p': function() {
		return this._p;
	},

	'a': function() {
		return this._a;
	},
	
	'b': function() {
		return this._b;
	},
	
	'G': function() {
		return this._G;
	},
	
	'n': function() {
		return this._n;
	},
	
	'h': function() {
		return this._h;
	},
	//-----------------------------------------------------------------------------
	'finiteField_p': function() {
		if (this._finiteField_p == null) {
			this._finiteField_p = new Clipperz.Crypto.ECC.PrimeField.FiniteField(this.p())
		}
		
		return this._finiteField_p;
	},
	'finiteField_n': function() {
		if (this._finiteField_n == null) {
			this._finiteField_n = new Clipperz.Crypto.ECC.PrimeField.FiniteField(this.n())
		}
		
		return this._finiteField_n;
	},

	//-----------------------------------------------------------------------------

	'negate': function(aPointA){
		var result;
		var point;

		point = this.scale(aPointA);
		result = new Clipperz.Crypto.ECC.PrimeField.Point({x:point.x(), y:this.finiteField_p().negate(point.y()), z:point.z()});
		return result;
	},

	//-----------------------------------------------------------------------------
    // point addition in affine coodinates
	'add': function(aPointA, aPointB) {
		if (aPointA.isInfty()){
			return new Clipperz.Crypto.ECC.PrimeField.Point({x:new Clipperz.Crypto.BigInt(aPointB.x().internalValue()) , y:new Clipperz.Crypto.BigInt(aPointB.y().internalValue()) , z:new Clipperz.Crypto.BigInt(aPointB.z().internalValue())});;
		};
		if (aPointB.isInfty()){
			return new Clipperz.Crypto.ECC.PrimeField.Point({x:new Clipperz.Crypto.BigInt(aPointA.x().internalValue()) , y:new Clipperz.Crypto.BigInt(aPointA.y().internalValue()) , z:new Clipperz.Crypto.BigInt(aPointA.z().internalValue())});;
		};
		var result;
		if (aPointA.x().equals(aPointB.x())){
			if (!aPointA.y().equals(aPointB.y())){
				return new Clipperz.Crypto.ECC.PrimeField.Point({x:new Clipperz.Crypto.BigInt("0") , y:new Clipperz.Crypto.BigInt("0") , z:new Clipperz.Crypto.BigInt("1")});
			} else {
				return this.ftimes2(aPointA); 
			}
		}
		var l1  = this.finiteField_p().subtract(aPointB.y() , aPointA.y());
		var l2  = this.finiteField_p().subtract(aPointB.x() , aPointA.x());
		var I   = this.finiteField_p().invert(l2);
		var l   = this.finiteField_p().multiply(l1, I);
		var x1  = this.finiteField_p().square(l);
		var x2  = this.finiteField_p().subtract(x1 , aPointA.x());
		var x   = this.finiteField_p().subtract(x2 , aPointB.x());
		var y1  = this.finiteField_p().subtract(aPointA.z(), x);
		var y2  = this.finiteField_p().multiply(l, y1);
		var y   = this.finiteField_p().subtract(y2, aPointA.y());
		result = new Clipperz.Crypto.ECC.PrimeField.Point({x:x , y:y , z: new Clipperz.Crypto.BigInt("1") });
		

		return result; 
	},
	// adition in standard projective coodinates
	'fadd' : function(aPointA, aPointB) {
		if (aPointA.isInfty()){
			return new Clipperz.Crypto.ECC.PrimeField.Point({x:new Clipperz.Crypto.BigInt(aPointB.x().internalValue()) , y:new Clipperz.Crypto.BigInt(aPointB.y().internalValue()) , z:new Clipperz.Crypto.BigInt(aPointB.z().internalValue())});;
		};
		if (aPointB.isInfty()){
			return new Clipperz.Crypto.ECC.PrimeField.Point({x:new Clipperz.Crypto.BigInt(aPointA.x().internalValue()) , y:new Clipperz.Crypto.BigInt(aPointA.y().internalValue()) , z:new Clipperz.Crypto.BigInt(aPointA.z().internalValue())});;
		};
		var u1    = this.finiteField_p().multiply(aPointB.y(), aPointA.z());
		var u2    = this.finiteField_p().multiply(aPointA.y(), aPointB.z());
		var v1    = this.finiteField_p().multiply(aPointB.x(), aPointA.z());
		var v2    = this.finiteField_p().multiply(aPointA.x(), aPointB.z());
		if (v1.equals(v2)){
			if (!u1.equals(u2)){
				return new Clipperz.Crypto.ECC.PrimeField.Point({x:new Clipperz.Crypto.BigInt("0") , y:new Clipperz.Crypto.BigInt("0") , z:new Clipperz.Crypto.BigInt("1")});
			} else {
				return this.ftimes2(aPointA); 
			}
		}
		var u     = this.finiteField_p().subtract(u1 , u2);
		var u_2   = this.finiteField_p().square(u);
		var v     = this.finiteField_p().subtract(v1 , v2);
		var v_2   = this.finiteField_p().square(v);
		var v_3   = this.finiteField_p().multiply(v_2 , v);
		var v_2v2 = this.finiteField_p().multiply(v_2 , v2);
		var w     = this.finiteField_p().multiply(aPointA.z(), aPointB.z());
		var a1    = this.finiteField_p().multiply(u_2 , w);
		var a2    = this.finiteField_p().times2(v_2v2);
		var a3    = this.finiteField_p().subtract(a1 , v_3);
		var a     = this.finiteField_p().subtract(a3 , a2)
		var x     = this.finiteField_p().multiply(a , v);
		var y1    = this.finiteField_p().subtract(v_2v2, a);
		var y2    = this.finiteField_p().multiply(y1, u);
		var y3    = this.finiteField_p().multiply(v_3, u2)
		var y     = this.finiteField_p().subtract(y2 , y3);
		var z     = this.finiteField_p().multiply(v_3,w);
		
		var result;
		
		result = new Clipperz.Crypto.ECC.PrimeField.Point({x:x , y:y , z:z });
		
		return result;
		
	},

	//-----------------------------------------------------------------------------
    // point doubling in affine coodinates
	'times2' : function(aPoint) {
		var result;
		if (aPoint.isInfty()){
			return new Clipperz.Crypto.ECC.PrimeField.Point({x:new Clipperz.Crypto.BigInt("0") , y:new Clipperz.Crypto.BigInt("0") , z:new Clipperz.Crypto.BigInt("1")});
		};
		var l1  = this.finiteField_p().square(aPoint.x());
		var l2  = this.finiteField_p().times3(l1);
		var l3  = this.finiteField_p().add(l2 , this.a());
		var l4  = this.finiteField_p().times2(aPoint.y());
		var l5  = this.finiteField_p().invert(l4);
		var l   = this.finiteField_p().multiply(l3, l5);
		var x1  = this.finiteField_p().square(l);
		var x2  = this.finiteField_p().times2(aPoint.x());
		var x   = this.finiteField_p().subtract(x1 , x2);
		var y1  = this.finiteField_p().subtract(aPoint.x(), x);
		var y2  = this.finiteField_p().multiply(l , y1);
		var y   = this.finiteField_p().subtract(y2 , aPoint.y());
		
		result = new Clipperz.Crypto.ECC.PrimeField.Point({x:x , y:y , z: new Clipperz.Crypto.BigInt("1")});
		
		return result;
		
	},
	// point doubling in standard projective coodinates
	'ftimes2' : function(aPoint) {
		if (aPoint.isInfty()){
			return new Clipperz.Crypto.ECC.PrimeField.Point({x:new Clipperz.Crypto.BigInt("0") , y:new Clipperz.Crypto.BigInt("0") , z:new Clipperz.Crypto.BigInt("1")});
		};
		var eight = new Clipperz.Crypto.BigInt("8");
		var four  = new Clipperz.Crypto.BigInt("4");
		var w1 = this.finiteField_p().square(aPoint.z());
		var w2 = this.finiteField_p().multiply(this.a() , w1);
		var w3 = this.finiteField_p().square(aPoint.x());
		var w4 = this.finiteField_p().times3(w3);
		var w  = this.finiteField_p().add(w2 , w4);
		var s  = this.finiteField_p().multiply(aPoint.y(),aPoint.z());
		var b1 = this.finiteField_p().multiply(aPoint.y(),aPoint.x());
		var b  = this.finiteField_p().multiply(b1 , s);
		var h1 = this.finiteField_p().square(w);
		var h2 = this.finiteField_p().multiply(b, eight);
		var h  = this.finiteField_p().subtract(h1 , h2);
		var x1 = this.finiteField_p().multiply(h , s);
		var x  = this.finiteField_p().times2(x1);
		var y1 = this.finiteField_p().multiply(b , four);//y1 = 4*b
		var y2 = this.finiteField_p().subtract(y1, h);   //y2 = 4*b - h
		var y3 = this.finiteField_p().multiply(y2, w);   //y3 = w(4*b-h)
		var y4 = this.finiteField_p().square(aPoint.y());//y4 = y^2
		var y5 = this.finiteField_p().square(s);         //y5 = s^2
		var y6 = this.finiteField_p().multiply(y4, y5);  //y6 = y^2*s^2
		var y7 = this.finiteField_p().multiply(y6, eight);//y7=8*y^2*s^2
		var y  = this.finiteField_p().subtract(y3, y7);  //y  = w(4*b-h) - 8*y^2*s^2
		var z1 = this.finiteField_p().square(s);
		var z2 = this.finiteField_p().multiply(z1 , s)
		var z  = this.finiteField_p().multiply(z2 , eight);
		
		var result;
		
		result = new Clipperz.Crypto.ECC.PrimeField.Point({x:x , y:y , z:z});
		
		return result;
		
	},

	//-----------------------------------------------------------------------------


	//-----------------------------------------------------------------------------
    // convert from projective to affine coodinates
	'scale' : function(aPoint) {
		var a = this.finiteField_p().invert(aPoint.z());
		var x = this.finiteField_p().multiply(aPoint.x(),a);
		var y = this.finiteField_p().multiply(aPoint.y(),a);
		
		var result;
		
		result = new Clipperz.Crypto.ECC.PrimeField.Point({x:x , y:y , z: new Clipperz.Crypto.BigInt("1")});
		return result;
	},
	//-----------------------------------------------------------------------------

	// multiply aPoint with aValue, returns Affine coordinate
	'multiply': function(aValue, aPoint) {
		var Q = new Clipperz.Crypto.ECC.PrimeField.Point({x:new Clipperz.Crypto.BigInt("0") , y:new Clipperz.Crypto.BigInt("0") , z:new Clipperz.Crypto.BigInt("1")});
		var P = new Clipperz.Crypto.ECC.PrimeField.Point({x:new Clipperz.Crypto.BigInt(aPoint.x().internalValue()) , y:new Clipperz.Crypto.BigInt(aPoint.y().internalValue()) , z:new Clipperz.Crypto.BigInt(aPoint.z().internalValue())});
		for (var t = 0; t <= aValue.bitSize()-1; t=t+1){
			if(aValue.isBitSet(t)){
				Q = this.fadd(P, Q);
			}
			P = this.ftimes2(P);
		}
		Q = this.scale(Q);
		return new Clipperz.Crypto.ECC.PrimeField.Point({x:new Clipperz.Crypto.BigInt(Q.x().internalValue()) , y:new Clipperz.Crypto.BigInt(Q.y().internalValue()) , z:new Clipperz.Crypto.BigInt(Q.z().internalValue())});;
	},
	
	//multiply aPoint with aValue, returns Projective coordinates

	'fmultiply': function(aValue, aPoint) {
		var Q = new Clipperz.Crypto.ECC.PrimeField.Point({x:new Clipperz.Crypto.BigInt("0") , y:new Clipperz.Crypto.BigInt("0") , z:new Clipperz.Crypto.BigInt("1")});
		var P = new Clipperz.Crypto.ECC.PrimeField.Point({x:new Clipperz.Crypto.BigInt(aPoint.x().internalValue()) , y:new Clipperz.Crypto.BigInt(aPoint.y().internalValue()) , z:new Clipperz.Crypto.BigInt(aPoint.z().internalValue())});
		for (var t = 0; t <= aValue.bitSize()-1; t=t+1){
			if(aValue.isBitSet(t)){
				Q = this.fadd(P, Q);
			}
			P = this.ftimes2(P);
		}
		return new Clipperz.Crypto.ECC.PrimeField.Point({x:new Clipperz.Crypto.BigInt(Q.x().internalValue()) , y:new Clipperz.Crypto.BigInt(Q.y().internalValue()) , z:new Clipperz.Crypto.BigInt(Q.z().internalValue())});;
	},

	'deferredMultiply': function(aValue, aPoint) {

	},

	//-----------------------------------------------------------------------------
	__syntaxFix__: "syntax fix"
});
