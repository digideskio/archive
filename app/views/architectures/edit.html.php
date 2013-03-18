<?php

$this->title($architecture->title);

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

	<li>
	<?=$this->html->link($architecture->title,'/architectures/view/'.$architecture->archive->slug); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		Edit
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li><?=$this->html->link('View','/architectures/view/'.$architecture->archive->slug); ?></li>
	<li class="active">
		<a href="#">
			Edit
		</a>
	</li>
	<li>
		<?=$this->html->link('History', $this->url(array('Architectures::history', 'slug' => $architecture->archive->slug))); ?>
	</li>
</ul>


<div class="row">

	<div class="span5">
		<div class="well">
		<?=$this->form->create($architecture); ?>
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
			<?=$this->form->field('earliest_date', array(
				'autocomplete' => 'off',
				'label' => 'Design Date',
				'value' => $architecture->archive->start_date_formatted()
			));?>
			<?=$this->form->field('latest_date', array(
				'autocomplete' => 'off',
				'label' => 'Completion Date',
				'value' => $architecture->archive->end_date_formatted()
			));?>
			<?=$this->form->field('status', array('autocomplete' => 'off', 'label' => 'Project Status'));?>
			<?=$this->form->field('location', array('autocomplete' => 'off'));?>
			<?=$this->form->field('city', array('autocomplete' => 'off'));?>
			<?=$this->form->field('country', array('autocomplete' => 'off'));?>
			<?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
			<?=$this->html->link('Cancel','/architectures/view/'.$architecture->archive->slug, array('class' => 'btn')); ?>
		<?=$this->form->end(); ?>
		</div>
		
		<div class="well">

			<legend>Edit</legend>

			<a class="btn btn-danger" data-toggle="modal" href="#deleteModal">
				<i class="icon-white icon-trash"></i> Delete Project
			</a>

		</div>
		
	</div>
	
	<div class="span5">

	<div class="well">
		<legend>Annotation</legend>
		<?=$this->form->create($architecture); ?>
			<?=$this->form->field('annotation', array(
				'type' => 'textarea', 
				'rows' => '10', 
				'style' => 'width:90%;',
				'label' => ''
			));?>
		
			<?=$this->form->hidden('title'); ?>

			<?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
			<?=$this->html->link('Cancel','/architectures/view/'.$architecture->archive->slug, array('class' => 'btn')); ?>
		<?=$this->form->end(); ?>
		
	</div>

	
	<?=$this->partial->archives_documents_edit(array(
		'model' => $architecture,
		'junctions' => $architecture_documents,
	)); ?>		

	</div>

<div class="modal fade hide" id="deleteModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Delete Project</h3>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to permanently delete <strong><?=$architecture->title; ?></strong>?</p>
			
			<p>By selecting <code>Delete</code>, you will remove this Project from the listings. Are you sure you want to continue?</p>
			</div>
			<div class="modal-footer">
			<?php $slug = $architecture->archive->slug; ?>
			<?=$this->form->create($architecture, array('url' => "/architectures/delete/$slug", 'method' => 'post')); ?>
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
			<?=$this->form->submit('Delete', array('class' => 'btn btn-danger')); ?>
			<?=$this->form->end(); ?>
	</div>
</div>
