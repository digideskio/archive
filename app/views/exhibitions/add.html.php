<?php 

$this->title('Add an Exhibition');

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

$title_data = json_encode($titles);

$venue_data = json_encode($venues);

$city_data = json_encode($cities);

$country_data = json_encode($countries);

$show_types_list = array('Solo' => 'Solo', 'Group' => 'Group');

?>

<div id="location" class="row-fluid">

	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Exhibitions','/exhibitions'); ?>
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
			<?=$this->html->link('Index','/exhibitions'); ?>
		</li>
		<li>
			<?=$this->html->link('Venues','/exhibitions/venues'); ?>
		</li>
		<li>
			<?=$this->html->link('History','/exhibitions/histories'); ?>
		</li>
		<li>
			<?=$this->html->link('Search','/exhibitions/search'); ?>
		</li>
		
	</ul>
	<div class="btn-toolbar">
			<a class="btn btn-inverse disabled" href="#"><i class="icon-plus-sign icon-white"></i> Add an Exhibition</a>
	</div>
</div>

<div class="row">

<?=$this->form->create(compact('archive', 'exhibition', 'link'), array('id' => 'ExhibitionsForm', 'class' => 'form-horizontal')); ?>

	<div class="span5">

		<div class="well">
			<legend>Exhibition Info</legend>
			<?=$this->form->field('archive.title', array('autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $title_data, 'label' => 'Title'));?>
			<?=$this->form->field('exhibition.curator', array('autocomplete' => 'off', 'label' => 'Curator'));?>
			<?=$this->form->field('exhibition.venue', array('autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $venue_data, 'label' => 'Venue'));?>
			<?=$this->form->field('exhibition.city', array('autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $city_data, 'label' => 'City'));?>
			<?=$this->form->field('exhibition.country', array('autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $country_data, 'label' => 'Country'));?>
			<?=$this->form->field('archive.earliest_date', array('autocomplete' => 'off', 'label' => 'Opening Date'));?>
			<?=$this->form->field('archive.latest_date', array('autocomplete' => 'off', 'label' => 'Closing Date'));?>
			<div class="control-group">
				<?=$this->form->label('archive.type', 'Show Type', array('class' => 'control-label')); ?>
				<div class="controls">
					<?=$this->form->select('archive.type', $show_types_list); ?>
				</div>
			</div>
			<?=$this->form->field('exhibition.remarks', array('type' => 'textarea', 'label' => 'Remarks'));?>
			<?=$this->form->field('link.url', array('autocomplete' => 'off', 'label' => 'URL'));?>

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

		<div class="well">
			<?=$this->form->submit('Save', array('class' => 'btn btn-large btn-block btn-primary')); ?>
		</div>

	</div>

	<div class="span5">
		<?=$this->partial->archives_documents_add(array(
			'documents' => $documents,
		)); ?>		
	</div>

<?=$this->form->end(); ?>

</div>
