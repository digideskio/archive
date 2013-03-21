<?php

$this->title($exhibition->title);

$this->form->config(
    array( 
        'templates' => array( 
            'error' => '<div class="help-inline">{:content}</div>' 
        )
    )
); 

$title_data = json_encode($titles);

$venue_data = json_encode($venues);

$city_data = json_encode($cities);

$country_data = json_encode($countries);

?>

<div id="location" class="row-fluid">

    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Exhibitions','/exhibitions'); ?>
	<span class="divider">/</span>
	</li>

	<li>
	<?=$this->html->link($exhibition->title,'/exhibitions/view/'.$exhibition->archive->slug); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		Edit
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li><?=$this->html->link('View','/exhibitions/view/'.$exhibition->archive->slug); ?></li>
	<li class="active">
		<a href="#">
			Edit
		</a>
	</li>
</ul>

<div class="row">
	<div class="span5">
		<div class="well">
		<?=$this->form->create($exhibition); ?>
			<?=$this->form->field('title', array('autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $title_data));?>
			<?=$this->form->field('curator', array('autocomplete' => 'off'));?>
			<?=$this->form->field('venue', array('autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $venue_data));?>
			<?=$this->form->field('city', array('autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $city_data));?>
			<?=$this->form->field('country', array('autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $country_data));?>
			<?=$this->form->field('earliest_date', array(
				'autocomplete' => 'off',
				'label' => 'Opening Date',
				'value' => $exhibition->archive->start_date_formatted()
			));?>
			<?=$this->form->field('latest_date', array(
				'autocomplete' => 'off',
				'label' => 'Closing Date',
				'value' => $exhibition->archive->end_date_formatted()
			));?>
			<?=$this->form->label('Show Type');?>
			<?=$this->form->select('type', array('Solo' => 'Solo', 'Group' => 'Group'), array('value' => $exhibition->archive->type)); ?>
			<?=$this->form->field('remarks', array(
				'type' => 'textarea',
			));?>
			<?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
			<?=$this->html->link('Cancel','/exhibitions/view/'.$exhibition->archive->slug, array('class' => 'btn')); ?>
		<?=$this->form->end(); ?>
		</div>


				
		<div class="well">

			<legend>Edit</legend>

			<a class="btn btn-danger" data-toggle="modal" href="#deleteModal">
				<i class="icon-white icon-trash"></i> Delete Exhibition
			</a>

		</div>
	</div>

	<div class="span5">

	<?=$this->partial->archives_links_edit(array(
		'model' => $exhibition,
		'junctions' => $exhibition_links,
	)); ?>		

	<?=$this->partial->archives_documents_edit(array(
		'model' => $exhibition,
		'archives_documents' => $archives_documents,
	)); ?>		

<div class="modal fade hide" id="deleteModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Delete Exhibition</h3>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to permanently delete <strong><?=$exhibition->title; ?></strong>?</p>
			
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
