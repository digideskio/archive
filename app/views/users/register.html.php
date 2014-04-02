<?php

 $this->title('Registration');

?>

<div id="header">
	<h1>Welcome to the Archive.</h1>
	<h2>Create Your Admin Account.</h2>
</div>

<?=$this->form->create(null); ?>
    <?=$this->form->field('username', array('autocomplete' => 'off'));?>
    <?=$this->form->field('password', array('type' => 'password')); ?>
    <?=$this->form->field('name');?>
    <?=$this->form->field('email');?>
    <?=$this->form->hidden('role_id', array('value' => '1'));?>
    <?=$this->form->submit('Register'); ?>
<?=$this->form->end(); ?>
