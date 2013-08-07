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

$documents_list = array();

foreach ($documents as $doc) {
	$documents_list["$doc->id"] = $doc->title;
}

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

<?=$this->form->create($exhibition, array('id' => 'ExhibitionsForm', 'class' => 'form-horizontal')); ?>

	<div class="span5">

		<div class="well">
		<?=$this->form->create($exhibition); ?>
			<legend>Exhibition Info</legend>
			<?=$this->form->field('title', array('autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $title_data));?>
			<?=$this->form->field('curator', array('autocomplete' => 'off'));?>
			<?=$this->form->field('venue', array('autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $venue_data));?>
			<?=$this->form->field('city', array('autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $city_data));?>
			<?=$this->form->field('country', array('autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $country_data));?>
			<?=$this->form->field('earliest_date', array('autocomplete' => 'off', 'label' => 'Opening Date'));?>
			<?=$this->form->field('latest_date', array('autocomplete' => 'off', 'label' => 'Closing Date'));?>
			<div class="control-group">
				<?=$this->form->label('type', 'Show Type', array('class' => 'control-label')); ?>
				<div class="controls">
					<?=$this->form->select('type', $show_types_list); ?>
				</div>
			</div>
			<?=$this->form->field('remarks', array('type' => 'textarea'));?>
			<?=$this->form->field('url', array('autocomplete' => 'off', 'label' => 'URL'));?>

			<div class="control-group" id="DocumentsGroup" style="display:none;">
				<?=$this->form->label('documents', 'Documents', array('class' => 'control-label')); ?>
				<div class="controls">
					<?=$this->form->select('documents', $documents_list, array('id' => 'ArchivesDocuments', 'multiple' => true)); ?>
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
