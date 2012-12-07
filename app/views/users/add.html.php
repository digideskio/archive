<?php 

$this->title('Add a User');

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
	
	<li class="active">
		Add
	</li>

	</ul>

</div>

<div class="actions">
		
	<ul class="nav nav-tabs">
		<li>
			<?=$this->html->link('Index','/users'); ?> 
		</li>
	</ul>

	<div class="btn-toolbar">

		<div class="action btn-group">

			<a class="btn btn-inverse btn-disabled" disabled="disabled" href="/users/add/">
				<i class="icon-plus-sign icon-white"></i> Add User
			</a>

		</div>

	</div>
</div>

<div class="well">
<?=$this->form->create($user); ?>
    <?=$this->form->field('username', array('autocomplete' => 'off'));?>
    <?=$this->form->field('password', array('type' => 'password')); ?>
    <?=$this->form->field('name', array('autocomplete' => 'off'));?>
    <?=$this->form->field('email', array('autocomplete' => 'off'));?>
	<?= $this->form->select('role_id', $role_list) ?>
    
    <fieldset>
    <?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
    <?=$this->html->link('Cancel','/users', array('class' => 'btn')); ?>
    </fieldset>
<?=$this->form->end(); ?>
</div>
