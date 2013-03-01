<?php 

$this->title('Add Artwork');

$this->form->config(
    array( 
        'templates' => array( 
            'error' => '<div class="help-inline">{:content}</div>' 
        )
    )
); 

$artist_names = json_encode($artists);

$classification_names = json_encode($classifications);

$artist = $work->artist ?: $artists[0];

?>

<div id="location" class="row-fluid">

    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Artwork','/works'); ?>
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
			<?=$this->html->link('Index','/works'); ?>
		</li>

		<li>
			<?=$this->html->link('History','/works/histories'); ?>
		</li>

	</ul>
	<div class="btn-toolbar">
		<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

			<a class="btn btn-inverse disabled" href="#"><i class="icon-plus-sign icon-white"></i> Add Artwork</a>
		
		<?php endif; ?>
	</div>
</div>

<div class="well">
<?=$this->form->create($work); ?>
	<legend>Artwork Info</legend>
    <?=$this->form->field('artist', array('value' => $artist, 'autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $artist_names));?>
    <?=$this->form->field('title');?>
    <?=$this->form->field('classification', array('autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $classification_names));?>
    <?=$this->form->field('earliest_date');?>
    <?=$this->form->field('latest_date');?>
    <?=$this->form->field('creation_number', array('label' => 'Artwork ID'));?>
	<?=$this->form->field('materials', array('type' => 'textarea'));?>
    <?=$this->form->field('quantity');?>
    <?=$this->form->field('remarks', array('type' => 'textarea'));?>
	<?=$this->form->field('height', array(
		'label' => "Height (cm)"
	));?>
	<?=$this->form->field('width', array(
		'label' => "Width (cm)"
	));?>
	<?=$this->form->field('depth', array(
		'label' => "Depth (cm)"
	));?>
	<?=$this->form->field('diameter', array(
		'label' => "Diameter (cm)"
	));?>
	<?=$this->form->field('weight', array(
		'label' => "Weight (kg)"
	));?>
    <?=$this->form->field('running_time');?>
    <?=$this->form->field('measurement_remarks', array('type' => 'textarea'));?>
    <?=$this->form->field('url', array('label' => 'URL'));?>
    <?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
    <?=$this->html->link('Cancel','/works', array('class' => 'btn')); ?>
<?=$this->form->end(); ?>
</div>
