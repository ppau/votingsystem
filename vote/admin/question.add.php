<?php

	$__msg = '';
	if( !empty( $_POST ) )
	{
		$db = get_db();
		
		$options = $_POST['options'];
		$options = explode( ',', $options );
		if( count( $options ) > 0 )
		{
			foreach( $options as $i => $option )
			{
				$options[$i] = trim( $option );
			}
		}
		
		$sql = sprintf( '
			SELECT
				MAX( rank ) as max_rank
			FROM
				form_vote_questions
			WHERE
				election_id = %d
			LIMIT
				1
			',
			$_GET['_param']
		);
		
		$rank = $db->GetRow( $sql );
		
		$fields = array(
			'election_id' => $_GET['_param'],
			'question' => $_POST['question'],
			'info' => $_POST['info'],
			'type' => $_POST['type'],
			'options' => serialize( $options ),
			'rank' => ( $rank['max_rank'] + 1 ),
		);
		
		$db->AutoExecute( 'form_vote_questions', $fields, 'INSERT' );
		
		$id = $db->Insert_ID();
		
		$_SESSION['message'] = 'Question successfully added to election';
		
		header( 'Location: /admin/question/' . $id . '/', true, 301 );
		die();
	}
	
	
	echo <<< HTML
<script type="text/javascript">
	function __clear()
	{
		$( '#custom' ).hide();
	}
	
	function __custom()
	{
		$( '#custom' ).show();
	}
</script>
Add new question:
<div class="gap"></div>
<div style="color: #F00; text-align: center;">{$__msg}</div>
<form action="/admin/question/{$_GET['_param']}/add/" enctype="application/x-www-form-urlencoded" method="post">
	<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td valign="top">Question:</td>
			<td><input type="text" name="question" style="width: 500px;" value="" /></td>
		</tr>
		<tr>
			<td valign="top">Info:</td>
			<td><textarea type="text" name="info" style="width: 500px;"></textarea></td>
		</tr>
		<tr>
			<td valign="top">Type:</td>
			<td>
				<select name="type" style="width: 200px;" onchange="__clear();">
					<option>--- Select Type ---</option>
					<option value="check_yes_no">Yes / No Option</option>
					<option value="check_custom" onclick="__custom();">Custom Options</option>
					<option value="text_box">Text Box</option>
					<option value="large_text_box">Large Text Box</option>
				</select>
				<div id="custom" style="display: none;">
					<div style="height: 4px;"></div>
					Options: <input type="text" name="options" style="width: 325px;" value="" /> (comma seperated)
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<input type="submit" class="button" name="add" value="Add Question" />
				<input type="submit" class="button" name="back" value="Back" onclick="window.location='/admin/elections/{$_GET['_param']}/'; return false;" />
			</td>
		</tr>
	</table>
</form>

HTML;
	