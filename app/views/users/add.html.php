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

<div class="well">
<?=$this->form->create($user); ?>
    <?=$this->form->field('username', array('autocomplete' => 'off'));?>
    <?=$this->form->field('password', array('type' => 'password')); ?>
    <?=$this->form->field('name', array('autocomplete' => 'off'));?>
    <?=$this->form->field('email', array('autocomplete' => 'off'));?>
    
    <?=$this->form->label('role', 'Role'); ?>
    
		<select name="role_id">
			<?php foreach($roles as $role): ?>
			<option value="<?=$role->id ?>"><?=$role->name ?></option>
			<?php endforeach; ?>
		</select>
    
    <fieldset>
    <?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
    <?=$this->html->link('Cancel','/users', array('class' => 'btn')); ?>
    </fieldset>
<?=$this->form->end(); ?>
</div>
