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

$artist = $work->artist ?: $artists[0];

$work_classes_list = array_combine($classifications, $classifications);

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
<?=$this->form->create($work, array('id' => 'WorksForm')); ?>
	<legend>Artwork Info</legend>

	<?php $work_classes_list = array_merge(array('' => 'Choose one...'), $work_classes_list); ?>

	<?=$this->form->label('classification', 'Classification'); ?>
	<?=$this->form->select('classification', $work_classes_list); ?>

    <?=$this->form->field('artist', array('value' => $artist, 'autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $artist_names));?>
    <?=$this->form->field('title', array('autocomplete' => 'off'));?>
    <?=$this->form->field('earliest_date', array('autocomplete' => 'off'));?>
    <?=$this->form->field('latest_date', array('autocomplete' => 'off'));?>
    <?=$this->form->field('creation_number', array('label' => 'Artwork ID', 'autocomplete' => 'off'));?>
	<?=$this->form->field('materials', array('type' => 'textarea'));?>
    <?=$this->form->field('quantity', array('autocomplete' => 'off'));?>
    <?=$this->form->field('remarks', array('type' => 'textarea'));?>
	<?=$this->form->field('height', array(
		'label' => "Height (cm)",
		'class' => 'dim two-d',
		'autocomplete' => 'off'
	));?>
	<?=$this->form->field('width', array(
		'label' => "Width (cm)",
		'class' => 'dim two-d',
		'autocomplete' => 'off'
	));?>
	<?=$this->form->field('depth', array(
		'label' => "Depth (cm)",
		'class' => 'dim three-d',
		'autocomplete' => 'off'
	));?>
	<?=$this->form->field('diameter', array(
		'label' => "Diameter (cm)",
		'class' => 'dim three-d',
		'autocomplete' => 'off'
	));?>
    <?=$this->form->field('running_time', array('autocomplete' => 'off', 'class' => 'dim four-d'));?>
    <?=$this->form->field('measurement_remarks', array('type' => 'textarea', 'class' => 'dim remarks'));?>
    <?=$this->form->field('url', array('label' => 'URL', 'autocomplete' => 'off'));?>
    <?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
    <?=$this->html->link('Cancel','/works', array('class' => 'btn')); ?>
<?=$this->form->end(); ?>
</div>

<script>

$(document).ready(function() {

	function handleFields() {
		var work = $('#WorksClassification').val();

		$('#WorksForm .dim').parent().hide();

		if (work) {
			$('#WorksForm .dim.remarks').parent().fadeIn();
		} else {
			$('#WorksForm .dim.remarks').parent().hide();
		}

		if (work == 'Audio' || work == 'Video') {
			$('#WorksForm .four-d').parent().fadeIn();
		} else {
			$('#WorksForm .four-d').parent().hide();
		}

		if (work == 'Painting' || work == 'Photography' || work == 'Poster and Design' || work == 'Works on Paper' ||
				work == 'Furniture' || work == 'Installation' || work == 'Object' || work == 'Porcelain' || work == 'Pottery') { 
			
			$('#WorksForm .two-d').parent().fadeIn();
		} else {
			$('#WorksForm .two-d').parent().hide();
		}

		if (work == 'Furniture' || work == 'Installation' || work == 'Object' || work == 'Porcelain' || work == 'Pottery') {
			$('#WorksForm .three-d').parent().fadeIn();
		} else {
			$('#WorksForm .three-d').parent().hide();
		}
			
			
	}

	$('#WorksClassification').change(function() {
		handleFields();
	});

	handleFields();

});

</script>
