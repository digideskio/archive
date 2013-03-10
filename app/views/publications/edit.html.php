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
	<?=$this->form->create($publication, array('id' => 'PublicationsForm')); ?>
		<legend>Publication Info</legend>

		<?php $pub_classes_list = array_merge(array('' => 'Choose one...'), $pub_classes_list); ?>

		<?=$this->form->label('classification', 'Category'); ?>
		<?=$this->form->select('classification', $pub_classes_list, array('value' => $publication->archive->classification)); ?>

		<div class="interview">
			<span class="help-block">Is the publication an interview?</span>
			
			<label class="checkbox">
			<?=$this->form->checkbox('type', array('value' => 'Interview', 'checked' => $publication->archive->type == 'Interview' ));?> Interview
			</label>
		</div>
		
		<?=$this->form->field('author', array('autocomplete' => 'off'));?>
		<?=$this->form->field('title', array('autocomplete' => 'off'));?>
		<?=$this->form->field('book_title', array('autocomplete' => 'off', 'placeholder' => 'Book the essay is in....', 'class' => 'essay'));?>
		<?=$this->form->field('publisher', array('autocomplete' => 'off'));?>
		<?=$this->form->field('location', array('autocomplete' => 'off', 'label' => 'Publisher Location', 'placeholder' => 'City, Country', 'class' => 'book'));?>
		<?=$this->form->field('earliest_date', array('autocomplete' => 'off', 'value' => $publication->archive->start_date_formatted()));?>
		<?=$this->form->field('latest_date', array('autocomplete' => 'off', 'class' => 'journal', 'value' => $publication->archive->end_date_formatted()));?>
		<?=$this->form->field('number', array('autocomplete' => 'off', 'class' => 'journal', 'placeholder' => 'Issue number'));?>
		<?=$this->form->field('volume', array('autocomplete' => 'off', 'placeholder' => 'e.g., Spring 2008', 'class' => 'journal'));?>
		<?=$this->form->field('editor', array('autocomplete' => 'off', 'class' => 'book'));?>
		<?=$this->form->field('translator', array('autocomplete' => 'off', 'class' => 'book'));?>
		<?=$this->form->field('pages', array('autocomplete' => 'off', 'class' => 'pages'));?>
		<?=$this->form->field('edition', array('autocomplete' => 'off', 'class' => 'book'));?>
		<?=$this->form->field('access_date', array('autocomplete' => 'off', 'value' => $access_date, 'class' => 'web'));?>
		<?=$this->form->field('subject', array('autocomplete' => 'off'));?>
		<?=$this->form->field('remarks', array('autocomplete' => 'off', 'type' => 'textarea'));?>
		<?=$this->form->field('language', array('autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $language_list));?>
		<?=$this->form->field('storage_location', array('autocomplete' => 'off'));?>
		<?=$this->form->field('storage_number', array('autocomplete' => 'off'));?>
		<?=$this->form->field('publication_number', array('autocomplete' => 'off', 'label' => 'Publication ID'));?>
		<?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
		<?=$this->html->link('Cancel','/publications/view/' . $publication->archive->slug, array('class' => 'btn')); ?>
	<?=$this->form->end(); ?>
	</div>

<script>

$(document).ready(function() {

	$('#PublicationsForm .book').parent().hide();
	$('#PublicationsForm .essay').parent().hide();
	$('#PublicationsForm .web').parent().hide();
	$('#PublicationsForm .journal').parent().hide();
	$('#PublicationsForm .pages').parent().hide();
	$('#PublicationsForm .interview').hide();

	$("#PublicationsForm label[for='PublicationsEarliestDate']").html('Date');
	
	function handleFields() {
		var pub = $('#PublicationsClassification').val();

		if (pub == 'Monograph' || pub == 'Catalogue' || pub == "Artist's Book") {
			$('#PublicationsForm .book').parent().fadeIn();
		} else {
			$('#PublicationsForm .book').parent().hide();
		}

		if (pub == 'Monograph' || pub == 'Catalogue' || pub == "Artist's Book" || pub == 'Essay in Book') { 
			$('#PublicationsForm #PublicationsEarliestDate').attr('placeholder', 'Year of Publication');
			$("#PublicationsForm label[for='PublicationsPublisher']").html('Publisher'); 	
		}

		if (pub == 'Newspaper') {
			$("#PublicationsForm label[for='PublicationsPublisher']").html('Newspaper');
		}

		if (pub == 'Journal' || pub == 'Magazine') {
			$('#PublicationsForm .journal').parent().fadeIn();
			$('#PublicationsForm #PublicationsEarliestDate').attr('placeholder', 'Month and Year');
			$("#PublicationsForm label[for='PublicationsEarliestDate']").html('Earliest Date');
			$("#PublicationsForm label[for='PublicationsPublisher']").html('Journal or Magazine');
		} else {
			$('#PublicationsForm .journal').parent().hide();
			$("#PublicationsForm label[for='PublicationsEarliestDate']").html('Date');
		}

		if (pub == 'Newspaper' || pub == 'Website') {
			$('#PublicationsForm .web').parent().fadeIn(); 
			$('#PublicationsForm #PublicationsEarliestDate').attr('placeholder', 'YYYY-MM-DD');
		} else {
			$('#PublicationsForm .web').parent().hide();
		}

		if (pub == 'Essay in Book') {
			$('#PublicationsForm .essay').parent().fadeIn();
		} else {
			$('#PublicationsForm .essay').parent().hide();
		}

		if (pub == 'Monograph' || pub == 'Catalogue' || pub == "Artist's Book" || pub == 'Newspaper' || pub == 'Essay in Book') {
			$('#PublicationsForm .pages').parent().fadeIn();
		} else {
			$('#PublicationsForm .pages').parent().hide();
		}

		if (pub == 'Website') {
			$("#PublicationsForm label[for='PublicationsPublisher']").html('Website Name');
		} 

		if (pub == 'Journal' || pub == 'Magazine' || pub == 'Website' || pub == 'Newspaper') {
			$('#PublicationsForm .interview').fadeIn(); 
		} else {
			$('#PublicationsForm .interview').hide(); 
		}

	}

	$('#PublicationsClassification').change(function() {
		handleFields();
	});

	handleFields();

});
				
</script>
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
