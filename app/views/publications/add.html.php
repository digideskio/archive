<?php

$this->title("Add a Publication");

$this->form->config(
    array( 
        'templates' => array( 
            'error' => '<div class="help-inline">{:content}</div>'
        )
    )
); 

$location_list = json_encode($locations);

$language_list = json_encode($language_names);

$access_date = $publication->access_date ?: date('Y-m-d');

?>

<div id="location" class="row-fluid">

    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Publications','/publications'); ?>
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
			<?=$this->html->link('Index','/publications'); ?>
		</li>

	</ul>
	<div class="btn-toolbar">
			<a class="btn btn-inverse disabled" href="#"><i class="icon-plus-sign icon-white"></i> Add a Publication</a>
	</div>
</div>

<div class="well">
<?=$this->form->create($publication, array('id' => 'PublicationsForm')); ?>
	<legend>Publication Info</legend>

	<?php $pub_classes_list = array_merge(array('' => 'Choose one...'), $pub_classes_list); ?>

	<?=$this->form->label('classification', 'Category'); ?>
	<?=$this->form->select('classification', $pub_classes_list); ?>

	<div class="interview">
		<span class="help-block">Is the publication an interview?</span>
		
		<label class="checkbox">
		<?=$this->form->checkbox('type', array('value' => 'Interview'));?> Interview
		</label>
	</div>
	
	<?=$this->form->field('author', array('autocomplete' => 'off'));?>
	<?=$this->form->field('title', array('autocomplete' => 'off'));?>
	<?=$this->form->field('book_title', array('autocomplete' => 'off', 'placeholder' => 'Book the essay is in....', 'class' => 'essay'));?>
	<?=$this->form->field('publisher', array('autocomplete' => 'off'));?>
	<?=$this->form->field('location', array('autocomplete' => 'off', 'label' => 'Publisher Location', 'placeholder' => 'City, Country', 'class' => 'book', 'data-provide' => 'typeahead', 'data-source' => $location_list));?>
	<?=$this->form->field('earliest_date', array('autocomplete' => 'off'));?>
	<?=$this->form->field('latest_date', array('autocomplete' => 'off', 'class' => 'journal'));?>
	<?=$this->form->field('number', array('autocomplete' => 'off', 'class' => 'journal', 'placeholder' => 'Issue number'));?>
	<?=$this->form->field('volume', array('autocomplete' => 'off', 'placeholder' => 'e.g., Spring 2008', 'class' => 'journal'));?>
	<?=$this->form->field('editor', array('autocomplete' => 'off', 'class' => 'book'));?>
	<?=$this->form->field('translator', array('autocomplete' => 'off', 'class' => 'book'));?>
	<?=$this->form->field('pages', array('autocomplete' => 'off', 'class' => 'pages'));?>
	<?=$this->form->field('edition', array('autocomplete' => 'off', 'class' => 'book'));?>
	<?=$this->form->field('url', array('autocomplete' => 'off', 'label' => 'URL', 'placeholder' => 'http://...', 'class' => 'web'));?>
	<?=$this->form->field('access_date', array('autocomplete' => 'off', 'value' => $access_date, 'class' => 'web'));?>
	<?=$this->form->field('subject', array('autocomplete' => 'off'));?>
	<?=$this->form->field('remarks', array('autocomplete' => 'off', 'type' => 'textarea'));?>
	<?=$this->form->field('language', array('autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $language_list));?>
	<?=$this->form->field('storage_location', array('autocomplete' => 'off'));?>
	<?=$this->form->field('storage_number', array('autocomplete' => 'off'));?>
	<?=$this->form->field('publication_number', array('autocomplete' => 'off', 'label' => 'Publication ID'));?>
	<?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
	<?=$this->html->link('Cancel','/publications', array('class' => 'btn')); ?>
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
