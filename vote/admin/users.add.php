Add new admin account.
<div class="gap"></div>
<form action="/admin/control/add/" enctype="application/x-www-form-urlencoded" method="post">
	<table>
		<tr>
			<td>First Name:</td>
			<td><input type="text" name="firstname" /></td>
		</tr>
		<tr>
			<td>Surname:</td>
			<td><input type="text" name="surname" /></td>
		</tr>
		<tr>
			<td>Email Address:</td>
			<td><input type="text" name="email" /></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" name="add" value="Add User" /></td>
		</tr>
	</table>
</form>
<?php
