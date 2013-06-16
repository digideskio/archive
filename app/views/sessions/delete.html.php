<?php

 $this->title('Logout'); 
 
?>

<div class="login">
	<div class="login-screen">
		<div class="row">
			<div class="span2 offset2">
				<div class="login-icon text-center">
					<img src="img/Map@2x.png" alt="Welcome to the Archive" />
					<h4>You have left <small>the Archive</small></h4>
				</div>
			</div>

			<div class="span6">
				<?=$this->form->create(null, array('class' => 'login-form', 'action' => 'add')); ?>
    				<?=$this->form->submit('Login', array('class' => 'btn btn-danger btn-large btn-block')); ?>
				<?=$this->form->end(); ?>
			</div>
		</div>
	</div>
</div>
