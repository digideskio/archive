<?php 

	$this->title($user->username);

	$check = lithium\security\Auth::check('default');

	$auth = app\models\Users::find('first', array(
		'with' => 'Roles',
		'conditions' => array('username' => $check['username']),
	));

	$role = $auth->role->name;

	if($auth->timezone_id) {
		$tz = new DateTimeZone($auth->timezone_id);
	}

?>


<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Users','/users'); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
	<?=$this->html->link($user->username,'/users/view/'.$user->username); ?>
	</li>

	</ul>

</div>

<div class="actions">

	<ul class="nav nav-tabs">
		<li class="active">
			<?=$this->html->link('View','/users/view/'.$user->username); ?>
		</li>

		<?php if($role == 'Admin' || $auth->username == $user->username): ?>
		<li>
			<?=$this->html->link('Edit','/users/edit/'.$user->username); ?>
		</li>
		<?php endif; ?>
	</ul>

</div>

<div class="alert alert-info">

<h1><?=$user->name ?></h1>

<h5><?=$user->email ?></h5>

<h6><?=$user->role->name ?></h6>

</div>

<table class="table">

	<thead>
		<tr>
			<th>User</th>
			<th style="min-width:150px">Date</th>
			<th>Title</th>
		<tr>
	</thead>
	<tbody>

	<?php if (sizeof($archives_histories) > 0 ): ?>

	<?php foreach( $archives_histories as $ah ): ?>

		<?php
			$start_date_string = date("Y-m-d H:i:s", $ah->start_date);
			$start_date_time = new DateTime($start_date_string);

			if (isset($tz)) {
				$start_date_time->setTimeZone($tz);
			}
			$start_date_display = $start_date_time->format("Y-m-d H:i:s");
		?>

		<tr>
			<td style="text-align:center">
				<?php if( $ah->user->id ): ?>
				<strong style="font-size: smaller;">
					<?=$this->html->link($ah->user->initials(),'/users/view/'.$ah->user->username); ?>
				</strong>
				<?php endif; ?>
			</td>
			<td><?=$start_date_display ?></td>
			<td>
				<?php if ($ah->archive->id): ?>
					<?=$this->html->link($ah->name,"/$ah->controller/history/".$ah->slug); ?>
				<?php else: ?>
					<?=$ah->name ?> <span class="meta muted text-error">&mdash; Deleted</span>
				<?php endif; ?>
			</td>
		</tr>
	<?php endforeach; ?>

	<?php endif; ?>

	</tbody>

</table>

<?=$this->pagination->pager('users', "view/$user->username", $page, $total, $limit); ?>

<div class="modal fade hide" id="deleteModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Delete User</h3>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to permanently delete <strong><?=$user->name; ?></strong>?</p>
			</div>
			<div class="modal-footer">
			<?=$this->form->create($user, array('url' => "/users/delete/$user->username", 'method' => 'post')); ?>
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
			<?=$this->form->submit('Delete', array('class' => 'btn btn-danger')); ?>
			<?=$this->form->end(); ?>
	</div>
</div>
