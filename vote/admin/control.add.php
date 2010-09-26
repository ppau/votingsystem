Add new admin account.
<div class="gap"></div>
<form action="/admin/control/add/" enctype="application/x-www-form-urlencoded" method="post">
	<table>
		<tr>
			<td>Username:</td>
			<td><input type="text" name="username" /></td>
		</tr>
		<tr>
			<td>Password:</td>
			<td><input type="password" name="password" /></td>
		</tr>
		<tr>
			<td>Confirm Password:</td>
			<td><input type="password" name="confirm" /></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" name="add" value="Add Account" /></td>
		</tr>
	</table>
</form>
<?php
