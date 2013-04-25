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

$packing_types = array('Crate', 'Paper Box', 'Simple', 'None', 'Other');
$packing_types_list = array_combine($packing_types, $packing_types);

$currencies = array('RMB', 'USD', 'EURO');
$currencies_list = array_combine($currencies, $currencies);

$location_names = json_encode($locations);

$users_list = array();

foreach ($users as $user) {
	$users_list += array($user->username => $user->name);
}

$in_time = $work->in_time ?: date('Y-m-d');

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
			<a href="/works">Index</a>
		</li>

		<li>
			<?=$this->html->link('Artists','/works/artists'); ?>
		</li>

		<li>
			<?=$this->html->link('Classifications','/works/classifications'); ?>
		</li>

		<?php if($auth->role->name == 'Admin'): ?>

			<li>
				<?=$this->html->link('Locations','/works/locations'); ?>
			</li>
		
		<?php endif; ?>

		<li>
			<?=$this->html->link('History','/works/histories'); ?>
		</li>

		<li>
			<?=$this->html->link('Search','/works/search'); ?>
		</li>

	</ul>
	<div class="btn-toolbar">
		<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

			<a class="btn btn-inverse disabled" href="#"><i class="icon-plus-sign icon-white"></i> Add Artwork</a>
		
		<?php endif; ?>
	</div>
</div>

<div class="row">

<?=$this->form->create($work, array('id' => 'WorksForm')); ?>

<div class="span5">
<div class="well">
	<legend>Artwork Info</legend>

	<?php $work_classes_list = array_merge(array('' => 'Choose one...'), $work_classes_list); ?>

	<?=$this->form->label('classification', 'Classification'); ?>
	<?=$this->form->select('classification', $work_classes_list); ?>

    <?=$this->form->field('artist', array('value' => $artist, 'autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $artist_names));?>
	<?=$this->form->field('artist_native_name', array('label' => 'Artist (Native Language)', 'autocomplete' => 'off'));?>
    <?=$this->form->field('title', array('autocomplete' => 'off'));?>
	<?=$this->form->field('native_name', array('label' => 'Artist (Native Language)', 'autocomplete' => 'off'));?>
    <?=$this->form->field('earliest_date', array('autocomplete' => 'off'));?>
    <?=$this->form->field('latest_date', array('autocomplete' => 'off'));?>
    <?=$this->form->field('creation_number', array('label' => 'Artwork ID', 'autocomplete' => 'off'));?>

	<?=$this->form->field('materials', array('type' => 'textarea'));?>
	<?=$this->form->field('edition', array('autocomplete' => 'off'));?>
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

	<label>Additional Notes</label>
	<div class="signed">
		<label class="checkbox">
		<?=$this->form->checkbox('signed', array('class' => 'two-d'));?> Artwork is Signed
		</label>
	</div>
	<div class="framed">
		<label class="checkbox">
		<?=$this->form->checkbox('framed', array('class' => 'two-d'));?> Artwork is Framed
		</label>
	</div>
	<div class="certification">
		<label class="checkbox">
		<?=$this->form->checkbox('certification');?> Certificate of Authenticity
		</label>
	</div>
    <?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
    <?=$this->html->link('Cancel','/works', array('class' => 'btn')); ?>
</div>
</div>

	<?php if($auth->role->name == 'Admin'): ?>
		<div class="span5">

		<div class="well">
			<legend>Inventory Info</legend>

				<?php $packing_types_list = array_merge(array('' => 'choose one...'), $packing_types_list); ?>

				<?=$this->form->label('packing_type', 'Packing Type'); ?>
				<?=$this->form->select('packing_type', $packing_types_list); ?>

				<?=$this->form->field('pack_price', array('label' => 'Packing Cost', 'autocomplete' => 'off'));?>

				<?=$this->form->select('pack_price_per', $currencies_list); ?>
			
				<?=$this->form->field('location', array('autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $location_names));?>

				<?=$this->form->field('in_time', array('label' => 'Received Time', 'autocomplete' => 'off', 'value' => $in_time));?>
				<?=$this->form->field('in_from', array('label' => 'Sent From', 'autocomplete' => 'off'));?>

				<?php $users_list = array_merge(array('' => 'choose one...'), $users_list); ?>

				<?=$this->form->label('in_operator', 'Received By'); ?>
				<?=$this->form->select('in_operator', $users_list); ?>

		</div>

	<?php endif; ?>
</div>

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
