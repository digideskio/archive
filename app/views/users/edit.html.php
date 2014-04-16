<?php

	$this->title($user->username);

	$auth = $this->authority->auth();

	$role = $auth->role->name;

    $this->form->config(
        array(
            'label' => array(
                'class' => 'control-label',
            ),
            'field' => array(
                'wrap' => array('class' => 'control-group'),
                'template' => '<div{:wrap}>{:label}<div class="controls control-row">{:input}{:error}</div></div>',
                'style' => 'max-width:100%'
            ),
            'select' => array(
                'style' => 'max-width:100%'
            ),
            'checkbox' => array(
                'wrap' => array('class' => 'control-group'),
            ),
            'templates' => array(
                'error' => '<div class="help-inline">{:content}</div>'
            )
        )
    );

    $role_list = array();

    foreach($roles as $role) {
        $role_list[$role->id] = $role->name;
    }

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

    <?php if($auth->role->name == 'Admin' && $auth->username != $user->username): ?>

        <div class="btn-toolbar">
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

    <?php endif; ?>

</div>

<?php if(!$user->active): ?>

<div class="alert alert-error">
This user is no longer active.
</div>

<?php endif; ?>

<div class="row">

<?=$this->form->create($user, array('class' => 'form-horizontal')); ?>
    <div class="span5">
    <div class="well">
    <legend>User Info</legend>
    <?=$this->form->field('username', array('autocomplete' => 'off', 'disabled' => 'disabled'));?>
    <?=$this->form->field('password', array('type' => 'password')); ?>
    <?=$this->form->field('name', array('autocomplete' => 'off'));?>
    <?=$this->form->field('email', array('autocomplete' => 'off'));?>

    <?php if($auth->role->name == 'Admin' && $auth->username != $user->username): ?>

        <div class="control-group">
        <?=$this->form->label('role_name', 'Role'); ?>
        <div class="controls">
		<?=$this->form->select('role_id', $role_list); ?>
        </div>
        </div>

	<?php endif; ?>

	<?php if($auth->role->name != 'Admin' || $auth->username == $user->username): ?>
    <?=$this->form->field('role_name', array('label' => 'Role', 'disabled' => 'disabled', 'value' => $auth->role->name)); ?>
	<?php endif; ?>
    </div>

    <div class="well">
        <?=$this->form->submit('Save', array('class' => 'btn btn-large btn-block btn-primary')); ?>
    </div>
    </div>
<?=$this->form->end(); ?>
</div>

<?php if($auth->role->name == 'Admin' && $auth->username != $user->username): ?>

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
