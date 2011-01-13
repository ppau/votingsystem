<?php

	$__msg = '';
	if( !empty( $_POST ) )
	{
		$options = $_POST['options'];
		$options = explode( ',', $options );
		if( count( $options ) > 0 )
		{
			foreach( $options as $i => $option )
			{
				$options[$i] = trim( $option );
			}
		}
		
		$fields = array(
			'question' => $_POST['question'],
			'info' => $_POST['info'],
			'type' => $_POST['type'],
			'options' => serialize( $options ),
		);
		
		$db = get_db();
		$db->AutoExecute( 'form_vote_questions', $fields, 'UPDATE', sprintf( 'id = %d', $_GET['_param'] ) );
		
		$__msg = 'Question information updated.<br /><br />';
	}
	
	if( isset( $_SESSION['message'] ) )
	{
		$__msg = $_SESSION['message'];
		unset( $_SESSION['message'] );
	}

	$question = get_question( $_GET['_param'] );
	
	if( !empty( $question['options'] ) )
		$question['options'] = implode( ', ', unserialize( $question['options'] ) );
	
	$type[$question['type']] = ' selected';
	$show_div = ( $question['type'] == 'check_custom' ) ? 'block' : 'none';
	
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
Edit question:
<div class="gap"></div>
<div style="color: #F00; text-align: center;">{$__msg}</div>
<form action="/admin/question/{$_GET['_param']}/" enctype="application/x-www-form-urlencoded" method="post">
	<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td valign="top">Question:</td>
			<td><input type="text" name="question" style="width: 500px;" value="{$question['question']}" /></td>
		</tr>
		<tr>
			<td valign="top">Info:</td>
			<td><textarea type="text" name="info" style="width: 500px;">{$question['info']}</textarea></td>
		</tr>
		<tr>
			<td valign="top">Type:</td>
			<td>
				<select name="type" style="width: 200px;" onchange="__clear();">
					<option>--- Select Type ---</option>
					<option value="check_yes_no"{$type['check_yes_no']}>Yes / No Option</option>
					<option value="check_custom" onclick="__custom();"{$type['check_custom']}>Custom Options</option>
					<option value="text_box"{$type['text_box']}>Text Box</option>
					<option value="large_text_box"{$type['large_text_box']}>Large Text Box</option>
				</select>
				<div id="custom" style="display: {$show_div};">
					<div style="height: 4px;"></div>
					Options: <input type="text" name="options" style="width: 325px;" value="{$question['options']}" /> (comma seperated)
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<input type="submit" class="button" name="update" value="Update Question" />
				<input type="submit" class="button" name="back" value="Back" onclick="window.location='/admin/elections/{$question['election_id']}/'; return false;" />
			</td>
		</tr>
	</table>
</form>

HTML;
	