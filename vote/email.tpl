<html>
	<body>
		<p>Dear {$name},</p>
		
		<p>Here is a link to the voting system beta</p>
		
		<p><a href="http://{$smarty.server.SERVER_NAME}/vote/{$election_id}/#pKey={$privateKey}">http://{$smarty.server.SERVER_NAME}/vote/{$election_id}/#pKey={$privateKey}</a></p>
	</body>
</html>
