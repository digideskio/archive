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

<?=$this->form->create(compact('archive', 'architecture')); ?>
<div class="well">
	<legend>Project Info</legend>
    <?=$this->form->field('architecture.architect', array('autocomplete' => 'off', 'label' => 'Architect'));?>
    <?=$this->form->field('archive.name', array('autocomplete' => 'off', 'label' => 'Title'));?>
    <?=$this->form->field('architecture.client', array('autocomplete' => 'off', 'label' => 'Client'));?>
    <?=$this->form->field('architecture.project_lead', array('autocomplete' => 'off', 'label' => 'Project Lead'));?>
    <?=$this->form->field('architecture.consultants', array('autocomplete' => 'off', 'label' => 'Consultants'));?>
    <?=$this->form->field('architecture.remarks', array('type' => 'textarea', 'label' => 'Remarks'));?>
	<?=$this->form->field('architecture.area', array(
		'autocomplete' => 'off',
		'label' => "Area (square meters)"
	));?>
    <?=$this->form->field('architecture.materials', array('autocomplete' => 'off', 'label' => 'Materials'));?>
    <?=$this->form->field('archive.earliest_date', array('autocomplete' => 'off', 'label' => 'Design Date'));?>
    <?=$this->form->field('archive.latest_date', array('autocomplete' => 'off', 'label' => 'Completion Date'));?>
    <?=$this->form->field('architecture.status', array('autocomplete' => 'off', 'label' => 'Project Status'));?>
    <?=$this->form->field('architecture.location', array('autocomplete' => 'off', 'label' => 'Location'));?>
    <?=$this->form->field('architecture.city', array('autocomplete' => 'off', 'label' => 'City'));?>
    <?=$this->form->field('architecture.country', array('autocomplete' => 'off', 'label' => 'Country'));?>
    <?=$this->form->field('architecture.annotation', array('type' => 'textarea', 'label' => 'Annotation'));?>
</div>
<div class="well">
	<?=$this->form->submit('Save', array('class' => 'btn btn-large btn-block btn-primary')); ?>
</div>
<?=$this->form->end(); ?>
