<?php

$this->title('Add a User');

$this->form->config(
    array(
		'label' => array(
			'class' => 'control-label',
		),
		'field' => array(
			'wrap' => array('class' => 'control-group'),
			'template' => '<div{:wrap}>{:label}<div class="controls control-row">{:input}{:error}</div></div>',
			'style' => 'max-width:100%'
		),
		'select' => array(
			'style' => 'max-width:100%'
		),
		'checkbox' => array(
			'wrap' => array('class' => 'control-group'),
		),
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

<div class="row">

<?=$this->form->create($user, array('class' => 'form-horizontal')); ?>

    <div class="span5">
    <div class="well">
    <legend>User Info</legend>
    <?=$this->form->field('username', array('autocomplete' => 'off'));?>
    <?=$this->form->field('password', array('type' => 'password')); ?>
    <?=$this->form->field('name', array('autocomplete' => 'off'));?>
    <?=$this->form->field('email', array('autocomplete' => 'off'));?>
    <div class="control-group">
	<?=$this->form->label('role_name', 'Role'); ?>
    <div class="controls">
	<?= $this->form->select('role_id', $role_list) ?>
    </div>
    </div>
    </div>

    <div class="well">
        <?=$this->form->submit('Save', array('class' => 'btn btn-large btn-block btn-primary')); ?>
    </div>
    </div>
<?=$this->form->end(); ?>
</div>
