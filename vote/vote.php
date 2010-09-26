<html>
<title>voting system</title>
<style>
body, select { font-size:14px; }
form { margin:5px; }
p { color:red; margin:5px; }
b { color:blue; }
</style>
<script type="text/javascript" src="Clipperz/src/js/MochiKit/MochiKit.js"></script>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script src="jquery.json-2.2.min.js" type="text/javascript" charset="utf-8"></script>
<script src="jquery.base64.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript"> 
  jQuery._$ = MochiKit.DOM.getElement; 
  var $j = jQuery.noConflict(); 
</script>
<script type='text/javascript' src='Clipperz/src/js/Clipperz/YUI/Utils.js'></script>
<script type='text/javascript' src='Clipperz/src/js/Clipperz/YUI/DomHelper.js'></script>
<script type='text/javascript' src='Clipperz/src/js/Clipperz/Base.js'></script>
<script type='text/javascript' src='Clipperz/src/js/Clipperz/Logging.js'></script>
<script type='text/javascript' src='Clipperz/src/js/Clipperz/ByteArray.js'></script>
<script type='text/javascript' src='Clipperz/src/js/Clipperz/Async.js'></script>

<script type='text/javascript' src='Clipperz/src/js/Clipperz/Crypto/BigInt.js'></script>
<script type='text/javascript' src='Clipperz/src/js/Clipperz/Crypto/Base.js'></script>
<script type='text/javascript' src='Clipperz/src/js/Clipperz/Crypto/AES.js'></script>
<script type='text/javascript' src='Clipperz/src/js/Clipperz/Crypto/SHA.js'></script>
<script type='text/javascript' src='Clipperz/src/js/Clipperz/Crypto/PRNG.js'></script>

<script type='text/javascript' src='Clipperz/src/js/Clipperz/Crypto/ECC/PrimeField/FiniteField.js'></script>
<script type='text/javascript' src='Clipperz/src/js/Clipperz/Crypto/ECC/PrimeField/Curve.js'></script>
<script type='text/javascript' src='Clipperz/src/js/Clipperz/Crypto/ECC/PrimeField/Point.js'></script>
<script type='text/javascript' src='Clipperz/src/js/Clipperz/Crypto/ECC/StandardCurves.js'></script>

<script type='text/javascript' src='Clipperz/src/js/Clipperz/Crypto/ecdsa.js'></script>
<script type='text/javascript' src='Clipperz/src/js/Clipperz/Crypto/ECBlind.js'></script>

<?php
  $elections = array(
    'platform' => array('That the Party adopts the Draft Platform in its entirety as its first election manifesto.','rambling here', array('Yes','No')),
    'ppi' => array('Should Pirate Party Australia formally become a member of the organisation Pirate Parties International?',' moar rambling', array('Yes','No')),
    'secretary' => array('Who should be Rodneys bitch??',' moar rambling', array('Simon Frew','Brendan Molloy')),
    'quorum' => array('To raise the quorum for constitutional amendments, in Article 7, by two (2) percent, from ten (10) percent to twelve (12) percent?',' moar rambling', array('Yes','No')),
    'grammar1' => array('Amendment to Correct Grammatical Errors in Part I',' moar rambling', array('Yes','No')),
    'amendsec' => array('Amendment to Specify Additional Requirements of the Party Secretary',' moar rambling', array('Yes','No')),
    'ammendpres' => array('Amendment to Specify Additional Requirements of the Party Secretary',' moar rambling', array('Yes','No')),
  );
?>
<p><b>Results:</b>
<span id="results" style="visibility:hidden;">
<?php
  // draw html for each result to be shown here, but make it invisible for now
  // exactly how we want to do this feedback I'm not sure yet
  foreach ($elections as $id => $a) {
    echo '<span id="result-'.$id.'"></span>';
  }
?>
</span>
</p>
<?php
  // create a form for each vote
  foreach ($elections as $id => $a) {
    echo '<form id="'.$id.'">';
    echo '<h3>'.$a[0].'</h3>';
    if (!empty($a[1])) { echo $a[1].'<br />'; }
    foreach ($a[2] as $vote) {
      $var = ereg_replace("[^A-Za-z0-9]", "", $vote);
      echo '<b>'.$vote.':</b> <input type="radio" name="'.$id.'" value="'.$var.'">';
    }
    // add individual resubmit forms hidden by default?
    echo '</form>'."\n";
  }
