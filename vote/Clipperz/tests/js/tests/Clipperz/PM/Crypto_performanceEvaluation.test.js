Clipperz.Crypto.PRNG.defaultRandomGenerator().fastEntropyAccumulationForTestingPurpose();

var tests = {

    //-------------------------------------------------------------------------

	'main_test': function (someTestArgs) {
		var key;
		var shortText;
		var longText;
		var veryLongText;
		var byteArray;
		var	plainText;
		var encryptedText;
		var decryptedText;
		var startTime, endTime;
		var startTime1, endTime1;
		var ClipperzCryptoVersion;
		var i,c;
	
		ClipperzCryptoVersion = '0.3';
		Clipperz.Crypto.PRNG.defaultRandomGenerator().fastEntropyAccumulationForTestingPurpose();
		key = 'trustno1';

		shortText = "Lorem ipsum dolor sit amet";
		longText = "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec nunc sapien, condimentum vitae, varius vel, pharetra in, augue. Mauris quam magna, pretium sit amet, accumsan id, volutpat lobortis, nibh. Fusce sagittis. Aenean justo. Curabitur euismod pede. Morbi at ante. Proin nisl leo, ultrices sed, facilisis et, nonummy sit amet, lorem. Praesent mauris tellus, pulvinar sed, nonummy vitae, rhoncus non, nunc. Proin placerat malesuada nisl. Nunc id enim. Maecenas commodo enim ac nibh. Sed condimentum, urna sit amet euismod gravida, mi urna varius odio, luctus pretium lectus justo nec felis. Ut in augue et est malesuada rhoncus. Sed vel orci. Mauris suscipit.  Praesent cursus velit non turpis. Donec tristique dolor ac est. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nulla est sapien, vulputate eget, bibendum id, pharetra nec, mauris. Aliquam faucibus tincidunt dui. Proin iaculis. Maecenas sagittis. Integer et augue. Donec vitae urna in orci aliquet commodo. Vestibulum lorem sem, suscipit ac, placerat nec, mollis in, felis. Donec laoreet odio a mauris. Integer rutrum, sapien id varius molestie, mauris odio egestas orci, non bibendum sem felis in metus.  Phasellus consectetuer lectus adipiscing mauris. Ut magna tellus, euismod ac, suscipit tincidunt, ullamcorper adipiscing, massa. Etiam orci. Phasellus a urna. Cras neque quam, laoreet at, tempus eget, euismod nec, nibh. Etiam hendrerit. Aenean vel lorem. Ut ligula lacus, congue eu, lobortis sit amet, venenatis in, magna. Nullam cursus felis quis est. Sed sem est, condimentum eu, vestibulum a, mattis vel, diam. Curabitur tincidunt pede quis pede. Sed neque diam, convallis vel, luctus at, porta id, nisl. Suspendisse potenti. Sed volutpat lobortis orci. Praesent mi. In interdum. Suspendisse suscipit ipsum eget dolor. Curabitur et tellus sed velit hendrerit varius. Cras sit amet est.  Donec arcu nulla, vehicula et, pretium in, placerat id, felis. Integer mollis auctor lectus. Integer ultrices elementum sapien. Nam et erat. Nam pulvinar porta tortor. Nam at risus. Quisque nulla. Integer vestibulum, lacus id bibendum laoreet, ligula mi pharetra lorem, sit amet pharetra felis mauris quis justo. Aliquam ultricies. Duis a pede eget lorem dapibus rhoncus. Aenean eu elit non libero consectetuer viverra. Maecenas velit mi, eleifend vel, malesuada vel, condimentum quis, odio. Mauris tempus augue sed turpis. Pellentesque condimentum, lacus vitae pellentesque ultricies, risus tellus posuere nisi, et dictum turpis pede nec elit. Sed eu lectus eu justo sagittis euismod. Vestibulum lobortis, urna id mollis rhoncus, orci quam euismod ligula, at malesuada lacus magna vitae massa. Phasellus mattis fermentum velit.  Nulla vulputate consequat enim. Maecenas quis neque. Curabitur sagittis facilisis neque. In elementum, eros non porttitor rhoncus, libero turpis sodales odio, vitae porta tellus purus et ante. Nullam molestie sollicitudin metus. Donec a elit. Morbi ut lacus. Donec at arcu. Quisque velit diam, interdum a, lacinia at, varius et, odio. Cras neque magna, ornare id, sollicitudin id, consequat a, est. Phasellus vestibulum est at leo. Nam facilisis, nulla dapibus condimentum pellentesque, est magna viverra ligula, at sollicitudin urna augue ut sapien. Fusce justo.";
		veryLongText = "";
		c = 100;
		for (i=0; i<100; i++) {
			veryLongText += longText;
		}
		nonce = new Clipperz.ByteArray(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);


		//--------------------------------------------------------------
		//
		//		longText	encryption performance
		//
		//--------------------------------------------------------------

		plainText = longText;

startTime = new Date();
		encryptedText = Clipperz.PM.Crypto.encryptingFunctions.versions[ClipperzCryptoVersion].encrypt(key, plainText, nonce);
endTime = new Date();
//console.log("[1] encryption time: " + (endTime - startTime));
		is((endTime - startTime < 350), true, "[1] Need to improve some more (" + (endTime - startTime) + ")");
		is(encryptedText, "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", "encrypted text");
		decryptedText = Clipperz.PM.Crypto.encryptingFunctions.versions[ClipperzCryptoVersion].decrypt(key, encryptedText);
		is(decryptedText, plainText, "encrypt <-> decrypt works (specifying the nonce)");

startTime = new Date();
		encryptedText = Clipperz.PM.Crypto.encryptingFunctions.versions[ClipperzCryptoVersion].encrypt(key, plainText);
endTime = new Date();
//console.log("[2] encryption time: " + (endTime - startTime));
		is((endTime - startTime < 350), true, "[2] Need to improve some more (" + (endTime - startTime) + ")");
		decryptedText = Clipperz.PM.Crypto.encryptingFunctions.versions[ClipperzCryptoVersion].decrypt(key, encryptedText);
		is(decryptedText, plainText, "encrypt <-> decrypt works");

		//--------------------------------------------------------------
		//
		//		veryLongText	encryption performance
		//
		//--------------------------------------------------------------
/*
		plainText = veryLongText;

startTime = new Date();
		encryptedText = Clipperz.PM.Crypto.encryptingFunctions.versions[ClipperzCryptoVersion].encrypt(key, plainText);
endTime = new Date();
//console.log("[3] encryption time: " + (endTime - startTime));
		is((endTime - startTime < 35000), true, "[3] Need to improve some more (" + (endTime - startTime) + ")");
		decryptedText = Clipperz.PM.Crypto.encryptingFunctions.versions[ClipperzCryptoVersion].decrypt(key, encryptedText);
		is(decryptedText, plainText, "encrypt <-> decrypt works");
*/
	},

    //-------------------------------------------------------------------------
    'syntaxFix': MochiKit.Base.noop
};



//#############################################################################

SimpleTest.runDeferredTests("Clipperz.PM.Crypto_performanceEvaluation", tests, {trace:false});





/*


/



*/