<?php

$this->title($publication->title);

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

$pub_classes_list = array_combine($pub_classifications, $pub_classifications);
$pub_types_list = array_combine($pub_types, $pub_types);

$location_list = json_encode($locations);

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

<div class="actions">
	<ul class="nav nav-tabs">
		<li><?=$this->html->link('View','/publications/view/'.$publication->archive->slug); ?></li>
		<li class="active">
			<a href="#">
				Edit
			</a>
		</li>
		<li><?=$this->html->link('Attachments','/publications/attachments/'.$publication->archive->slug); ?></li>
		<li>
			<?=$this->html->link('History', $this->url(array('Publications::history', 'slug' => $publication->archive->slug))); ?>
		</li>
	</ul>

	<div class="btn-toolbar">
			<a class="btn btn-danger" data-toggle="modal" href="#deleteModal">
				<i class="icon-white icon-trash"></i> Delete Publication
			</a>
	</div>
</div>

<div class="row">
<?=$this->form->create($publication, array('id' => 'PublicationsForm', 'class' => 'form-horizontal')); ?>

	<div class="span5">
		<div class="well">
			<legend>Publication Info</legend>
			
			<?=$this->form->field('author', array('autocomplete' => 'off'));?>
			<?=$this->form->field('title', array('autocomplete' => 'off'));?>
			<?=$this->form->field('remarks', array('autocomplete' => 'off', 'type' => 'textarea'));?>
			<?=$this->form->field('storage_location', array('autocomplete' => 'off'));?>
			<?=$this->form->field('storage_number', array('autocomplete' => 'off'));?>
			<?=$this->form->field('publication_number', array('autocomplete' => 'off', 'label' => 'Publication ID'));?>

		</div>

		<div class="well">
			<?=$this->form->submit('Save', array('class' => 'btn btn-large btn-block btn-primary')); ?>
		</div>

	</div>

	<div class="span5">
		<div class="well">
			<legend>Details</legend>

			<?php $pub_classes_list = array_merge(array('' => 'Choose one...'), $pub_classes_list); ?>

			<div class="control-group">
				<?=$this->form->label('classification', 'Category', array('class' => 'control-label')); ?>
				<div class="controls">
					<?=$this->form->select('classification', $pub_classes_list, array('value' => $publication->archive->classification)); ?>
				</div>
			</div>

			<?php $pub_types_list = array_merge(array('' => 'Choose one...'), $pub_types_list); ?>

			<div class="control-group">
				<?=$this->form->label('type', 'Type', array('class' => 'control-label')); ?>
				<div class="controls">
					<?=$this->form->select('type', $pub_types_list, array('value' => $publication->archive->type)); ?>
				</div>
			</div>

			<?=$this->form->field('book_title', array('autocomplete' => 'off', 'placeholder' => 'Book the essay is in....', 'class' => 'essay'));?>
			<?=$this->form->field('publisher', array('autocomplete' => 'off'));?>
			<?=$this->form->field('location', array('autocomplete' => 'off', 'label' => 'Publisher Location', 'placeholder' => 'City, Country', 'class' => 'book', 'data-provide' => 'typeahead', 'data-source' => $location_list));?>
			<?=$this->form->field('earliest_date', array('autocomplete' => 'off', 'value' => $publication->archive->start_date_formatted()));?>
			<?=$this->form->field('latest_date', array('autocomplete' => 'off', 'class' => 'journal', 'value' => $publication->archive->end_date_formatted()));?>
			<?=$this->form->field('number', array('autocomplete' => 'off', 'class' => 'journal', 'placeholder' => 'Issue number'));?>
			<?=$this->form->field('volume', array('autocomplete' => 'off', 'placeholder' => 'e.g., Spring 2008', 'class' => 'journal'));?>
			<?=$this->form->field('editor', array('autocomplete' => 'off', 'class' => 'book'));?>
			<?=$this->form->field('translator', array('autocomplete' => 'off', 'class' => 'book'));?>
			<?=$this->form->field('pages', array('autocomplete' => 'off', 'class' => 'pages'));?>
			<?=$this->form->field('edition', array('autocomplete' => 'off', 'class' => 'book'));?>
			<?=$this->form->field('url', array('autocomplete' => 'off', 'label' => 'URL', 'placeholder' => 'http://...', 'class' => 'web'));?>
			<?=$this->form->field('access_date', array('autocomplete' => 'off', 'value' => $publication->access_date, 'class' => 'web'));?>
			<?=$this->form->field('subject', array('autocomplete' => 'off'));?>
			<?=$this->form->field('language', array('autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $language_list));?>
			
		</div>
	</div>

<?=$this->form->end(); ?>
</div>

<script>

$(document).ready(function() {

	$('#PublicationsForm .book').closest('.control-group').hide();
	$('#PublicationsForm .essay').closest('.control-group').hide();
	$('#PublicationsForm .web').closest('.control-group').hide();
	$('#PublicationsForm .journal').closest('.control-group').hide();
	$('#PublicationsForm .pages').closest('.control-group').hide();

	$("#PublicationsForm label[for='PublicationsEarliestDate']").html('Date');
	
	function handleFields() {
		var pub = $('#PublicationsClassification').val();

		if (pub == 'Monograph' || pub == 'Catalogue' || pub == "Artist's Book" || pub == "Other") {
			$('#PublicationsForm .book').closest('.control-group').fadeIn();
		} else {
			$('#PublicationsForm .book').closest('.control-group').hide();
		}

		if (pub == 'Monograph' || pub == 'Catalogue' || pub == "Artist's Book" || pub == 'Essay in Book') { 
			$('#PublicationsForm #PublicationsEarliestDate').attr('placeholder', 'Year of Publication');
			$("#PublicationsForm label[for='PublicationsPublisher']").html('Publisher'); 	
		}

		if (pub == 'Newspaper') {
			$("#PublicationsForm label[for='PublicationsPublisher']").html('Newspaper');
		}

		if (pub == 'Journal' || pub == 'Magazine') {
			$('#PublicationsForm .journal').closest('.control-group').fadeIn();
			$('#PublicationsForm #PublicationsEarliestDate').attr('placeholder', 'Month and Year');
			$("#PublicationsForm label[for='PublicationsEarliestDate']").html('Earliest Date');
			$("#PublicationsForm label[for='PublicationsPublisher']").html('Journal or Magazine');
		} else {
			$('#PublicationsForm .journal').closest('.control-group').hide();
			$("#PublicationsForm label[for='PublicationsEarliestDate']").html('Date');
		}

		if (pub == 'Newspaper' || pub == 'Website' || pub == "Other") {
			$('#PublicationsForm .web').closest('.control-group').fadeIn();
			$('#PublicationsForm #PublicationsEarliestDate').attr('placeholder', 'YYYY-MM-DD');
		} else {
			$('#PublicationsForm .web').closest('.control-group').hide();
		}

		if (pub == 'Essay in Book') {
			$('#PublicationsForm .essay').closest('.control-group').fadeIn();
		} else {
			$('#PublicationsForm .essay').closest('.control-group').hide();
		}

		if (pub == 'Monograph' || pub == 'Catalogue' || pub == "Artist's Book" || pub == 'Newspaper' || pub == 'Essay in Book' || pub == "Other") {
			$('#PublicationsForm .pages').closest('.control-group').fadeIn();
		} else {
			$('#PublicationsForm .pages').closest('.control-group').hide();
		}

		if (pub == 'Website') {
			$("#PublicationsForm label[for='PublicationsPublisher']").html('Website Name');
		} 

		if (pub == '') {
			$("#PublicationsForm label[for='PublicationsPublisher']").html('Publisher');
		}

	}


	$('#PublicationsClassification').change(function() {
		handleFields();
	});

	handleFields();

});

</script>

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