/* former example only
<form id="startrek">
	<h1>What is Your Favourite Star Trek Character</h1>
	<table>
		<tr style="background:#FDD"><td>James T. Kirk</td>
			<td><input type="text" name="kirk" /></td></tr>
		<tr style="background:#DFD"><td>Spock</td>
			<td><input type="text" name="spock" /></td></tr>
		<tr style="background:#FDD"><td>Leonard "Bones" McCoy</td>
			<td><input type="text" name="mccoy" /></td></tr>
		<tr style="background:#DFD"><td>Montgomery "Scotty" Scott</td>
			<td><input type="text" name="scotty" /></td></tr>
		<tr style="background:#FDD"><td>Hikaru Sulu</td>
			<td><input type="text" name="sulu" /></td></tr>
		<tr style="background:#DFD"><td>Uhura</td>
			<td><input type="text" name="uhura" /></td></tr>
		<tr style="background:#FDD"><td>Christine Chapel</td>
			<td><input type="text" name="Chapel" /></td></tr>
		<tr style="background:#DFD"><td>Janice Rand</td>
			<td><input type="text" name="Rand" /></td></tr>
	</table>
</form>
*/ ?>
	<table>
		<tr style="background:#FFF"><td></td>
			<td><b>Warning: Once this form has been submitted, it can not be changed</b><br /><INPUT TYPE="button" id="submit" NAME="myButton" VALUE="Submit" onClick=""><div id="noentropy">You need to generate more entropy by moving your mouse around the screen before you can submit</div></td></tr>
	</table>
<script language="JavaScript">
//document.write (getArray);
function sendVotes(){
	var fullHref = new String(window.location.href);
  	var hrefParts = fullHref.split('#pKey=');
  	var group = Clipperz.Crypto.ECC.StandardCurves.P256();
  	var hash = 'sha256';
	var d = new Clipperz.Crypto.BigInt(hrefParts[1], 16);
<?php
  // need to send all votes here
  foreach ($elections as $id => $a) {
    echo "        sendVote('$id', d, group, hash);\n";
  }
//	sendVote('startrek', d, group, hash);
?>
}
function sendVote(election, d, group, hash) {
	//serialise the form
	var reqobj = {'election':election,'stamp':Math.round(((new Date()).getTime()-Date.UTC(1970,0,1))/1000)};
	var req = $j.base64Encode($j.toJSON(reqobj));
	var sigobj = Clipperz.Crypto.ECDSA.sign(req, d, group, hash);
	var sig = $j.base64Encode($j.toJSON({'r':sigobj.r.asString(16), 's':sigobj.s.asString(16)}));
	var Q = group.multiply(d,group.G());
	var pk = $j.base64Encode($j.toJSON(Q.asJSONObj()));
	var uri = 'http://vote.pirateparty.org.au/blindReq.php?req='+req+'&sig='+sig+'&pk='+pk+'&callback=?';
    $j.ajax({ url: uri, dataType: 'jsonp', success: function(data){sendVote2(data, election, group, hash)} });
}
function sendVote2(jsonp, election, group, hash){
if (jsonp===false){
	//error -- wrong VoterID
}else{
	var vote = $j.base64Encode($j.toJSON($j("#"+election).serializeArray()));
	var tx = new Clipperz.Crypto.BigInt(jsonp.x , 16);
	var ty = new Clipperz.Crypto.BigInt(jsonp.y, 16);
	var Rcap = new Clipperz.Crypto.ECC.PrimeField.Point({x:tx , y:ty , z: new Clipperz.Crypto.BigInt("1") });
	var blindness = Clipperz.Crypto.ECBlind.blindness(vote, Rcap, group, hash);
	var blindSign = $j.base64Encode($j.toJSON({'hcap':blindness.hcap.asString(16), 'Rcap':Rcap.asJSONObj()}));
	var uri = 'http://vote.pirateparty.org.au/blindSign.php?req='+blindSign+'&callback=?';
    $j.ajax({ url: uri, dataType: 'jsonp', success: function(data){sendVote3(data, election, blindness, vote, Rcap, group, hash)} });
}
}
function sendVote3(data,election, blindness, vote, Rcap, group, hash){
if (data === false){
	//error submitting vote  contact support errorID 1
} else {
	var hcapOut = new Clipperz.Crypto.BigInt(data.hcap, 16);
	if(hcapOut.equals(blindness.hcap)){
		var scap = new Clipperz.Crypto.BigInt(data.scap, 16)
		var s = Clipperz.Crypto.ECBlind.unblindness(scap, blindness.R, Rcap, vote, blindness.beta, group, hash);
		var bsig = $j.base64Encode($j.toJSON({'s':s.asString(16), 'R':blindness.R.asJSONObj()}));
		var uri = 'http://vote.pirateparty.org.au/addVote.php?vote='+vote+'&election='+election+'&bsig='+bsig+'&callback=?';
		$j.ajax({ url: uri, dataType: 'jsonp', success: function(data){sendVote4(data)} });
	}
}}
function sendVote4(data){
if (data === false){
	//error submitting vote contact support errorID 2
} else {
	$j("#results").empty();
<?php // we'll want to append something more meaningful later ?>
    $j("#results").append($j.toJSON(data));
}}
    $j('#submit').attr("disabled", true);
	$j('#submit').click(function(){sendVotes()});
		Clipperz.Async.callbacks("Clipperz.Crypto.PRNG.main_test", [
				MochiKit.Base.method(Clipperz.Crypto.PRNG.defaultRandomGenerator(), 'deferredEntropyCollection'), 
				function () {$j('#submit').attr("disabled", false);}
			])
    //$j("input").change(showValues);
    //$j("select").change(showValues);
    //showValues();
</script>
</html>
