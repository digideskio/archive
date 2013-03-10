<?php

$this->title($publication->title);

$this->form->config(
    array( 
        'templates' => array( 
            'error' => '<div class="help-inline">{:content}</div>' 
        )
    )
); 

$language_list = json_encode($language_names);

?>

<div id="location" class="row-fluid">

    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Publications','/publications'); ?>
	<span class="divider">/</span>
	</li>

	<li>
	<?=$this->html->link($publication->title,'/publications/view/'.$publication->archive->slug); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		Edit
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li><?=$this->html->link('View','/publications/view/'.$publication->archive->slug); ?></li>
	<li class="active">
		<a href="#">
			Edit
		</a>
	</li>
	<li>
		<?=$this->html->link('History', $this->url(array('Publications::history', 'slug' => $publication->archive->slug))); ?>
	</li>
</ul>

<div class="row">

	<div class="span5">
		<div class="well">
		<?=$this->form->create($publication); ?>
			<legend>Info</legend>
			
			<?php $pub_classes_list = array_merge(array('' => 'Choose one...'), $pub_classes_list); ?>

			<?=$this->form->label('type', 'Category'); ?>
			<?=$this->form->select('type', $pub_classes_list); ?>
	
			<span class="help-block">Is the publication an interview?</span>
			
			<label class="checkbox">
			<?=$this->form->checkbox('type', array('value' => 'Interview', 'checked' => $publication->archive->type == 'Interview' ));?> Interview
			</label>

			<?=$this->form->field('author');?>
			<?=$this->form->field('title');?>
			<?=$this->form->field('editor');?>
			<?=$this->form->field('publisher');?>
			<?=$this->form->field('earliest_date', array('value' => $publication->archive->start_date_formatted()));?>
			<?=$this->form->field('latest_date', array('value' => $publication->archive->end_date_formatted()));?>
			<?=$this->form->field('pages');?>
			<?=$this->form->field('subject');?>
			<?=$this->form->field('remarks', array('type' => 'textarea'));?>
			<?=$this->form->field('language', array('autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $language_list));?>
			<?=$this->form->field('storage_location');?>
			<?=$this->form->field('storage_number');?>
			<?=$this->form->field('publication_number', array('label' => 'Publication ID'));?>
			<?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
			<?=$this->html->link('Cancel','/publications/view/' . $publication->archive->slug, array('class' => 'btn')); ?>
		<?=$this->form->end(); ?>
		</div>


				
		<div class="well">

			<legend>Edit</legend>

			<a class="btn btn-danger" data-toggle="modal" href="#deleteModal">
				<i class="icon-white icon-trash"></i> Delete Publication
			</a>

		</div>
	</div>

	<div class="span5">

	<?=$this->partial->archives_links_edit(array(
		'model' => $publication,
		'junctions' => $publication_links,
	)); ?>		

	<?=$this->partial->archives_documents_edit(array(
		'model' => $publication,
		'junctions' => $publication_documents,
	)); ?>		

<div class="modal fade hide" id="deleteModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Delete Publication</h3>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to permanently delete <strong><?=$publication->title; ?></strong>?</p>
			
			<p>By selecting <code>Delete</code>, you will remove this Publication from the listings. Are you sure you want to continue?</p>
			</div>
			<div class="modal-footer">
			<?php $slug = $publication->archive->slug; ?>
			<?=$this->form->create($publication, array('url' => "/publications/delete/$slug", 'method' => 'post')); ?>
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
			<?=$this->form->submit('Delete', array('class' => 'btn btn-danger')); ?>
			<?=$this->form->end(); ?>
	</div>
</div>
