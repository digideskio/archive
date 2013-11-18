<?php

$this->title($exhibition->archive->name);

$this->form->config(
    array( 
		'label' => array(
			'class' => 'control-label',
		),
		'field' => array(
			'wrap' => array('class' => 'control-group'),
			'template' => '<div{:wrap}>{:label}<div class="controls">{:input}{:error}</div></div>',
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

	<li>
	<?=$this->html->link($exhibition->archive->name,'/exhibitions/view/'.$exhibition->archive->slug); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		Edit
	</li>

	</ul>

</div>

<div class="actions">
<ul class="nav nav-tabs">
	<li><?=$this->html->link('View','/exhibitions/view/'.$exhibition->archive->slug); ?></li>
	<li class="active">
		<a href="#">
			Edit
		</a>
	</li>
	<li><?=$this->html->link('Attachments','/exhibitions/attachments/'.$exhibition->archive->slug); ?></li>
	<li><?=$this->html->link('History','/exhibitions/history/'.$exhibition->archive->slug); ?></li>
</ul>
	<div class="btn-toolbar">
			<a class="btn btn-danger" data-toggle="modal" href="#deleteModal">
				<i class="icon-white icon-trash"></i> Delete Exhibition
			</a>
	</div>
</div>

<div class="row">

<?=$this->form->create(compact('archive', 'exhibition'), array('id' => 'ExhibitionsForm', 'class' => 'form-horizontal')); ?>

	<div class="span5">
		<div class="well">
			<legend>Exhibition Info</legend>
			<?=$this->form->field('archive.name', array('autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $title_data));?>
			<?=$this->form->field('exhibition.curator', array('autocomplete' => 'off'));?>
			<?=$this->form->field('exhibition.venue', array('autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $venue_data));?>
			<?=$this->form->field('exhibition.city', array('autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $city_data));?>
			<?=$this->form->field('exhibition.country', array('autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $country_data));?>
			<?=$this->form->field('archive.earliest_date', array(
				'autocomplete' => 'off',
				'label' => 'Opening Date',
				'value' => $exhibition->archive->start_date_formatted()
			));?>
			<?=$this->form->field('archive.latest_date', array(
				'autocomplete' => 'off',
				'label' => 'Closing Date',
				'value' => $exhibition->archive->end_date_formatted()
			));?>
			<div class="control-group">
				<?=$this->form->label('archive.type', 'Show Type', array('class' => 'control-label')); ?>
				<div class="controls">
					<?=$this->form->select('archive.type', $show_types_list, array('value' => $exhibition->archive->type)); ?>
				</div>
			</div>
			<?=$this->form->field('exhibition.remarks', array(
				'type' => 'textarea',
			));?>
		</div>

		<div class="well">
			<?=$this->form->submit('Save', array('class' => 'btn btn-large btn-block btn-primary')); ?>
		</div>
				
	</div>

<?=$this->form->end(); ?>

</div>

<div class="modal fade hide" id="deleteModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Delete Exhibition</h3>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to permanently delete <strong><?=$exhibition->archive->name; ?></strong>?</p>
			
			<p>By selecting <code>Delete</code>, you will remove this Exhibition from the listings. Are you sure you want to continue?</p>
			</div>
			<div class="modal-footer">
			<?php $slug = $exhibition->archive->slug; ?>
			<?=$this->form->create($exhibition, array('url' => "/exhibitions/delete/$slug", 'method' => 'post')); ?>
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
			<?=$this->form->submit('Delete', array('class' => 'btn btn-danger')); ?>
			<?=$this->form->end(); ?>
	</div>
</div>
