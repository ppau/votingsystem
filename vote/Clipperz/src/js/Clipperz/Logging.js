Clipperz.Base.module('Clipperz');

if (typeof console == 'undefined') {
	Clipperz.log = MochiKit.Logging.logDebug;
// Safari/WebKit 4
} else if (navigator.userAgent.match(/WebKit/)) {
//	Clipperz.log = console.log;
	Clipperz.log = MochiKit.Logging.logDebug;
} else if (navigator.userAgent.match(/Gecko/)) {
	Clipperz.log = function () { 
//	firebug 1.3 bug see http://code.google.com/p/fbug/issues/detail?id=1347		
		console.log.apply(window._firebug, arguments);
	};
}