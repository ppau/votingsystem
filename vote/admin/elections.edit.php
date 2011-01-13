<?php

	$__msg = '';
	if( !empty( $_POST ) )
	{
		$db = get_db();
		$fields = array(
			'name' => $_POST['name'],
			'active' => $_POST['status'],
		);
		
		$db->AutoExecute( 'form_vote', $fields, 'UPDATE', sprintf( 'id = %d', $_GET['_param'] ) );
		
		$__msg = 'Election information updated.<br /><br />';
	}
	
	if( isset( $_SESSION['message'] ) )
	{
		$__msg = $_SESSION['message'];
		unset( $_SESSION['message'] );
	}

	$election = get_elections_by_id( $_GET['_param'] );
	
	$questions = get_questions_for_election( $election['id'] );
	
	$status = array( 'yes' => '', 'no' => '' );
	
	$status[$election['active']] = ' checked="checked"';
	$start_date = date( 'd/m/Y - h:i a', $election['start'] );
	$end_date = date( 'd/m/Y - h:i a', $election['end'] );
	
	echo <<< HTML
			<div class="gap"></div>
			<div style="color: #F00; text-align: center;">{$__msg}</div>
			<form action="{$_SERVER['REQUEST_URI']}" method="POST" enctype="application/x-www-form-urlencoded">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td width="140">Election Name:</td>
						<td><input type="text" name="name" value="{$election['name']}" style="width: 250px;" /></td>
					</tr>
					<tr>
						<td>Active:</td>
						<td>
							<input type="radio" id="status_yes" name="status" value="yes"{$status['yes']} /><label for="status_yes"> Yes</label>
							<input type="radio" id="status_no" name="status" value="no"{$status['no']} /><label for="status_no"> No</label>
						</td>
					</tr>
					<tr>
						<td>Start Date:</td>
						<td>{$start_date}</td>
					</tr>
					<tr>
						<td>End Date:</td>
						<td>{$end_date}</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>
							<input type="submit" class="button" name="update_election" value="Update Election" />
						</td>
					</tr>
				</table>
			</form>
			
			<div class="right"><a href="/admin/question/{$_GET['_param']}/add/">Add Question</a></div>
			<div class="gap"></div>

HTML;

	if( count( $questions ) > 0 )
	{
		echo <<< HTML
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<thead>
					<td width="50">ID</td>
					<td>Question</td>
					<td width="80">Action</td>
					<td width="60" align="center">Rank</td>
				</thead>

HTML;
		foreach( $questions as $question )
		{
			$question_output = ( strlen( $question['question'] ) > 90 ) ? substr( $question['question'], 0, 90 ) . '...' : $question['question'];
			
			$rank['up'] = ( $question['rank'] > 1 ) ? '<a href="/admin/rank/'.$question['id'].'/up/"><img src="/images/rank_up.png" width="16" height="16" border="0" /></a>' : '';
			$rank['down'] = ( $question['rank'] < $question['max_rank'] ) ? '<a href="/admin/rank/'.$question['id'].'/down/"><img src="/images/rank_down.png" width="16" height="16" border="0" /></a>' : '';
			
			echo <<< HTML
				<tbody>
					<td>{$question['id']}</td>
					<td>{$question_output}</td>
					<td><a href="/admin/question/{$question['id']}/">Edit</a> / <a href="/admin/question/{$question['id']}/del/">Delete</a></td>
					<td align="center">{$rank['up']}{$rank['down']}</td>
				</tbody>

HTML;
		}
		
		echo <<< HTML
			</table>
HTML;
	}