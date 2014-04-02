<?php

$this->title($architecture->archive->name);

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
	<?=$this->html->link($architecture->archive->name,'/architectures/view/'.$architecture->archive->slug); ?>
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
		<?=$this->form->create(compact('archive', 'architecture')); ?>
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
			<?=$this->form->field('archive.earliest_date', array(
				'autocomplete' => 'off',
				'label' => 'Design Date',
				'value' => $archive->start_date_formatted()
			));?>
			<?=$this->form->field('archive.latest_date', array(
				'autocomplete' => 'off',
				'label' => 'Completion Date',
				'value' => $archive->end_date_formatted()
			));?>
			<?=$this->form->field('architecture.status', array('autocomplete' => 'off', 'label' => 'Project Status'));?>
			<?=$this->form->field('architecture.location', array('autocomplete' => 'off', 'label' => 'Location'));?>
			<?=$this->form->field('architecture.city', array('autocomplete' => 'off', 'label' => 'City'));?>
			<?=$this->form->field('architecture.country', array('autocomplete' => 'off', 'label' => 'Country'));?>
			<?=$this->form->field('architecture.annotation', array('type' => 'textarea', 'label' => 'Annotation'));?>
			<div class="control-group" style="margin-bottom: 0">
				<label class="control-label">Published</label>

				<div class="controls">
					<label class="checkbox">
						<?=$this->form->checkbox('archive.published');?>
					</label>
				</div>
			</div>
			<?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
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

	<?=$this->partial->archives_documents_edit(array(
		'model' => $architecture,
		'archives_documents' => $archives_documents,
	)); ?>

	</div>

<div class="modal fade hide" id="deleteModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Delete Project</h3>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to permanently delete <strong><?=$architecture->archive->name; ?></strong>?</p>

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
