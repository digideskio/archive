<?php

 $this->title('Login'); 
 
?>

<?=$this->form->create(); ?>
    <?=$this->form->field('username'); ?>
    <?=$this->form->field('password', array('type' => 'password')); ?>
	<?=$this->form->hidden('path', array('value' =>  $path)); ?>
    <?=$this->form->submit('Log in'); ?>
<?=$this->form->end(); ?>

<?php if ($message): ?>

	<p><?=$message; ?></p>

<?php endif; ?>
