hashString = function(aValue) {
	return Clipperz.Crypto.SHA.sha256(new Clipperz.ByteArray(aValue)).toHexString().substring(2)	
}


var tests = {

    //-------------------------------------------------------------------------

	'basic_tests': function (someTestArgs) {
		var	username;
		var	passphrase;
	
		var C;
		var P;
		var	salt;
		var	x;
		var v;
	
		username = "giulio.cesare";
		passphrase = "trustno1";
	
		C = hashString(username);
		is (C, "bde3c7b5fdcd9d6ce72782ca1ae912fc4397d668fcb3a73a04e5d47852670c4a", "C");
	
		P  = hashString(passphrase + username);
		is (P, "d79f5c5a04e91e1c85fb64cb6ee9481cb52c181047f69da02cd6c3ce6d058a76", "P");

		salt = "cf1fa93393ade60318b8276f1f39420098419445005a7dc9117975fe1f8d9988";

		x = hashString(salt + P);
		is(x, "21fe88a158e420aade86e00b5eb12a4c19bf15482fa34c542c90b1afdbd5b5fd", "x");
	
		v = Clipperz.Crypto.SRP.g().powerModule(new Clipperz.Crypto.BigInt(x, 16), Clipperz.Crypto.SRP.n());
		is(v.asString(10), "33816467430011076413789931449607305355248467973000153409872503376381719918118", "v");
		is(v.asString(16), "4ac37139dbf32ebabd2c43f91dd085066d3c457d059efd5902d32ed247fcb626", "v (base 16)");
	},

    //-------------------------------------------------------------------------

	'someCredential_tests': function (someTestArgs) {
		var	username;
		var	passphrase;
	
		var C;
		var P;
		var	salt;
		var	x;
		var v;

		username = "giulio.cesare.debug";
		passphrase = "trustno1";
	
		C = hashString(username);
		is (C, "fa1af609123b97a10d676158ed538d4657a89ac33a102b22bd9a66712039e208", "C");

		P  = hashString(passphrase + username);
		is (P, "e1bfba03dd626b12f29458a6ad63fb2c01b4765548504e1e2f6b1503c82e4253", "P");

		salt = "cf1fa93393ade60318b8276f1f39420098419445005a7dc9117975fe1f8d9988";

		x = hashString(salt + P);
		is(x, "93d4af3cdcd2447a745d309826dff3161feed4b15f32db8e909ff032a2bc8fb8", "x");
	
		v = Clipperz.Crypto.SRP.g().powerModule(new Clipperz.Crypto.BigInt(x, 16), Clipperz.Crypto.SRP.n());
		is(v.asString(10), "115049747015252903452664067168789229427785288458366249918596663144588656606556", "v");
	},

    //-------------------------------------------------------------------------

	'srpConnection_tests': function (someTestArgs) {
		var srpConnection;
		var C, P, salt;
	
		C = 	"da8602c2f847306f4eb9acdaad925277d1fad1408f173f128a078aea15e60b1e";
		P =		"77643559beca49dd21c1c31db10bb0a9009662cb504413dc3fa3b7303c7e02ba";
		salt =	"000108cbbacda1f03ea9360301045434ec7d82ba150936df08a229cbb4832ce1";
	
		srpConnection = new Clipperz.Crypto.SRP.Connection({C:C, P:P, hash:Clipperz.Crypto.SHA.sha_d256});
		srpConnection._a = new Clipperz.Crypto.BigInt("37532428169486597638072888476611365392249575518156687476805936694442691012367", 10);
		srpConnection.set_s(new Clipperz.Crypto.BigInt(salt, 16));
		is (srpConnection.s().asString(16, 64), salt, "salt read/write is coherent");
		srpConnection.set_B(new Clipperz.Crypto.BigInt("123541032067854367017620977654446651448957899464139861291542193929199957895435", 10));

		is(	srpConnection.serverSideCredentialsWithSalt(salt).v,
			"c73169c8236d37bf9ef11a2349e3064b7dc6e883a58d64443ea9235677520030",
			"server side credentials - v"
		)
		is(srpConnection.S(), "84134227508133659832466942692590826994401065200828529664948840490489960952050", "Server side 'S'");
	//MochiKit.Logging.logDebug("=== serverSideCredentials: " + MochiKit.Base.serializeJSON(srpConnection.serverSideCredentialsWithSalt(salt)));

		srpConnection = new Clipperz.Crypto.SRP.Connection({C:C, P:P, hash:Clipperz.Crypto.SHA.sha_d256});
		try {
			srpConnection.set_B(new Clipperz.Crypto.BigInt("0", 10));
			ok(false, "Setting B to 0 should raise an exception");
		} catch(e) {
			ok(true, "Setting B to 0 should raise an exception");
		}
	},

    //-------------------------------------------------------------------------
    'syntaxFix': MochiKit.Base.noop
}

//=============================================================================

SimpleTest.runDeferredTests("Clipperz.Crypto.SRP", tests, {trace:false});
