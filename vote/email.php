<?php
include_once('Smarty/libs/Smarty.class.php');
include_once('Swift-4.0.6/lib/swift_required.php');
include('database.php');
require_once('/var/www/src/group.php');
require_once('/var/www/src/standardCurves.php');

function passwordProtect($username, $password){
	if (
			(
				!isset($_SERVER['PHP_AUTH_USER']) ||
				(
					isset($_SERVER['PHP_AUTH_USER']) &&
					$_SERVER['PHP_AUTH_USER'] != $username
				)
			) &&
			(
				!isset($_SERVER['PHP_AUTH_PW']) ||
				(
					isset($_SERVER['PHP_AUTH_PW']) &&
					$_SERVER['PHP_AUTH_PW'] != $password
				)
			)
		)
	{
		header('WWW-Authenticate: Basic realm="Login"');
		header('HTTP/1.0 401 Unauthorized');
		echo 'Please login to continue.';
		exit;
	}
}

passwordProtect("admin", "bNyXRcP1DT7d0JVlxBgJ3BTz");
//Create the Transport
//$transport = Swift_MailTransport::newInstance();
$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465,'ssl')
  ->setUsername('alexis.shaw@gmail.com')
  ->setPassword('loadingreadyrun')
  ;
//Create the Mailer using your created Transport
$mailer = Swift_Mailer::newInstance($transport);
$IDs = getnames();
$group = new StandardCurve('P256');
foreach($IDs as $ID){
	// generate Key pair new primeFieldValue($group->n_field, '594a4870c24763126167fda80a49d464abb17ac19d53a3cd92c5e837710b24fd', 16)
	$privateKey = $group->n_field->randomMemberNOZero();
	$publicKey  = $group->G->intMultiply($privateKey->asString());
	
	$smarty = new Smarty;
	$smarty->assign('name',  $ID['firstname'] . ' ' . $ID['surname']);
	$smarty->assign('privateKey', $privateKey->asString(16));
	$message = Swift_Message::newInstance('Here is your Vote')
		->setFrom(array('votes@votes.alexisshaw.com' => 'PPAU voting system'))
		->setTo(array($ID['email'] => $ID['firstname'] . ' ' . $ID['surname']))
		->setBody($smarty->fetch('email.tpl'), 'text/html');
	addPubkey($publicKey);
	echo '<p>Sending Email to ' . $ID['firstname'] . ' ' . $ID['surname'] . '&lt;' .$ID['email'] . '&gt; </p>' ."\n";
	//send the message
	$result = $mailer->send($message);
}
