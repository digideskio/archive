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

$pub_classes_list = array_combine($pub_classifications, $pub_classifications);
$pub_types_list = array_combine($pub_types, $pub_types);

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

		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">Filter <b class="caret"></b></a>
			<ul class="dropdown-menu">
		<?php foreach($pub_classifications as $pc): ?>
			<li>
				<?=$this->html->link($pc,'/publications?classification='.$pc); ?> 
			</li>
		<?php endforeach; ?>
			<li class="divider"></li>
		<?php foreach($pub_types as $pt): ?>
			<li>
				<?=$this->html->link($pt,'/publications?type='.$pt); ?>
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
<?=$this->form->create(compact('publication'), array('id' => 'PublicationsForm', 'class' => 'form-horizontal')); ?>

	<div class="span5">
		<div class="well">
			<legend>Publication Info</legend>
			
			<?=$this->form->field('publication.author', array('label' => 'Author', 'autocomplete' => 'off'));?>
			<?=$this->form->field('publication.title', array('label' => 'Title', 'autocomplete' => 'off'));?>
			<?=$this->form->field('publication.remarks', array('label' => 'Remarks', 'autocomplete' => 'off', 'type' => 'textarea'));?>
			<?=$this->form->field('publication.storage_location', array('label' => 'Storage Location', 'autocomplete' => 'off'));?>
			<?=$this->form->field('publication.storage_number', array('label' => 'Storage Number', 'autocomplete' => 'off'));?>
			<?=$this->form->field('publication.publication_number', array('Publication Number', 'autocomplete' => 'off', 'label' => 'Publication ID'));?>

			<div class="control-group" id="DocumentsGroup" style="display:none;">
				<?=$this->form->label('documents', 'Documents', array('class' => 'control-label')); ?>
				<div class="controls">
					<select name="documents[]" id="ArchivesDocuments" multiple="multiple" style="max-width:100%">
					<?php foreach ($documents as $doc): ?>
						<option value="<?=$doc->id ?>" selected="selected"><?=$doc->title ?></option>
					<?php endforeach; ?>
					</select>

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
				<?=$this->form->label('publication.classification', 'Category', array('class' => 'control-label')); ?>
				<div class="controls">
					<?=$this->form->select('publication.classification', $pub_classes_list, array('id' => 'PublicationsClassification')); ?>
				</div>
			</div>

			<?php $pub_types_list = array_merge(array('' => 'Choose one...'), $pub_types_list); ?>

			<div class="control-group">
				<?=$this->form->label('publication.type', 'Type', array('class' => 'control-label')); ?>
				<div class="controls">
					<?=$this->form->select('publication.type', $pub_types_list); ?>
				</div>
			</div>

			<?=$this->form->field('publication.book_title', array('label' => 'Book Title', 'autocomplete' => 'off', 'placeholder' => 'Book the essay is in....', 'class' => 'essay'));?>
			<?=$this->form->field('publication.publisher', array('label' => 'Publisher', 'autocomplete' => 'off'));?>
			<?=$this->form->field('publication.location', array('autocomplete' => 'off', 'label' => 'Publisher Location', 'placeholder' => 'City, Country', 'class' => 'book', 'data-provide' => 'typeahead', 'data-source' => $location_list));?>
			<?=$this->form->field('publication.earliest_date', array('label' => 'Earliest Date', 'autocomplete' => 'off'));?>
			<?=$this->form->field('publication.latest_date', array('label' => 'Latest Date', 'autocomplete' => 'off', 'class' => 'journal'));?>
			<?=$this->form->field('publication.number', array('label' => 'Number', 'autocomplete' => 'off', 'class' => 'journal', 'placeholder' => 'Issue number'));?>
			<?=$this->form->field('publication.volume', array('label' => 'Volume', 'autocomplete' => 'off', 'placeholder' => 'e.g., Spring 2008', 'class' => 'journal'));?>
			<?=$this->form->field('publication.editor', array('label' => 'Editor', 'autocomplete' => 'off', 'class' => 'book'));?>
			<?=$this->form->field('publication.translator', array('label' => 'Translator', 'autocomplete' => 'off', 'class' => 'book'));?>
			<?=$this->form->field('publication.pages', array('label' => 'Pages', 'autocomplete' => 'off', 'class' => 'pages'));?>
			<?=$this->form->field('publication.edition', array('label' => 'Edition', 'autocomplete' => 'off', 'class' => 'book'));?>
			<?=$this->form->field('publication.url', array('autocomplete' => 'off', 'label' => 'URL', 'placeholder' => 'http://...', 'class' => 'web'));?>
			<?=$this->form->field('publication.access_date', array('label' => 'Access Date', 'autocomplete' => 'off', 'value' => $access_date, 'class' => 'web'));?>
			<?=$this->form->field('publication.subject', array('label' => 'Subject', 'autocomplete' => 'off'));?>
			<?=$this->form->field('publication.language', array('label' => 'Langauge', 'autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $language_list));?>
			
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
