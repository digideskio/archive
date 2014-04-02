<?php

$this->title('Add a Link');

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
	<?=$this->html->link('Links','/Links'); ?>
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
			<?=$this->html->link('Index','/links'); ?>
		</li>

		<li>
			<?=$this->html->link('Search','/links/search'); ?>
		</li>

	</ul>

	<div class="btn-toolbar">
			<a class="btn btn-inverse disabled" href="#"><i class="icon-plus-sign icon-white"></i> Add a Link</a>
	</div>
</div>

<div class="well">
<?=$this->form->create($link); ?>
	<legend>Link</legend>
    <?=$this->form->field('title');?>
    <?=$this->form->field('url', array('label' => 'URL'));?>
    <?=$this->form->field('description', array('type' => 'textarea'));?>
    <?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
    <?=$this->html->link('Cancel','/links', array('class' => 'btn')); ?>
<?=$this->form->end(); ?>
</div>
