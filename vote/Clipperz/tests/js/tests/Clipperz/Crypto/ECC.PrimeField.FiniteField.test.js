var tests = {

    //-------------------------------------------------------------------------

	'main_test': function (someTestArgs) {
		return Clipperz.Async.callbacks("Clipperz.Crypto.PRNG.main_test", [
			MochiKit.Base.method(Clipperz.Crypto.PRNG.defaultRandomGenerator(), 'deferredEntropyCollection'), 
			function () {
		var	PrimeField_1;
		var	bigInt_1;
		var	bigInt_2;
		var	result;
		var	expectedResult;
		//
		//	Constructur and equality test
		//
		
		bigInt_1 = new Clipperz.Crypto.BigInt("29");
		bigInt_2 = new Clipperz.Crypto.BigInt("1");
		PrimeField = new Clipperz.Crypto.ECC.PrimeField.FiniteField(bigInt_1);			
		is (PrimeField.asString(10) == bigInt_1.toString(10), true, "Test asString Method" );
		is (true, true, PrimeField.randomMemberNOZero().toString(10));
		bigInt_1 = new Clipperz.Crypto.BigInt("13");
		bigInt_2 = new Clipperz.Crypto.BigInt("20");
		is (PrimeField.add(bigInt_1,bigInt_2).toString(10) == "4", true, "Test Modular Addition 13 + 20 mod 29 = 4 (" + PrimeField.add(bigInt_1,bigInt_2).toString(10) + ")");
		
		bigInt_1 = new Clipperz.Crypto.BigInt("14");
		bigInt_2 = new Clipperz.Crypto.BigInt("17");
		is (PrimeField.subtract(bigInt_1,bigInt_2).toString(10) == "26", true, "Test Modular Subtraction 14 - 17 mod 29 = 26 (" + PrimeField.subtract(bigInt_1,bigInt_2).toString(10) + ")");

		bigInt_1 = new Clipperz.Crypto.BigInt("10");
		bigInt_2 = new Clipperz.Crypto.BigInt("27");
		is (PrimeField.multiply(bigInt_1,bigInt_2).toString(10) == "9", true, "Test Modular Addition 10 x 27 mod 29 = 9 (" + PrimeField.multiply(bigInt_1,bigInt_2).toString(10) + ")");

		bigInt_1 = new Clipperz.Crypto.BigInt("10");
		is (PrimeField.square(bigInt_1).toString(10) == "13", true, "Test Modular Squaring 10^2 mod 29 = 13 (" + PrimeField.square(bigInt_1).toString(10) + ")");

		bigInt_1 = new Clipperz.Crypto.BigInt("10");
		is (PrimeField.invert(bigInt_1).toString(10) == "3", true, "Test Modular Inversion 10^-1 mod 29 = 3 (" + PrimeField.invert(bigInt_1).toString(10) + ")");

		bigInt_1 = new Clipperz.Crypto.BigInt("10");
		is (PrimeField.negate(bigInt_1).toString(10) == "19", true, "Test Modular Negation -10 mod 29 = 19 (" + PrimeField.invert(bigInt_1).toString(10) + ")");

		bigInt_1 = new Clipperz.Crypto.BigInt("ffffffff00000000ffffffffffffffffbce6faada7179e84f3b9cac2fc632551", 16);
		bigInt_2 = new Clipperz.Crypto.BigInt("580ec00d856434334cef3f71ecaed4965b12ae37fa47055b1965c7b134ee45d0", 16);
		PrimeField = new Clipperz.Crypto.ECC.PrimeField.FiniteField(bigInt_1);
		is (PrimeField.invert(bigInt_2).toString(16) == "6a664fa115356d33f16331b54c4e7ce967965386c7dcbf2904604d0c132b4a74", true, PrimeField.invert(bigInt_2).toString(16));

		bigInt_1 = new Clipperz.Crypto.BigInt("ffffffff00000000ffffffffffffffffbce6faada7179e84f3b9cac2fc632551", 16);
		bigInt_2 = new Clipperz.Crypto.BigInt("580ec00d856434334cef3f71ecaed4965b12ae37fa47055b1965c7b134ee45d0", 16);
		PrimeField = new Clipperz.Crypto.ECC.PrimeField.FiniteField(bigInt_1);
		is (PrimeField.invert(bigInt_2).toString(16) == "6a664fa115356d33f16331b54c4e7ce967965386c7dcbf2904604d0c132b4a74", true, PrimeField.invert(bigInt_2).toString(16));
		
		var q;
		var a;
		var b;
		var Xg;
		var Yg;
		var G;
		var n;
		var h;
		var tx;
		var ty;
		var curve;
		var point;
		var point2;
		var tm;
		q = new Clipperz.Crypto.BigInt("ffffffff00000001000000000000000000000000ffffffffffffffffffffffff" , 16);
		a = new Clipperz.Crypto.BigInt("ffffffff00000001000000000000000000000000fffffffffffffffffffffffc" , 16);
		b = new Clipperz.Crypto.BigInt("5ac635d8aa3a93e7b3ebbd55769886bc651d06b0cc53b0f63bce3c3e27d2604b" , 16);
		Xg= new Clipperz.Crypto.BigInt("6b17d1f2e12c4247f8bce6e563a440f277037d812deb33a0f4a13945d898c296" , 16);
		Yg= new Clipperz.Crypto.BigInt("4fe342e2fe1a7f9b8ee7eb4a7c0f9e162bce33576b315ececbb6406837bf51f5" , 16);
		G = new Clipperz.Crypto.ECC.PrimeField.Point({x:Xg , y:Yg , z: new Clipperz.Crypto.BigInt("1") });
		n = new Clipperz.Crypto.BigInt("ffffffff00000000ffffffffffffffffbce6faada7179e84f3b9cac2fc632551" , 16);
		h = new Clipperz.Crypto.BigInt("1" , 16);
		curve = new Clipperz.Crypto.ECC.PrimeField.Curve({p:q, a:a, b:b, G:G, n:n, h:h}); //setup curve
		
		point = curve.G();
		is (point.isInfty(), false, "test isInfty false case");
		point2 = curve.negate(curve.G());
		point = curve.add(point, point2);
		is (point.isInfty(), true, "test isInfty true case");

		return;
				}], {trace:false})
		
},	
    //-------------------------------------------------------------------------
    'syntaxFix': MochiKit.Base.noop
};



//#############################################################################

SimpleTest.runDeferredTests("Clipperz.Crypto.ECC.PrimeField", tests, {trace:false});
