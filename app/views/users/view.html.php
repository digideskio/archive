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

<div id="tools" class="btn-toolbar">

<?php if($auth->role->name == 'Admin' && $auth->username != $user->username): ?>

	<div class="action btn-group">

		<a class="btn btn-inverse" href="/users/edit/<?=$user->username ?>">
			<i class="icon-pencil icon-white"></i> Edit User
		</a>
		<a class="btn btn-inverse dropdown-toggle" data-toggle="dropdown" href="">
			<span class="caret"></span>
		</a>
		<ul class="dropdown-menu">
			<li>
				<a href="/users/edit/<?=$user->username ?>">
					<i class="icon-pencil"></i> Edit
				</a>
			</li>
			<li>
				<a data-toggle="modal" href="#deleteModal">
					<i class="icon-trash"></i> Delete
				</a>
			</li>
		</ul>

	</div>
	
<?php endif; ?>

<?php if($auth->username == $user->username): ?>

	<div class="action btn-group">

		<a class="btn btn-inverse" href="/users/edit/<?=$user->username ?>">
			<i class="icon-pencil icon-white"></i> Edit Profile
		</a>

	</div>

<?php endif; ?>

</div>


<h1><?=$user->name ?></h1>

<h5><?=$user->email ?></h5>

<h6><?=$user->role->name ?></h6>



<div class="modal fade hide" id="deleteModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Delete Collection</h3>
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
