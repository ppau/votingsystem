Clipperz.Crypto.PRNG.defaultRandomGenerator().fastEntropyAccumulationForTestingPurpose();

var tests = {

    //-------------------------------------------------------------------------

	'main_test': function (someTestArgs) {
		var password;
		var	plainText;
		var	encryptedText;
		var decryptedText;
/*	
		password = "trustno1";
		plainText = "The quick brown fox jumps over the lazy dog";
//console.profile("encrypt");
		encryptedText = Clipperz.PM.Crypto.encryptingFunctions.versions['0.2'].encrypt(password, plainText);
//console.profileEnd();
//console.profile("decrypt");
		decryptedText = Clipperz.PM.Crypto.encryptingFunctions.versions['0.2'].decrypt(password, encryptedText);
//console.profileEnd();
		is(decryptedText, plainText, "simple string encrypted/decrypted");
*/

		password = "L7bd9fQMhborMbYcHtlr";
		plainText = {"records":{"f1aac97154a0e52c5e33508afa82df5a9d6dcde24883a240b8c072a3238da0b6":{"label":"imap4all [no]", "key":"f54b5033d1152456acb67974c45ee6771f8411e300c9533359dfacacf60dcbbd", "notes":""}, "c9dae2b7a60b300008306f5ec731b60050250df8f8ff34f7d9cce92762121b99":{"label":"Il manifesto", "key":"6e0ef134503110e72f444e7d102a4b1cc6ae28f2e0b1287c2b1875ff052fc16c", "notes":""}, "70d536c89a86b1aa9e077d6f9c717306a5d6c8d5549789e42dfb4f981484f116":{"label":"OmniGroup applications", "key":"7b432b7dae39ff5951db31947fa721dc012af0da4055760c6df3b02e776ef22c", "notes":"url: http://www.omnigroup.com\n\nLicence owner: Giulio Cesare Solaroli\n\nOmniWeb: EQGP-EMKH-LKWP-MUHQ-OUHL-LDF\nOmniGraffle:\nOmniOutliner:\nOmniDiskSweeper:"}, "111855cedd650dfcbbce597d764583c6b040df4b71f5fa0161fb8d10514ee48f":{"label":"R@cine", "key":"57295772c84669b0a224f435e9b75c797ae5999a2d9473ab50f9384ae54f49d6", "notes":""}, "378a790452de46e1079a99eba2e15094a096b418cccd0262b8b20244eb94d2df":{"label":"NewsGator", "key":"6ee16f6932ee02000c49dbcc685c84074b40d7956e5f4bc1100030a0f9a41f1a", "notes":""}, "30c4f575799fc6908765fc8b54f4a9a483cb32e12aa89feae545976870a9102e":{"label":"GMail - giulio.cesare", "key":"0395efd852b00700bcf78b65350ec15932430df71201d2c53a11b0269b557d1a", "notes":""}, "b2836a864ff081b6f053c3f5f13dfb29c81af33d25a316cdd82af747ea71bea0":{"label":"GMail - clipperz.com", "key":"90d6ae70d89c8211404b6f9d6c70b6b9c113fff74f474a67b34acd9c1c048d1f", "notes":""}, "6ad2cda35f97743cfddf2133cdf3142fe6419e683484531f1ef1e67431f44284":{"label":"Aruba - hosting", "key":"5c7472d24d57391c63ea99ed1fc9de179d225abd335fa65702018cfea6083d47", "notes":""}, "741ce1d21839c69db754309b04ce02fbb4104f6cb87572c056ae4af918420700":{"label":"Aruba - sql hosting", "key":"f6bd942ac3b0b7065771e5197c7499b345a10f7a4843d00c3ba3809d0ea059dc", "notes":""}, "1cef440eecea59f47554aa04b94e18c1d9fc761246b911f89a7da72d544cac48":{"label":"Amazon", "key":"1ae022b4d14b642f113703b2a98931bd892dec2da785ab5ff6fc1d0aac7537f1", "notes":""}, "d34c13773b5d8154355c2605024a1dfaf66279ba0fbe3ac19fc1cbc642278fe4":{"label":"YouTube [no]", "key":"4c6593d4f6448137939b364b84c81501fadb60f7871fe5fa63c93e97bb5c4648", "notes":""}, "5054f0b94cd97610a1bc0ed8671b6fb5b25bf7a5582677059fcaaea75fac27bc":{"label":"DynDns - gcsolaroli", "key":"f8ed9e7a3630deed046eda37ebc63ecb4d63668a2f97224d7628fdc53b242467", "notes":""}, "73fb52ed51533657d6ebb020d5026fb4deb601dadce802de58f7fff4b56e1495":{"label":"DynDns - clipperz", "key":"d8bc295177383a523e67b61b166e0ca956ab4c2ee86800559a009d2716064f6d", "notes":""}, "48d4c0546c032be26ecce4da41e020129afa7fc34cfe164ea72e1c9953d2e6bb":{"label":"Bol.it", "key":"cada5dadeebd8d12190954d21f1a944c8799d034f028be195b448935fcf970c7", "notes":""}, "d62d420db34720ccc054df06b88725ea79020ffa9389ca15e70137fb4dfd0883":{"label":"Freenigma - clipperz.com", "key":"f09cb3790c1110794b834702b8c487c1a42b2508fbe6450a8468477d93790b2e", "notes":""}, "ccd44ae294e7694ea53009c7198506cc0fe3121ad5d9fe2635d247e2afdab2ae":{"label":"Freenigma", "key":"4b05738f6faebc147eac5e425054a91d3cc59dd63844e82d1f0864c0ac8efec7", "notes":""}, "bd5a587bb977a2c728fcd0fa6093dd63a4e62138cf89721115fe45e0396ba5d2":{"label":"clipperz.com - blog", "key":"9cc24328bbce18e8713962428d8123e309a12f7e1d9537bc252e134501734003", "notes":""}, "c2b99939e40d100218baa3ed1cb2a25a5cf726485b0336a0989b104a94157b5f":{"label":"Apple", "key":"75f2651af400629c4e5dd8bcdc3a6c691150d23d6e1a4eb263ff810926d1228f", "notes":""}, "b5bd38d8eb5e23b1354884cc519e05580864fadf74d0a19d2c691cd0c7054d77":{"label":".mac", "key":"5934ae96d2e01282effb368d9086c2ba5d1d856ad91dd6f04f5bace26a1c0cbe", "notes":""}, "ff79a2282cf246add520a3c06e835cf6ffaaae95067d45e8e2e8f44da2501380":{"label":"3nity", "key":"33d84c4a91ab053cbf8115c689ede7e504b81199884de449acc257bea534f57f", "notes":""}, "7b2f2a59ebb34b5a49f20b99b546f08b9f4f62cbefdce9699f8ef7e74aeb0552":{"label":"ACM", "key":"b4976bb0892baba81d628513d191de100d89acd58efbb07c823a5bb4abe48a7a", "notes":""}, "b83a6bac0da3a27eb909d34cbad183e77088952f01d8d5321613b7b01635a020":{"label":"Adobe", "key":"d162bc404994a79ec97e0106c3a4edf2f83aca25def130242e11e95e74bd0aaa", "notes":""}, "befc571e9cda1a7dfb1d15200240ca5170386280ee7be6a12716904cb6d0ea44":{"label":"Adobe Photoshop Elements 3", "key":"18a62c3c2065399819707322f467ff4be030d7939acbe5182c8194599845c428", "notes":"Photoshop Elements 2:\n1057-4312-5223-2206-9509-6837"}, "0424f72608fedc969d64a6d5b4a16dd3ce860a230cd6d87d936439f4dd2aafc7":{"label":"Agatra", "key":"c35158a21b2af75d414232b742ab738d042314e00209f8fca94c8c704c891f23", "notes":""}, "e5e17c29fd598acb4f4c7d621dbdcb045d4d0cabf7d8a19e24420c440cdc3935":{"label":"AIM", "key":"8561ac421d845921978387b5e6b362750b57ed08feda8ec12b9378b69f67ceff", "notes":""}, "de890eb76a4b0cabd4ffd490adad1ff1b73238c7b5ee6dde1a2aeab2d03ebe93":{"label":"Anna Vespignani", "key":"79a970af0d2d30643dc2db4d16757395c1f22c311919036c2a22b7581982144a", "notes":""}, "0dc8d3989d0b35d672c012057d3eb7b111f16e79329e08a9ffb31ac7accbab21":{"label":"Bloglines", "key":"fe81f4df8c42fd81c830f9af408e9b074e77fd430e26d0ee285844fe3b092aec", "notes":""}, "85a40a322a59c80cb46519900269dcc7cf6947213d03dfc9371dd1930373a65b":{"label":"Bow.it", "key":"64a1a9fec99c9238dc8180a01484a1ccf5f50fcd6e9a95a52b8b49fb9ca00bdc", "notes":""}, "60308062a1848a301641a74712d220eef191a280ba0a8355992f0e61ed793811":{"label":"GMail - feedback", "key":"fad310cb2e6152c3faf78b7183c99f3044f5d31ee364068b80580c271a7784ef", "notes":""}, "257ac2da79ee1cd46dfa214d91f5ece213b6bbade28d1ee71495c81a3d7e033a":{"label":"Fineco", "key":"8f99de2635b5dad7987180bc0bff49947eb37cc75d6a5d1ee1f13ed7567465a3", "notes":""}, "78261622810232b6da5efcd52b1c9b0bd87c62517bf4df25323ca6a0b49d84ec":{"label":"mon.itor.us", "key":"d2aa7164007c5deac8bb73580a6ab0d051f747e801ecd30284eff725d0ffaba2", "notes":""}, "4b78dc0376d07e57d77b4c7318d2f222956adb6ff7360b73e60b8bb8b85f3d11":{"label":"Lamba Probe - forum", "key":"f73906817fddba4d8f816334cb2fd0cd5ae91bc29bce6a69fdd5cf98fc96911f", "notes":""}, "78ca2c85908275d788c2f7dd0306ca5e03b83637bb3812272b697e12e9dbf941":{"label":"MeasureMap", "key":"2385ce9536ebb7863b6a4c8b1f5c428587e4d6420a4bbcd31b935cb00bbd768e", "notes":""}, "4c2c7f0d733b647e6f388c9a4590a2a864cd2de259b66aba9b3cf92bdc3cf9bc":{"label":"NGI - Squillo", "key":"96f20c212be02fb38c8b2dfc83d8e864dd84dcb95297a7fecf9280e1e4dcffe3", "notes":""}, "eaeadf6d36f8ee6916c33b9e5bf480b663dc90c0c7f370ff5a1f2fd998cf1143":{"label":"NGI - F5", "key":"00347769244b208647c24e6a64f8fa4213e97eb2135ecfcb277b341c28616a59", "notes":""}, "19654392222206d60547073209672dde1c743ea371ddc20a2bd8254e561a4ec0":{"label":"CVSdude", "key":"ed0ab5080a29eb1b20927142d21ab8f67b61c2c7b19623bb610af030dfd42c02", "notes":""}, "6b10514d50e745f1dab5a40e8629ecf1a8c78a5d6e3895f3637fb67d2d3f9993":{"label":"Yahoo", "key":"6380a7655cd790d1f1e6f482e92ae04201568ff0cab887e65102e9396df1b86e", "notes":"note"}}, "directLogins":{"eac496e0b1ec75ea403f821fedc7f51f98dac639713ebe577f969f607a8943f5":{"record":"111855cedd650dfcbbce597d764583c6b040df4b71f5fa0161fb8d10514ee48f", "label":"R@cine - WebMail", "favicon":"http://www.racine.ra.it/favicon.ico"}, "ef564a022630d4395a9ecac854f3b127b3518cec362323ccc605079c0749c152":{"record":"1cef440eecea59f47554aa04b94e18c1d9fc761246b911f89a7da72d544cac48", "label":"Amazon sign in", "favicon":"http://www.amazon.com/favicon.ico"}, "4f14b88a4055ff23a00d625382650888ce9284fe869304775e43e3e33ee5bbb6":{"record":"6ad2cda35f97743cfddf2133cdf3142fe6419e683484531f1ef1e67431f44284", "label":"Aruba - hosting", "favicon":"http://hosting.aruba.it/favicon.ico"}, "e94c0d12d1db0badc31a8bbbbc4b08d2065a39f458462bbff9756f7b5eb7fedf":{"record":"741ce1d21839c69db754309b04ce02fbb4104f6cb87572c056ae4af918420700", "label":"Aruba - sql hosting", "favicon":"http://mysql.aruba.it/favicon.ico"}, "7299249153ef93a44e2f248ca3a73badde56e71d70919bb5637093c2abbe2c9a":{"record":"bd5a587bb977a2c728fcd0fa6093dd63a4e62138cf89721115fe45e0396ba5d2", "label":"clipperz.com - blog", "favicon":"http://www.clipperz.com/favicon.ico"}, "66876dbae68778d4c104bc12f01adcb21d47d9eace8db30ef95f74f461afcb59":{"record":"73fb52ed51533657d6ebb020d5026fb4deb601dadce802de58f7fff4b56e1495", "label":"DynDns - clipperz", "favicon":"http://www.dyndns.com/favicon.ico"}, "a60c65030a1797abde3e2089c3e5de9648f66bf71cebf0b58c26e729ad8d6a45":{"record":"5054f0b94cd97610a1bc0ed8671b6fb5b25bf7a5582677059fcaaea75fac27bc", "label":"DynDns - gcsolaroli", "favicon":"http://www.dyndns.com/favicon.ico"}, "08d6c5dff9fed4a2f237c32dd0a93ac46b2c45370d07f56fa76064be3b8fecbf":{"record":"30c4f575799fc6908765fc8b54f4a9a483cb32e12aa89feae545976870a9102e", "label":"GMail - giulio.cesare", "favicon":"http://www.google.com/favicon.ico"}, "9e75e12f0f52f248cc7ae517869dd7b02303037d32d9fb4fa0ab0e013923c304":{"record":"c9dae2b7a60b300008306f5ec731b60050250df8f8ff34f7d9cce92762121b99", "label":"Il manifesto", "favicon":"http://abbonati.ilmanifesto.it/favicon.ico"}, "935bf9553fbcb85b8bd5b98c6901d7cccb2566b395f192cbea71e7822979aaf2":{"record":"f1aac97154a0e52c5e33508afa82df5a9d6dcde24883a240b8c072a3238da0b6", "label":"Imap4All.com - account", "favicon":"http://www.imap4all.com/favicon.ico"}, "9504205ec29b89e6ccd0f3afc7a447d8891da0c71a0222f1860f98a8f8bc6677":{"record":"f1aac97154a0e52c5e33508afa82df5a9d6dcde24883a240b8c072a3238da0b6", "label":"Imap4all.com - WebMail", "favicon":"http://webmail.imap4all.com/favicon.ico"}, "3d8dd32d2290ca98789c914580ac2436ece97234217a07d752726d2ac48a4ebf":{"record":"d34c13773b5d8154355c2605024a1dfaf66279ba0fbe3ac19fc1cbc642278fe4", "label":"YouTube [no]", "favicon":"http://www.youtube.com/favicon.ico"}, "7c4b6b5a16984c43ed6d99b04ddfa7e00b624de729ec8aaa3d0f539fb67587e2":{"record":"c2b99939e40d100218baa3ed1cb2a25a5cf726485b0336a0989b104a94157b5f", "label":"Apple Store - Italia", "favicon":"http://store.apple.com/favicon.ico"}, "0b9a98262b50f0ebae5af077467bc627619738873690238fd61093ce9922c9f9":{"record":"ff79a2282cf246add520a3c06e835cf6ffaaae95067d45e8e2e8f44da2501380", "label":"3nity", "favicon":"http://www.3nity.de/favicon.ico"}, "aadeb3388629cfc3b15954f26cf284f52e084191dcdf75752dc4c53d2006c5be":{"record":"7b2f2a59ebb34b5a49f20b99b546f08b9f4f62cbefdce9699f8ef7e74aeb0552", "label":"ACM Web Account", "favicon":"http://portal.acm.org/favicon.ico"}, "3d21c71f2e284ec76f1ae0bb990b683979918f758635bb7d008150f4d7b1447d":{"record":"b83a6bac0da3a27eb909d34cbad183e77088952f01d8d5321613b7b01635a020", "label":"Adobe - Sign In", "favicon":"http://www.adobe.com/favicon.ico"}, "e61a331c998804d46044d4c2acaf96c2fce806f6549e1e16c7d2334872a70953":{"record":"0424f72608fedc969d64a6d5b4a16dd3ce860a230cd6d87d936439f4dd2aafc7", "label":"Agatra [no]", "favicon":"http://www.agatra.com/favicon.ico"}, "9bcd99564fda778061246439fa098dcc79de75b16c542f61e6de7d36dbaf97dc":{"record":"e5e17c29fd598acb4f4c7d621dbdcb045d4d0cabf7d8a19e24420c440cdc3935", "label":"AIM [no]", "favicon":"http://my.screenname.aol.com/favicon.ico"}, "c7093f4663c6e0eba941c557cb86da83fc68cbea36c922e168d0867e6cabe9fe":{"record":"0dc8d3989d0b35d672c012057d3eb7b111f16e79329e08a9ffb31ac7accbab21", "label":"Bloglines", "favicon":"http://www.bloglines.com/favicon.ico"}, "915f2e9460f6e54c6137f3876f9179fc8d2162c59f26e12899c2db6b0e70a68f":{"record":"85a40a322a59c80cb46519900269dcc7cf6947213d03dfc9371dd1930373a65b", "label":"Bow.it", "favicon":"http://www.bow.it/favicon.ico"}, "779701af1beb2a91735ba1a2e471b948f0d985bb0df256f5e089291ce3405bd2":{"record":"b2836a864ff081b6f053c3f5f13dfb29c81af33d25a316cdd82af747ea71bea0", "label":"GMail - Clipperz", "favicon":"http://www.google.com/favicon.ico"}, "1c300539a98c874d52134b6b5a591172acc00c0947692f3da284447f7d511eaf":{"record":"60308062a1848a301641a74712d220eef191a280ba0a8355992f0e61ed793811", "label":"GMail - feedback", "favicon":"http://www.google.com/favicon.ico"}, "f9dccdf7a98735fd7a6b5d04c09177005c0de14f8f92b04007f06a281ecdf31e":{"record":"30c4f575799fc6908765fc8b54f4a9a483cb32e12aa89feae545976870a9102e", "label":"Blogger", "favicon":"http://www.google.com/favicon.ico"}, "48497a89f3bfd567758977e1c32b4497d28c843880667ee52fa4cfcb53c5f9e4":{"record":"378a790452de46e1079a99eba2e15094a096b418cccd0262b8b20244eb94d2df", "label":"NewsGator", "favicon":"http://www.newsgator.com/favicon.ico"}, "134cd28f150df4f2a089f4807bb7a35fb7ece22ec41244f72e63f8b43637a4cd":{"record":"4b78dc0376d07e57d77b4c7318d2f222956adb6ff7360b73e60b8bb8b85f3d11", "label":"Lambda Probe - forum", "favicon":"http://www.lambdaprobe.org/favicon.ico"}, "2ab6106a81513b70f1ba0d7c5c3ef54fa6f4bcadf01d2eeaa2b31b9299551398":{"record":"78ca2c85908275d788c2f7dd0306ca5e03b83637bb3812272b697e12e9dbf941", "label":"Measure Map", "favicon":"http://alpha.measuremap.com/favicon.ico"}, "53ccdc41b43da9b018847f9faa8effb35e7a6c6e78a54e9ee7816fc02f0ea63b":{"record":"4c2c7f0d733b647e6f388c9a4590a2a864cd2de259b66aba9b3cf92bdc3cf9bc", "label":"NGI - Squillo", "favicon":"http://www.ngi.it/favicon.ico"}, "ca520e7081fba1df3ef79c3d00266cffc8e4567def29d67ae812b7ed6283fb12":{"record":"eaeadf6d36f8ee6916c33b9e5bf480b663dc90c0c7f370ff5a1f2fd998cf1143", "label":"NGI - F5", "favicon":"http://www.ngi.it/favicon.ico"}, "80e63e135d7abd2b2990f42af4f8d1f8e8b1146aed44dc36975061fbf360a983":{"record":"6b10514d50e745f1dab5a40e8629ecf1a8c78a5d6e3895f3637fb67d2d3f9993", "label":"Yahoo! Mail", "favicon":"http://login.yahoo.com/favicon.ico"}}, "preferences":{"preferredLanguage":"en-US"}};
/* */
		plainText = {
			"records": {
				"1": {
					"label":"imap4all [no]",
					"key":"f54b5033d1152456acb67974c45ee6771f8411e300c9533359dfacacf60dcbbd",
					"notes":""
				},
				"2": {
					"label":"Il manifesto",
					"key":"6e0ef134503110e72f444e7d102a4b1cc6ae28f2e0b1287c2b1875ff052fc16c",
					"notes":""
				},
				"3": {
					"label": "OmniGroup applications",
					"key": "7b432b7dae39ff5951db31947fa721dc012af0da4055760c6df3b02e776ef22c",
					"notes": "url: http://www.omnigroup.com\n\nLicence owner: Giulio Cesare Solaroli\n\nOmniWeb: EQGP-EMKH-LKWP-MUHQ-OUHL-LDF\nOmniGraffle:\nOmniOutliner:\nOmniDiskSweeper:"
				},
				"4": {
					"label": "R@cine",
					"key": "57295772c84669b0a224f435e9b75c797ae5999a2d9473ab50f9384ae54f49d6",
					"notes": ""
				},
				"5": {
					"label": "NewsGator",
					"key": "6ee16f6932ee02000c49dbcc685c84074b40d7956e5f4bc1100030a0f9a41f1a",
					"notes": ""
				},
				"6": {
					"label": "GMail - giulio.cesare",
					"key": "0395efd852b00700bcf78b65350ec15932430df71201d2c53a11b0269b557d1a",
					"notes": ""
				},
				"7": {
					"label": "GMail - clipperz.com",
					"key": "90d6ae70d89c8211404b6f9d6c70b6b9c113fff74f474a67b34acd9c1c048d1f",
					"notes": ""
				},
				"8": {
					"label": "Aruba - hosting",
					"key": "5c7472d24d57391c63ea99ed1fc9de179d225abd335fa65702018cfea6083d47",
					"notes": ""
				},
				"9": {
					"label": "Aruba - sql hosting",
					"key": "f6bd942ac3b0b7065771e5197c7499b345a10f7a4843d00c3ba3809d0ea059dc",
					"notes": ""
				},
				"10": {
					"label": "Amazon",
					"key": "1ae022b4d14b642f113703b2a98931bd892dec2da785ab5ff6fc1d0aac7537f1",
					"notes": ""
				},
				"11": {
					"label": "YouTube [no]",
					"key": "4c6593d4f6448137939b364b84c81501fadb60f7871fe5fa63c93e97bb5c4648",
					"notes": ""
				},
				"12": {
					"label": "DynDns - gcsolaroli",
					"key": "f8ed9e7a3630deed046eda37ebc63ecb4d63668a2f97224d7628fdc53b242467",
					"notes": ""
				},
				"13": {
					"label": "DynDns - clipperz",
					"key": "d8bc295177383a523e67b61b166e0ca956ab4c2ee86800559a009d2716064f6d",
					"notes": ""
				},
				"14": {
					"label": "Bol.it",
					"key": "cada5dadeebd8d12190954d21f1a944c8799d034f028be195b448935fcf970c7",
					"notes": ""
				},
				"15": {
					"label": "Freenigma - clipperz.com",
					"key": "f09cb3790c1110794b834702b8c487c1a42b2508fbe6450a8468477d93790b2e",
					"notes": ""
				},
				"16": {
					"label": "Freenigma",
					"key": "4b05738f6faebc147eac5e425054a91d3cc59dd63844e82d1f0864c0ac8efec7",
					"notes": ""
				},
				"17": {
					"label": "clipperz.com - blog",
					"key": "9cc24328bbce18e8713962428d8123e309a12f7e1d9537bc252e134501734003",
					"notes": ""
				},
				"18": {
					"label": "Apple",
					"key": "75f2651af400629c4e5dd8bcdc3a6c691150d23d6e1a4eb263ff810926d1228f",
					"notes": ""
				},
				"19": {
					"label": ".mac",
					"key": "5934ae96d2e01282effb368d9086c2ba5d1d856ad91dd6f04f5bace26a1c0cbe",
					"notes": ""
				},
				"20": {
					"label": "3nity",
					"key": "33d84c4a91ab053cbf8115c689ede7e504b81199884de449acc257bea534f57f",
					"notes": ""
				},
				"21": {
					"label": "ACM",
					"key": "b4976bb0892baba81d628513d191de100d89acd58efbb07c823a5bb4abe48a7a",
					"notes": ""
				},
				"22": {
					"label": "Adobe",
					"key": "d162bc404994a79ec97e0106c3a4edf2f83aca25def130242e11e95e74bd0aaa",
					"notes": ""
				},
				"23": {
					"label": "Adobe Photoshop Elements 3",
					"key": "18a62c3c2065399819707322f467ff4be030d7939acbe5182c8194599845c428",
					"notes": "Photoshop Elements 2:\n1057-4312-5223-2206-9509-6837"
				},
				"24": {
					"label": "Agatra",
					"key": "c35158a21b2af75d414232b742ab738d042314e00209f8fca94c8c704c891f23",
					"notes": ""
				},
				"25": {
					"label": "AIM",
					"key": "8561ac421d845921978387b5e6b362750b57ed08feda8ec12b9378b69f67ceff",
					"notes": ""
				},
				"26": {
					"label": "Anna Vespignani",
					"key": "79a970af0d2d30643dc2db4d16757395c1f22c311919036c2a22b7581982144a",
					"notes": ""
				},
				"27": {
					"label": "Bloglines",
					"key": "fe81f4df8c42fd81c830f9af408e9b074e77fd430e26d0ee285844fe3b092aec",
					"notes": ""
				},
				"28": {
					"label": "Bow.it",
					"key": "64a1a9fec99c9238dc8180a01484a1ccf5f50fcd6e9a95a52b8b49fb9ca00bdc",
					"notes": ""
				},
				"29": {
					"label": "GMail - feedback",
					"key": "fad310cb2e6152c3faf78b7183c99f3044f5d31ee364068b80580c271a7784ef",
					"notes": ""
				},
				"30": {
					"label": "Fineco",
					"key": "8f99de2635b5dad7987180bc0bff49947eb37cc75d6a5d1ee1f13ed7567465a3",
					"notes": ""
				},
				"31": {
					"label": "mon.itor.us",
					"key": "d2aa7164007c5deac8bb73580a6ab0d051f747e801ecd30284eff725d0ffaba2",
					"notes": ""
				},
				"32": {
					"label": "Lamba Probe - forum",
					"key": "f73906817fddba4d8f816334cb2fd0cd5ae91bc29bce6a69fdd5cf98fc96911f",
					"notes": ""
				},
				"33": {
					"label": "MeasureMap",
					"key": "2385ce9536ebb7863b6a4c8b1f5c428587e4d6420a4bbcd31b935cb00bbd768e",
					"notes": ""
				},
				"34": {
					"label": "NGI - Squillo",
					"key": "96f20c212be02fb38c8b2dfc83d8e864dd84dcb95297a7fecf9280e1e4dcffe3",
					"notes": ""
				},
				"35": {
					"label": "NGI - F5",
					"key": "00347769244b208647c24e6a64f8fa4213e97eb2135ecfcb277b341c28616a59",
					"notes": ""
				},
				"36": {
					"label": "CVSdude",
					"key": "ed0ab5080a29eb1b20927142d21ab8f67b61c2c7b19623bb610af030dfd42c02",
					"notes": ""
				},
				"37": {
					"label": "Yahoo",
					"key": "6380a7655cd790d1f1e6f482e92ae04201568ff0cab887e65102e9396df1b86e",
					"notes": "note"
				}
			},
			"directLogins": {
				"1": { "record": "1", "label": "R@cine - WebMail", "favicon": "http://www.racine.ra.it/favicon.ico" },
				"2": { "record": "2", "label": "Amazon sign in", "favicon": "http://www.amazon.com/favicon.ico" },
				"3": { "record": "3", "label": "Aruba - hosting", "favicon": "http://hosting.aruba.it/favicon.ico" },
				"4": { "record": "4", "label": "Aruba - sql hosting", "favicon":"http://mysql.aruba.it/favicon.ico" },
				"5": { "record": "5", "label":"clipperz.com - blog", "favicon":"http://www.clipperz.com/favicon.ico" },
				"6": { "record":"6", "label":"DynDns - clipperz", "favicon":"http://www.dyndns.com/favicon.ico" },
				"7": { "record":"7", "label":"DynDns - gcsolaroli", "favicon":"http://www.dyndns.com/favicon.ico" },
				"8":{"record":"8", "label":"GMail - giulio.cesare", "favicon":"http://www.google.com/favicon.ico" },
				"9":{"record":"9", "label":"Il manifesto", "favicon":"http://abbonati.ilmanifesto.it/favicon.ico" },
				"10":{"record":"10", "label":"Imap4All.com - account", "favicon":"http://www.imap4all.com/favicon.ico" },
				"11":{"record":"12", "label":"Imap4all.com - WebMail", "favicon":"http://webmail.imap4all.com/favicon.ico" },
				"13":{"record":"13", "label":"YouTube [no]", "favicon":"http://www.youtube.com/favicon.ico" },
				"14":{"record":"14", "label":"Apple Store - Italia", "favicon":"http://store.apple.com/favicon.ico" },
				"15":{"record":"15", "label":"3nity", "favicon":"http://www.3nity.de/favicon.ico" },
				"16":{"record":"16", "label":"ACM Web Account", "favicon":"http://portal.acm.org/favicon.ico" },
				"17":{"record":"17", "label":"Adobe - Sign In", "favicon":"http://www.adobe.com/favicon.ico" },
				"18":{"record":"18", "label":"Agatra [no]", "favicon":"http://www.agatra.com/favicon.ico" },
				"19":{"record":"19", "label":"AIM [no]", "favicon":"http://my.screenname.aol.com/favicon.ico" },
				"20":{"record":"20", "label":"Bloglines", "favicon":"http://www.bloglines.com/favicon.ico" },
				"21":{"record":"21", "label":"Bow.it", "favicon":"http://www.bow.it/favicon.ico" },
				"22":{"record":"22", "label":"GMail - Clipperz", "favicon":"http://www.google.com/favicon.ico" },
				"23":{"record":"23", "label":"GMail - feedback", "favicon":"http://www.google.com/favicon.ico" },
				"24":{"record":"24", "label":"Blogger", "favicon":"http://www.google.com/favicon.ico" },
				"25":{"record":"25", "label":"NewsGator", "favicon":"http://www.newsgator.com/favicon.ico" },
				"26":{"record":"26", "label":"Lambda Probe - forum", "favicon":"http://www.lambdaprobe.org/favicon.ico" },
				"27":{"record":"27", "label":"Measure Map", "favicon":"http://alpha.measuremap.com/favicon.ico" },
				"28":{"record":"28", "label":"NGI - Squillo", "favicon":"http://www.ngi.it/favicon.ico" },
				"29":{"record":"29", "label":"NGI - F5", "favicon":"http://www.ngi.it/favicon.ico" },
				"30":{"record":"30", "label":"Yahoo! Mail", "favicon":"http://login.yahoo.com/favicon.ico"}
			},
			"preferences":{"preferredLanguage":"en-US"}
		}
/* */

//console.profile("encrypt 0.2");
		encryptedText = Clipperz.PM.Crypto.encryptingFunctions.versions['0.2'].encrypt(password, plainText);
//console.profileEnd();
//console.profile("decrypt");
//		decryptedText = Clipperz.PM.Crypto.encryptingFunctions.versions['0.2'].decrypt(password, encryptedText);
//console.profileEnd();
//		is(MochiKit.Base.serializeJSON(decryptedText), MochiKit.Base.serializeJSON(plainText), "complex structure encrypted/decrypted");

//console.profile("encrypt 0.3");
		encryptedText = Clipperz.PM.Crypto.encryptingFunctions.versions['0.3'].encrypt(password, plainText);
//console.profileEnd();
		decryptedText = Clipperz.PM.Crypto.encryptingFunctions.versions['0.3'].decrypt(password, encryptedText);
		is(MochiKit.Base.serializeJSON(decryptedText), MochiKit.Base.serializeJSON(plainText), "complex structure encrypted/decrypted");
	},
	
    //-------------------------------------------------------------------------
    'syntaxFix': MochiKit.Base.noop
};



//#############################################################################

SimpleTest.runDeferredTests("Clipperz.Crypto.AES.performance", tests, {trace:false});
