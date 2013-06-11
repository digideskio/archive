<?php

$this->title($work->title);

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

$artist_names = array();
$artist_native_names = array();
$artist_names_assoc = array();

foreach ($artists as $artist) {
	if ($artist['name']) {
		array_push($artist_names, $artist['name']);
		$artist_name = $artist['name'];

		if (!isset($artist_names_assoc[$artist_name]) || $artist_names_assoc[$artist_name] == '') {
			$artist_names_assoc[$artist_name] = $artist['native_name'];
		}
	}
	if ($artist['native_name']) {
		array_push($artist_native_names, $artist['native_name']);
		$artist_native_name = $artist['native_name'];

		if (!isset($artist_names_assoc[$artist_native_name]) || $artist_names_assoc[$artist_native_name] == '') {
			$artist_names_assoc[$artist_native_name] = $artist['name'];
		}
	}
}

$artist_names = array_values(array_unique($artist_names));
$artist_names_data = json_encode($artist_names);

$artist_native_names = array_values(array_unique($artist_native_names));
$artist_native_names_data = json_encode($artist_native_names);

$artist_names_assoc_data = json_encode($artist_names_assoc);

$classification_names = array_keys($classifications);
$work_classes_list = array_combine($classification_names, $classification_names);

$materials_data = json_encode($materials);

$packing_types = array('Crate', 'Paper Box', 'Simple', 'None', 'Other');
$packing_types_list = array_combine($packing_types, $packing_types);

$currencies = array('RMB', 'USD', 'EURO');
$currencies_list = array_combine($currencies, $currencies);

$location_names = json_encode($locations);
$locations_list = $locations ? array_combine($locations, $locations) : array();

$users_list = array();

foreach ($users as $user) {
	$users_list += array($user->username => $user->name);
}

?>

<div id="location" class="row-fluid">

    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Artwork','/works'); ?>
	<span class="divider">/</span>
	</li>

	<li>
	<?=$this->html->link($work->title,'/works/view/'.$work->archive->slug); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		Edit
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li><?=$this->html->link('View','/works/view/'.$work->archive->slug); ?></li>
	<li class="active">
		<a href="#">
			Edit
		</a>
	</li>
	<li><?=$this->html->link('Attachments','/works/attachments/'.$work->archive->slug); ?></li>
	<li><?=$this->html->link('History','/works/history/'.$work->archive->slug); ?></li>
</ul>

<div class="row">

