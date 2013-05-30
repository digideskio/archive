<?php

 $this->title('Login'); 
 
?>

<?=$this->form->create(null); ?>
    <?=$this->form->field('username'); ?>
    <?=$this->form->field('password', array('type' => 'password')); ?>
    <?=$this->form->submit('Log in'); ?>
<?=$this->form->end(); ?>

<?php if ($message): ?>

	<p><?=$message; ?></p>

<?php endif; ?>
