<?php

$this->title($publication->archive->name);

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
	<?=$this->html->link($publication->archive->name,'/publications/view/'.$publication->archive->slug); ?>
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
<?=$this->form->create(compact('archive', 'publication'), array('id' => 'PublicationsForm', 'class' => 'form-horizontal')); ?>

	<div class="span5">
		<div class="well">
			<legend>Publication Info</legend>
			
			<?=$this->form->field('publication.author', array('label' => 'Author', 'autocomplete' => 'off'));?>
			<?=$this->form->field('archive.title', array('label' => 'Title', 'autocomplete' => 'off', 'value' => $archive->name));?>
			<?=$this->form->field('publication.remarks', array('label' => 'Remarks', 'autocomplete' => 'off', 'type' => 'textarea'));?>
			<?=$this->form->field('publication.storage_location', array('Storage Location' => 'Author', 'autocomplete' => 'off'));?>
			<?=$this->form->field('publication.storage_number', array('Storage Number' => 'Author', 'autocomplete' => 'off'));?>
			<?=$this->form->field('publication.publication_number', array('Publication Number' => 'Author', 'autocomplete' => 'off', 'label' => 'Publication ID'));?>

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
				<?=$this->form->label('archive.classification', 'Category', array('class' => 'control-label')); ?>
				<div class="controls">
					<?=$this->form->select('archive.classification', $pub_classes_list, array('value' => $publication->archive->classification)); ?>
				</div>
			</div>

			<?php $pub_types_list = array_merge(array('' => 'Choose one...'), $pub_types_list); ?>

			<div class="control-group">
				<?=$this->form->label('archive.type', 'Type', array('class' => 'control-label')); ?>
				<div class="controls">
					<?=$this->form->select('archive.type', $pub_types_list, array('value' => $publication->archive->type)); ?>
				</div>
			</div>

			<?=$this->form->field('publication.book_title', array('Book Title' => 'Author', 'autocomplete' => 'off', 'placeholder' => 'Book the essay is in....', 'class' => 'essay'));?>
			<?=$this->form->field('publication.publisher', array('label' => 'Publisher', 'autocomplete' => 'off'));?>
			<?=$this->form->field('publication.location', array('label' => 'Publisher Location', 'autocomplete' => 'off', 'placeholder' => 'City, Country', 'class' => 'book', 'data-provide' => 'typeahead', 'data-source' => $location_list));?>
			<?=$this->form->field('archive.earliest_date', array('label' => 'Date', 'autocomplete' => 'off', 'value' => $publication->archive->start_date_formatted()));?>
			<?=$this->form->field('archive.latest_date', array('label' => 'Latest Date', 'autocomplete' => 'off', 'class' => 'journal', 'value' => $publication->archive->end_date_formatted()));?>
			<?=$this->form->field('publication.number', array('label' => 'Number', 'autocomplete' => 'off', 'class' => 'journal', 'placeholder' => 'Issue number'));?>
			<?=$this->form->field('publication.volume', array('label' => 'Volume', 'autocomplete' => 'off', 'placeholder' => 'e.g., Spring 2008', 'class' => 'journal'));?>
			<?=$this->form->field('publication.editor', array('label' => 'Editor', 'autocomplete' => 'off', 'class' => 'book'));?>
			<?=$this->form->field('publication.translator', array('label' => 'Translator', 'autocomplete' => 'off', 'class' => 'book'));?>
			<?=$this->form->field('publication.pages', array('label' => 'Pages', 'autocomplete' => 'off', 'class' => 'pages'));?>
			<?=$this->form->field('publication.edition', array('label' => 'Edition', 'autocomplete' => 'off', 'class' => 'book'));?>
			<div class="control-group">
			<label class="control-label">URL</label>
			<div class="controls">
			<span class="help-block" style="margin-top: 0; padding:5px 6px;">
				See the <?=$this->html->link('attachments', $this->url(array('Publications::attachments', 'slug' => $publication->archive->slug)) . '#links-editor'); ?> page.
			</span>
			</div>
			</div>
			<?=$this->form->field('publication.access_date', array('label' => 'Access Date', 'autocomplete' => 'off', 'value' => $publication->access_date, 'class' => 'web'));?>
			<?=$this->form->field('publication.subject', array('label' => 'Subject', 'autocomplete' => 'off'));?>
			<?=$this->form->field('publication.language', array('label' => 'Language', 'autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $language_list));?>
			
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

	$("#PublicationsForm label[for='ArchivesEarliestDate']").html('Date');
	
	function handleFields() {
		var pub = $('#ArchivesClassification').val();

		if (pub == 'Monograph' || pub == 'Catalogue' || pub == "Artist's Book" || pub == "Other") {
			$('#PublicationsForm .book').closest('.control-group').fadeIn();
		} else {
			$('#PublicationsForm .book').closest('.control-group').hide();
		}

		if (pub == 'Monograph' || pub == 'Catalogue' || pub == "Artist's Book" || pub == 'Essay in Book') { 
			$('#PublicationsForm #ArchivesEarliestDate').attr('placeholder', 'Year of Publication');
			$("#PublicationsForm label[for='PublicationsPublisher']").html('Publisher'); 	
		}

		if (pub == 'Newspaper') {
			$("#PublicationsForm label[for='PublicationsPublisher']").html('Newspaper');
		}

		if (pub == 'Journal' || pub == 'Magazine') {
			$('#PublicationsForm .journal').closest('.control-group').fadeIn();
			$('#PublicationsForm #ArchivesEarliestDate').attr('placeholder', 'Month and Year');
			$("#PublicationsForm label[for='ArchivesEarliestDate']").html('Earliest Date');
			$("#PublicationsForm label[for='PublicationsPublisher']").html('Journal or Magazine');
		} else {
			$('#PublicationsForm .journal').closest('.control-group').hide();
			$("#PublicationsForm label[for='ArchivesEarliestDate']").html('Date');
		}

		if (pub == 'Newspaper' || pub == 'Website' || pub == "Other") {
			$('#PublicationsForm .web').closest('.control-group').fadeIn();
			$('#PublicationsForm #ArchivesEarliestDate').attr('placeholder', 'YYYY-MM-DD');
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


	$('#ArchivesClassification').change(function() {
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
