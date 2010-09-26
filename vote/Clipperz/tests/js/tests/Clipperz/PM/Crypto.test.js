Clipperz.Crypto.PRNG.defaultRandomGenerator().fastEntropyAccumulationForTestingPurpose();

var tests = {

    //-------------------------------------------------------------------------

	'main_test': function (someTestArgs) {
		var	key;

		var plainData;
		var encryptedData;
		var decryptedData;
	
		var result;
		var expectedResult;
	
		var plainText;
		var longPlainText;

		key = 'trustno1';

		plainText = "Lorem ipsum dolor sit amet";
		longPlainText = "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec nunc sapien, condimentum vitae, varius vel, pharetra in, augue. Mauris quam magna, pretium sit amet, accumsan id, volutpat lobortis, nibh. Fusce sagittis. Aenean justo. Curabitur euismod pede. Morbi at ante. Proin nisl leo, ultrices sed, facilisis et, nonummy sit amet, lorem. Praesent mauris tellus, pulvinar sed, nonummy vitae, rhoncus non, nunc. Proin placerat malesuada nisl. Nunc id enim. Maecenas commodo enim ac nibh. Sed condimentum, urna sit amet euismod gravida, mi urna varius odio, luctus pretium lectus justo nec felis. Ut in augue et est malesuada rhoncus. Sed vel orci. Mauris suscipit.  Praesent cursus velit non turpis. Donec tristique dolor ac est. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nulla est sapien, vulputate eget, bibendum id, pharetra nec, mauris. Aliquam faucibus tincidunt dui. Proin iaculis. Maecenas sagittis. Integer et augue. Donec vitae urna in orci aliquet commodo. Vestibulum lorem sem, suscipit ac, placerat nec, mollis in, felis. Donec laoreet odio a mauris. Integer rutrum, sapien id varius molestie, mauris odio egestas orci, non bibendum sem felis in metus.  Phasellus consectetuer lectus adipiscing mauris. Ut magna tellus, euismod ac, suscipit tincidunt, ullamcorper adipiscing, massa. Etiam orci. Phasellus a urna. Cras neque quam, laoreet at, tempus eget, euismod nec, nibh. Etiam hendrerit. Aenean vel lorem. Ut ligula lacus, congue eu, lobortis sit amet, venenatis in, magna. Nullam cursus felis quis est. Sed sem est, condimentum eu, vestibulum a, mattis vel, diam. Curabitur tincidunt pede quis pede. Sed neque diam, convallis vel, luctus at, porta id, nisl. Suspendisse potenti. Sed volutpat lobortis orci. Praesent mi. In interdum. Suspendisse suscipit ipsum eget dolor. Curabitur et tellus sed velit hendrerit varius. Cras sit amet est.  Donec arcu nulla, vehicula et, pretium in, placerat id, felis. Integer mollis auctor lectus. Integer ultrices elementum sapien. Nam et erat. Nam pulvinar porta tortor. Nam at risus. Quisque nulla. Integer vestibulum, lacus id bibendum laoreet, ligula mi pharetra lorem, sit amet pharetra felis mauris quis justo. Aliquam ultricies. Duis a pede eget lorem dapibus rhoncus. Aenean eu elit non libero consectetuer viverra. Maecenas velit mi, eleifend vel, malesuada vel, condimentum quis, odio. Mauris tempus augue sed turpis. Pellentesque condimentum, lacus vitae pellentesque ultricies, risus tellus posuere nisi, et dictum turpis pede nec elit. Sed eu lectus eu justo sagittis euismod. Vestibulum lobortis, urna id mollis rhoncus, orci quam euismod ligula, at malesuada lacus magna vitae massa. Phasellus mattis fermentum velit.  Nulla vulputate consequat enim. Maecenas quis neque. Curabitur sagittis facilisis neque. In elementum, eros non porttitor rhoncus, libero turpis sodales odio, vitae porta tellus purus et ante. Nullam molestie sollicitudin metus. Donec a elit. Morbi ut lacus. Donec at arcu. Quisque velit diam, interdum a, lacinia at, varius et, odio. Cras neque magna, ornare id, sollicitudin id, consequat a, est. Phasellus vestibulum est at leo. Nam facilisis, nulla dapibus condimentum pellentesque, est magna viverra ligula, at sollicitudin urna augue ut sapien. Fusce justo.";

		//
		//	hashing
		//
		plainData = new Clipperz.ByteArray(plainText);
	
		result = Clipperz.PM.Crypto.encryptingFunctions.versions['0.1'].hash(plainData);
		is(result.length(), (256/8), "encryptingFunctions.versions[0.1].hash generate a 256 bit signature with a short text");

		result = Clipperz.PM.Crypto.encryptingFunctions.versions['0.2'].hash(plainData);
		is(result.length(), (256/8), "encryptingFunctions.versions[0.2].hash generate a 256 bit signature with a short text");


		plainData = new Clipperz.ByteArray(longPlainText);
	
		result = Clipperz.PM.Crypto.encryptingFunctions.versions['0.1'].hash(plainData);
		is(result.length(), (256/8), "encryptingFunctions.versions[0.1].hash generate a 256 bit signature with a long text");

		result = Clipperz.PM.Crypto.encryptingFunctions.versions['0.2'].hash(plainData);
		is(result.length(), (256/8), "encryptingFunctions.versions[0.2].hash generate a 256 bit signature with a long text");


		//
		//	encrypting / decripting
		//
		plainData = plainText;

		encryptedData = Clipperz.PM.Crypto.encryptingFunctions.versions['0.1'].encrypt(key, plainData);
		decryptedData = Clipperz.PM.Crypto.encryptingFunctions.versions['0.1'].decrypt(key, encryptedData);
		is(plainData.toString(), decryptedData.toString(), "encryptingFunctions.versions[0.1] of encrypt/decrypt functions work with a short text");

		encryptedData = Clipperz.PM.Crypto.encryptingFunctions.versions['0.2'].encrypt(key, plainData);
		decryptedData = Clipperz.PM.Crypto.encryptingFunctions.versions['0.2'].decrypt(key, encryptedData);
		is(plainData.toString(), decryptedData.toString(), "encryptingFunctions.versions[0.2] of encrypt/decrypt functions work with a short text");

//console.time("encrypt-256-short");
		encryptedData = Clipperz.PM.Crypto.encryptingFunctions.versions['0.3'].encrypt(key, plainData);
//console.timeEnd("encrypt-256-short");
//console.time("decrypt-256-short");
		decryptedData = Clipperz.PM.Crypto.encryptingFunctions.versions['0.3'].decrypt(key, encryptedData);
//console.timeEnd("decrypt-256-short");
		is(plainData.toString(), decryptedData.toString(), "encryptingFunctions.versions[0.3] of encrypt/decrypt functions work with a short text");


		plainData = longPlainText + longPlainText + longPlainText + longPlainText + longPlainText + longPlainText + longPlainText + longPlainText;

/*
		encryptedData = Clipperz.PM.Crypto.encryptingFunctions.versions['0.1'].encrypt(key, plainData);
		decryptedData = Clipperz.PM.Crypto.encryptingFunctions.versions['0.1'].decrypt(key, encryptedData);
		is(plainData, decryptedData, "encryptingFunctions.versions[0.1] of encrypt/decrypt functions work with a long text");

		encryptedData = Clipperz.PM.Crypto.encryptingFunctions.versions['0.2'].encrypt(key, plainData);
		decryptedData = Clipperz.PM.Crypto.encryptingFunctions.versions['0.2'].decrypt(key, encryptedData);
		is(plainData, decryptedData, "encryptingFunctions.versions[0.2] of encrypt/decrypt functions work with a long text");

		encryptedData = Clipperz.PM.Crypto.encryptingFunctions.versions['0.3'].encrypt(key, plainData);
		decryptedData = Clipperz.PM.Crypto.encryptingFunctions.versions['0.3'].decrypt(key, encryptedData);
		is(plainData, decryptedData, "encryptingFunctions.versions[0.3] of encrypt/decrypt functions work with a long text");
*/
	},

    //-------------------------------------------------------------------------

	'second_test': function (someTestArgs) {
		var key;
		var	plainText;
		var	plainData;
		var deferredResult;
		var startTime;
//		var nonce;

		key = 'trustno1';

		plainText = "Lorem ipsum dolor sit amet";
		plainData = new Clipperz.ByteArray(plainText);
		
//		nonce = new Clipperz.ByteArray("0x00000000000000000000000000000000");
		plainData = plainData + plainData + plainData + plainData + plainData + plainData + plainData + plainData;
MochiKit.Logging.logDebug("plainData length: " + plainData.length);
		startTime = new Date();
		
		deferredResult = new MochiKit.Async.Deferred();
		
		deferredResult.addCallback(function() {
			return Clipperz.PM.Crypto.deferredEncrypt({key:key, value:plainData, version:'0.3'});
//			return Clipperz.PM.Crypto.encrypt(key, plainData, '0.3' / *Clipperz.PM.Crypto.encryptingFunctions.currentVersion* /);
		});
		deferredResult.addCallback(function(aResult) {
			MochiKit.Logging.logDebug("encrypting: " + (new Date() - startTime));
			startTime = new Date();
			return aResult;
		});
		deferredResult.addCallback(MochiKit.Async.wait, 1);
		deferredResult.addCallback(function(aResult) {
			MochiKit.Logging.logDebug("pause: " + (new Date() - startTime));
			startTime = new Date();
			return aResult;
		});
		deferredResult.addCallback(function(anEncryptedValue) {
			return Clipperz.PM.Crypto.deferredDecrypt({key:key, value:anEncryptedValue, version:'0.3'});
		});
		deferredResult.addCallback(function(aResult) {
			MochiKit.Logging.logDebug("decrypting: " + (new Date() - startTime));
			startTime = new Date();
			return aResult;
		});
		deferredResult.addCallback(MochiKit.Async.wait, 1);
		deferredResult.addBoth(function(aDecryptedValue) {
			is(plainData, aDecryptedValue, "deferredEncript/deferredDecript functions work with a long text");
		});

/*
		deferredResult.addCallback(function(aResult) {
			var i,c;
			var currentChar;
			var unicode;
			
			unicode = 150;
			
			c = 100000;
			startTime = new Date();
			for (i=0; i<c; i++) {
				currentChar = String.fromCharCode(unicode+1);
			}
			MochiKit.Logging.logDebug("String.fromCharCode: " + (new Date() - startTime));
		});

		deferredResult.addCallback(function(aResult) {
			var i,c;
			var currentByte;
			var unicode;
			
			currentByte = 150;
			
			c = 100000;
			startTime = new Date();
			for (i=0; i<c; i++) {
				unicode = (currentByte & 0x0f) << (6+6);
			}
			MochiKit.Logging.logDebug("(currentByte & 0x0f) << (6+6): " + (new Date() - startTime));
		});

		deferredResult.addCallback(function(aResult) {
			var i,c;
			var currentByte;
			var byteArray;
			
			currentByte = 150;
			
			byteArray = new Clipperz.ByteArray(plainData);
			c = byteArray.length();
			startTime = new Date();
			for (i=0; i<c; i++) {
				currentByte = byteArray.byteAtIndex(i);
			}
			MochiKit.Logging.logDebug("byteAtIndex: " + (new Date() - startTime));
		});
*/

		deferredResult.addBoth(function() {
			SimpleTest.ok(true, "COMPLETED all tests");
		})

		deferredResult.callback();
		
		return deferredResult;
	},

    //-------------------------------------------------------------------------
    'syntaxFix': MochiKit.Base.noop
};



//#############################################################################

SimpleTest.runDeferredTests("Clipperz.PM.Crypto", tests, {trace:false});

