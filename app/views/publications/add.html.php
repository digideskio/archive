<?php

$this->title("Add a Publication");

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

$location_list = json_encode($locations);

$language_list = json_encode($language_names);

$access_date = $publication->access_date ?: date('Y-m-d');

$documents_list = array();

foreach ($documents as $doc) {
	$documents_list["$doc->id"] = $doc->title;
}

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

		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">Filter <b class="caret"></b></a>
			<ul class="dropdown-menu">
		<?php foreach($pub_classifications as $pc): ?>
			<li>
				<?=$this->html->link($pc,'/publications?classification='.$pc); ?> 
			</li>
		<?php endforeach; ?>
			</ul>
		</li>

		<li>
			<?=$this->html->link('Languages','/publications/languages'); ?>
		</li>
		<li>
			<?=$this->html->link('Subjects','/publications/subjects'); ?>
		</li>
		<li>
			<?=$this->html->link('History','/publications/histories'); ?>
		</li>

		<li>
			<?=$this->html->link('Search','/publications/search'); ?>
		</li>


	</ul>
	<div class="btn-toolbar">
			<a class="btn btn-inverse disabled" href="#"><i class="icon-plus-sign icon-white"></i> Add a Publication</a>
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

			<div class="control-group" id="DocumentsGroup" style="display:none;">
				<?=$this->form->label('documents', 'Documents', array('class' => 'control-label')); ?>
				<div class="controls">
					<?=$this->form->select('documents', $documents_list, array('id' => 'ArchivesDocuments', 'multiple' => true)); ?>
				</div>
			</div>

		</div>

		<?=$this->partial->archives_documents_add(array(
			'documents' => $documents,
		)); ?>		

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
					<?=$this->form->select('classification', $pub_classes_list); ?>
				</div>
			</div>

			<div class="control-group">
				<div class="controls">
					<div class="interview">
						<label class="checkbox">
						<?=$this->form->checkbox('type', array('value' => 'Interview'));?> Publication is an Interview
						</label>
					</div>
				</div>
			</div>

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
	$('#PublicationsForm .interview').closest('.control-group').hide();

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

		if (pub == 'Journal' || pub == 'Magazine' || pub == 'Website' || pub == 'Newspaper') {
			$('#PublicationsForm .interview').closest('.control-group').fadeIn(); 
		} else {
			$('#PublicationsForm .interview').closest('.control-group').hide(); 
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
