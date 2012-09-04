<?php 

$this->title('Add a Collection');

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
	<?=$this->html->link('Collections','/collections'); ?>
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
			<?=$this->html->link('Index','/collections'); ?>
		</li>

	</ul>

	<div class="btn-toolbar">
			<a class="btn btn-inverse disabled" href="#"><i class="icon-plus-sign icon-white"></i> Add a Collection</a>
	</div>
</div>

<div class="well">
<?=$this->form->create($collection); ?>
	<legend>Collection Info</legend>
    <?=$this->form->field('title');?>
    <?=$this->form->field('description', array('type' => 'textarea'));?>
    <?=$this->form->hidden('class'); ?>
    <?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
    <?=$this->html->link('Cancel','/collections', array('class' => 'btn')); ?>
<?=$this->form->end(); ?>
</div>
