<?php

$this->title($user->username);

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

<div class="well">
<?=$this->form->create($user); ?>
    <?=$this->form->field('username', array('autocomplete' => 'off', 'disabled' => 'disabled'));?>
    <?=$this->form->field('password', array('type' => 'password')); ?>
    <?=$this->form->field('name', array('autocomplete' => 'off'));?>
    <?=$this->form->field('email', array('autocomplete' => 'off'));?>
    
    <?php if($auth->role->name == 'Admin' && $auth->username != $user->username): ?>
    
    <?=$this->form->label('role_id', 'Role'); ?>
    
		<select name="role_id">
			<?php foreach($roles as $role): ?>
			<option value="<?=$role->id ?>" <?php if ($user->role->id == $role->id) { echo 'selected="selected"'; } ?>>
				<?=$role->name ?>
			</option>
			<?php endforeach; ?>
		</select>
		
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

