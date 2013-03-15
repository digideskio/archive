<?php 

$this->title('Add a Project');

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
	<?=$this->html->link('Architecture','/architectures'); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		Add a Project
	</li>

	</ul>

</div>

<div class="actions">
	<ul class="nav nav-tabs">
		<li>
			<?=$this->html->link('Index','/architectures'); ?>
		</li>

	</ul>

	<div class="btn-toolbar">
			<a class="btn btn-inverse disabled" href="#"><i class="icon-plus-sign icon-white"></i> Add a Project</a>
	</div>
</div>

<div class="well">
<?=$this->form->create($architecture); ?>
	<legend>Project Info</legend>
    <?=$this->form->field('architect', array('autocomplete' => 'off'));?>
    <?=$this->form->field('title', array('autocomplete' => 'off'));?>
    <?=$this->form->field('client', array('autocomplete' => 'off'));?>
    <?=$this->form->field('project_lead', array('autocomplete' => 'off'));?>
    <?=$this->form->field('consultants', array('autocomplete' => 'off'));?>
    <?=$this->form->field('remarks', array('type' => 'textarea'));?>
	<?=$this->form->field('area', array(
		'autocomplete' => 'off',
		'label' => "Area (square meters)"
	));?>
    <?=$this->form->field('materials', array('autocomplete' => 'off'));?>
    <?=$this->form->field('earliest_date', array('autocomplete' => 'off', 'label' => 'Design Date'));?>
    <?=$this->form->field('latest_date', array('autocomplete' => 'off', 'label' => 'Completion Date'));?>
    <?=$this->form->field('status', array('autocomplete' => 'off', 'label' => 'Project Status'));?>
    <?=$this->form->field('location', array('autocomplete' => 'off'));?>
    <?=$this->form->field('city', array('autocomplete' => 'off'));?>
    <?=$this->form->field('country', array('autocomplete' => 'off'));?>
    <?=$this->form->field('annotation', array('type' => 'textarea'));?>
    <?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
    <?=$this->html->link('Cancel','/architectures', array('class' => 'btn')); ?>
<?=$this->form->end(); ?>
</div>
