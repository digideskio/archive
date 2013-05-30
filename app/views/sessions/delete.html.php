<?php

 $this->title('Logout'); 
 
?>

<?=$this->form->create(null, array('action' => 'add')); ?>
	<h3>You are logged out of the Archive.</h3>
    <?=$this->form->submit('Log in'); ?>
<?=$this->form->end(); ?>