<?=$this->form->create($work, array('id' => 'WorksForm', 'class' => 'form-horizontal')); ?>

	<div class="span5">
		<div class="well">
			<legend>Artwork Info</legend>

    		<?=$this->form->field('artist', array('autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $artist_names_data));?>
			<?=$this->form->field('artist_native_name', array('label' => 'Artist (Native Language)', 'autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $artist_native_names_data));?>
    		<?=$this->form->field('title', array('autocomplete' => 'off'));?>
			<?=$this->form->field('native_name', array('label' => 'Title (Native Language)', 'autocomplete' => 'off', 'value' => $work->archive->native_name));?>
			<?=$this->form->field('earliest_date', array('autocomplete' => 'off', 'value' => $work->archive->start_date_formatted()));?>
			<?=$this->form->field('latest_date', array('autocomplete' => 'off', 'value' => $work->archive->end_date_formatted()));?>
			<?=$this->form->field('creation_number', array('autocomplete' => 'off', 'label' => 'Artwork ID'));?>

			<?=$this->form->field('remarks', array('type' => 'textarea'));?>
		</div>

		<div class="well">
			<?=$this->form->submit('Save', array('class' => 'btn btn-large btn-block btn-primary')); ?>
		</div>
		
	</div>
	
	<div class="span5">

		<div class="well">
			<legend>Details</legend>
				<?=$this->form->field('annotation', array(
					'type' => 'textarea', 
					'rows' => '5', 
					'style' => 'width:90%;',
				));?>

			<?php $work_classes_list = array_merge(array('' => 'Choose one...'), $work_classes_list); ?>

			<div class="control-group">
				<?=$this->form->label('classification', 'Classification', array('class' => 'control-label')); ?>
				<div class="controls">
					<?=$this->form->select('classification', $work_classes_list, array('value' => $work->archive->classification)); ?>
				</div>
			</div>

			<?=$this->form->field('materials', array('type' => 'textarea'));?>
			<?=$this->form->field('edition', array('autocomplete' => 'off', 'value' => $work->attribute('edition')));?>
			<?=$this->form->field('quantity', array('autocomplete' => 'off'));?>

			<?=$this->form->field('height', array(
				'label' => "Height (cm)",
				'class' => 'dim two-d',
				'autocomplete' => 'off',
				'value' => $work->height ?: ''
			));?>
			<?=$this->form->field('width', array(
				'label' => "Width (cm)",
				'class' => 'dim two-d',
				'autocomplete' => 'off',
				'value' => $work->width ?: ''
			));?>
			<?=$this->form->field('depth', array(
				'label' => "Depth (cm)",
				'class' => 'dim three-d',
				'autocomplete' => 'off',
				'value' => $work->depth ?: ''
			));?>
			<?=$this->form->field('diameter', array(
				'label' => "Diameter (cm)",
				'class' => 'dim three-d',
				'autocomplete' => 'off',
				'value' => $work->diameter ?: ''
			));?>
			<?=$this->form->field('running_time', array('autocomplete' => 'off', 'class' => 'dim four-d'));?>
			<?=$this->form->field('measurement_remarks', array('type' => 'textarea', 'class' => 'dim remarks'));?>

			<div class="certification control-group" style="margin-bottom: 0">
				<label class="control-label">Additional Notes</label>

				<div class="controls">
					<label class="checkbox">
						<?=$this->form->checkbox('certification', array('checked' => $work->attribute('certification')));?> Certificate of Authenticity
					</label>
				</div>
			</div>
			<div class="signed control-group" style="margin-bottom: 0;">
				<div class="controls">
					<label class="checkbox">
						<?=$this->form->checkbox('signed', array('class' => 'two-d', 'checked' => $work->attribute('signed')));?> Artwork is Signed
					</label>
				</div>
			</div>
			<div class="framed control-group" style="margin-bottom: 0;">
				<div class="controls">
					<label class="checkbox">
						<?=$this->form->checkbox('framed', array('class' => 'two-d', 'checked' => $work->attribute('framed')));?> Artwork is Framed
					</label>
				</div>
			</div>
			<br/>

			<?php if($inventory): ?>
				<legend>Inventory Info</legend>

				<?php $packing_types_list = array_merge(array('' => 'Choose one...'), $packing_types_list); ?>

				<div class="control-group">
					<?=$this->form->label('packing_type', 'Packing Type'); ?>
					<div class="controls">
						<?=$this->form->select('packing_type', $packing_types_list, array('value' => $work->attribute('packing_type'))); ?>
					</div>
				</div>

				<div class="control-group">
					<label for="WorksPackPrice" class="control-label">Packing Cost</label>
					<div class="controls control-row">
						<input type="text" name="pack_price" autocomplete="off" id="WorksPackPrice" class="span1" value="<?=$work->attribute('pack_price');?>">

						<?=$this->form->select('pack_price_per', $currencies_list, array('value' => $work->attribute('pack_price_per'), 'class' => 'span1')); ?>
					</div>
				</div>
			
				<?php if ($locations && sizeof($locations) < 50): ?>

					<?php $locations_list = array_merge(array('' => 'Select location...'), $locations_list); ?>

					<div class="control-group">
						<?=$this->form->label('location', 'Location', array('class' => 'control-label')); ?>
						<div class="controls control-row">
							<input type="text" name="location" autocomplete="off" class="span2" id="WorksLocation" value="<?=$work->location?>">
							<?=$this->form->select('select_location', $locations_list, array('class' => 'span1', 'value' => $work->location)); ?>
						</div>
					</div>
						<script>
							
							$(document).ready(function() {

								$('#WorksSelectLocation').change(function() {
									var location = $(this).val();
									$('#WorksLocation').val(location);
								});

							});

						</script>
				<?php else: ?>
					<?=$this->form->field('location', array('autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $location_names));?>
				<?php endif; ?>

				<?=$this->form->field('in_time', array('label' => 'Received Time', 'autocomplete' => 'off', 'value' => $work->attribute('in_time')));?>
				<?=$this->form->field('in_from', array('label' => 'Sent From', 'autocomplete' => 'off', 'value' => $work->attribute('in_from')));?>

				<?php $users_list = array_merge(array('' => 'Choose one...'), $users_list); ?>

				<div class="control-group">
					<?=$this->form->label('in_operator', 'Received By'); ?>
					<div class="controls">
						<?=$this->form->select('in_operator', $users_list, array('value' => $work->attribute('in_operator'))); ?>
					</div>
				</div>

				<div class="control-group">
					<label for="WorksBuyPrice" class="control-label">Purchase Price</label>
					<div class="controls control-row">
						<input type="text" name="buy_price" autocomplete="off" id="WorksBuyPrice" class="span1" value="<?=$work->attribute('buy_price'); ?>">

						<?=$this->form->select('buy_price_per', $currencies_list, array('class' => 'span1', 'value' => $work->attribute('buy_price_per'))); ?>
					</div>
				</div>

				<div class="control-group">
					<label for="WorksSellPrice" class="control-label">Sale Price</label>
					<div class="controls control-row">
						<input type="text" name="sell_price" autocomplete="off" id="WorksSellPrice" class="span1" value="<?=$work->attribute('sell_price'); ?>">

						<?=$this->form->select('sell_price_per', $currencies_list, array('class' => 'span1', 'value' => $work->attribute('sell_price_per'))); ?>
					</div>
				</div>

				<?=$this->form->field('sell_date', array('label' => 'Date of Sale', 'autocomplete' => 'off', 'value' => $work->attribute('sell_date')));?>

			<?php endif; ?>

		</div>
	</div>

<?=$this->form->end(); ?>

		
</div>
	
<div class="row">
	<div class="span5 offset5">
		<div class="well">
		
			<legend>Edit</legend>
		
			<a class="btn btn-danger" data-toggle="modal" href="#deleteModal">
				<i class="icon-white icon-trash"></i> Delete Artwork
			</a>
		
		</div>
	</div>
</div>

<script>

$(document).ready(function() {

	var artist_names =	<?php echo $artist_names_data; ?>;
	var artist_native_names = <?php echo $artist_native_names_data; ?>;
	var artist_names_assoc = <?php echo $artist_names_assoc_data; ?>;
	var materials = <?php echo $materials_data; ?>

	$('#WorksArtist').typeahead(
		{
			source: artist_names,
			updater: function (item) {
				if (artist_names_assoc[item] != undefined) {
					$('#WorksArtistNativeName').val(artist_names_assoc[item]);
				}
				return item;
			}
		}
	);

	$('#WorksArtistNativeName').typeahead(
		{
			source: artist_native_names,
			updater: function (item) {
				if (artist_names_assoc[item] != undefined) {
					$('#WorksArtist').val(artist_names_assoc[item]);
				}
				return item;
			}
		}
	);

	$('#WorksMaterials').typeahead(
		{
			source: materials
		}
	);

});

</script>

<script>

$(document).ready(function() {

	var classifications = <?php echo json_encode($classifications); ?>;

	function handleFields() {
		var work = $('#WorksClassification').val();

		$('#WorksForm .dim').closest('.control-group').hide();

		$('#WorksForm .two-d').closest('.control-group').hide();
		$('#WorksForm .three-d').closest('.control-group').hide();
		$('#WorksForm .four-d').closest('.control-group').hide();

		if (work) {
			$('#WorksForm .dim.remarks').closest('.control-group').fadeIn();

			var classification_class = classifications[work]['class'];
			var classes = classification_class.split(" ");

			for(i=0,x=classes.length;i<x;i++){
				$('#WorksForm .' + classes[i]).closest('.control-group').fadeIn();
			}
			
		} else {
			$('#WorksForm .dim.remarks').closest('.control-group').hide();
		}

	}

	$('#WorksClassification').change(function() {
		handleFields();
	});

	handleFields();

});

</script>

<div class="modal fade hide" id="deleteModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Delete Artwork</h3>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to permanently delete <strong><?=$work->title; ?></strong>?</p>
			
			<p>By selecting <code>Delete</code>, you will remove this Artwork from the listings. Are you sure you want to continue?</p>
			</div>
			<div class="modal-footer">
			<?php $slug = $work->archive->slug; ?>
			<?=$this->form->create($work, array('url' => "/works/delete/$slug", 'method' => 'post')); ?>
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
			<?=$this->form->submit('Delete', array('class' => 'btn btn-danger')); ?>
			<?=$this->form->end(); ?>
	</div>
</div>
