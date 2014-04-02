<?php

	$this->title($user->username);

	$auth = $this->authority->auth();

	$role = $auth->role->name;

	$this->form->config(
		array(
			'templates' => array(
				'error' => '<div class="help-inline">{:content}</div>'
			)
		)
	);

?>

<div id="location" class="row-fluid">


	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Users','/users'); ?>
	<span class="divider">/</span>
	</li>

	<li>
	<?=$this->html->link($user->username,'/users/view/'.$user->username); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
		Edit
	</li>

	</ul>

</div>

<div class="actions">

	<ul class="nav nav-tabs">
		<li>
			<?=$this->html->link('View','/users/view/'.$user->username); ?>
		</li>

		<li class="active">
			<?=$this->html->link('Edit','/users/edit/'.$user->username); ?>
		</li>
	</ul>

</div>

<?php if(!$user->active): ?>

<div class="alert alert-error">
This user is no longer active.
</div>

<?php endif; ?>
<div class="well">
<?=$this->form->create($user); ?>
    <?=$this->form->field('username', array('autocomplete' => 'off', 'disabled' => 'disabled'));?>
    <?=$this->form->field('password', array('type' => 'password')); ?>
    <?=$this->form->field('name', array('autocomplete' => 'off'));?>
    <?=$this->form->field('email', array('autocomplete' => 'off'));?>

    <?php if($auth->role->name == 'Admin' && $auth->username != $user->username): ?>

		<?=$this->form->select('role_id', $role_list); ?>

	<?php endif; ?>

	<?php if($auth->role->name != 'Admin' || $auth->username == $user->username): ?>
	<?=$this->form->label('role_name', 'Role'); ?>
    <input type="text" name="role_name" disabled="disabled" value="<?=$auth->role->name?>">
	<?php endif; ?>

    <fieldset>
    <?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
    <?=$this->html->link('Cancel','/users/view/'.$user->username, array('class' => 'btn')); ?>
    </fieldset>
<?=$this->form->end(); ?>
</div>

<?php if($auth->role->name == 'Admin' && $auth->username != $user->username): ?>

	<div class="well">

			<legend>Edit</legend>

			<?php if($user->active): ?>
			<a class="btn btn-danger" data-toggle="modal" href="#deleteModal">
				<i class="icon-white icon-ban-circle"></i> Disable Account
			</a>
			<?php else: ?>
			<a class="btn btn-success" data-toggle="modal" href="#activateModal">
				<i class="icon-white icon-ok-sign"></i> Activate Account
			</a>
			<?php endif; ?>

	</div>


<div class="modal fade hide" id="deleteModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Disable User</h3>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to remove <strong><?=$user->name; ?>'s</strong> access?</p>

			<p>By selecting <code>Disable</code> you will de-activate this user's account. Are you sure you want to continue?</p>
			</div>
			<div class="modal-footer">
			<?=$this->form->create($user, array('url' => "/users/delete/$user->username", 'method' => 'post')); ?>
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
			<?=$this->form->submit('Disable', array('class' => 'btn btn-danger')); ?>
			<?=$this->form->end(); ?>
	</div>
</div>

<div class="modal fade hide" id="activateModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Activate User</h3>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to enable <strong><?=$user->name; ?>'s</strong> access?</p>

			<p>By selecting <code>Activate</code> you will enable this user's account. Are you sure you want to continue?</p>
			</div>
			<div class="modal-footer">
			<?=$this->form->create($user, array('url' => "/users/activate/$user->username", 'method' => 'post')); ?>
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
			<?=$this->form->submit('Activate', array('class' => 'btn btn-success')); ?>
			<?=$this->form->end(); ?>
	</div>
</div>
<?php endif; ?>
