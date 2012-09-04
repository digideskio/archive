<?php 

$this->title($user->username);

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

		<?php if($auth->role->name == 'Admin' || $auth->username == $user->username): ?>
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
