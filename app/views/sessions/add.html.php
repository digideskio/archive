<?php

 $this->title('Login');

?>

<div class="login">
	<div class="login-screen">
		<div class="row">
			<div class="span2 offset2">
				<div class="login-icon text-center">
					<img src="img/Map@2x.png" alt="Welcome" />
					<h4 style="text-align: center;"><small>Welcome</small></h4>
				</div>
			</div>

			<div class="span6">
				<?=$this->form->create(null, array('class' => 'login-form')); ?>
					<div class="control-group">
    					<?=$this->form->field('username', array('label' => false, 'class' => 'login-field', 'placeholder' => 'Username')); ?>
						<label class="login-field-icon fui-user" for="Username"></label>
					</div>

					<div class="control-group">
    					<?=$this->form->field('password', array('type' => 'password', 'label' => false, 'class' => 'login-field', 'placeholder' => 'Password')); ?>
						<label class="login-field-icon fui-lock" for="Password"></label>
					</div>

					<?=$this->form->hidden('path', array('value' =>  $path)); ?>
    				<?=$this->form->submit('Login', array('class' => 'btn btn-danger btn-large btn-block')); ?>
					<?=$this->form->end(); ?>
<?php if ($message): ?>

	<p><?=$message; ?></p>

<?php endif; ?>
			</div>
		</div>
	</div>
</div>


