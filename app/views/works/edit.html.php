<?php

$this->title($work->archive->name);

$authority_can_inventory = $this->authority->canInventory();
$inventory = (\lithium\core\Environment::get('inventory') && ($authority_can_inventory));

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

$artists_list = array('' => 'Choose one...');

foreach ($artists as $a) {
	$artists_list[$a->id] = $a->archive->names();
}

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
	<?=$this->html->link($work->archive->name,'/works/view/'.$work->archive->slug); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
		Edit
	</li>

	</ul>

</div>

<div class="actions">
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

	<div class="btn-toolbar">
			<a class="btn btn-danger" data-toggle="modal" href="#deleteModal">
				<i class="icon-white icon-trash"></i> Delete Artwork
			</a>
	</div>
</div>

<div class="row">

<?=$this->form->create(compact('archive', 'work', 'artist'), array('id' => 'WorksForm', 'class' => 'form-horizontal')); ?>

	<div class="span5">
		<div class="well">
			<legend>Artwork Info</legend>

			<div class="control-group">
				<?=$this->form->label('artist.id', 'Artist', array('class' => 'control-label')); ?>
				<div class="controls">
                    <?php $artist_id = !empty($artist) ? $artist->id : ''; ?>
					<?=$this->form->select('artist.id', $artists_list, array('value' => $artist_id)); ?>
				</div>
			</div>

    		<?=$this->form->field('archive.name', array('label' => 'Title', 'autocomplete' => 'off'));?>
			<?=$this->form->field('archive.native_name', array('label' => 'Title (Native Language)', 'autocomplete' => 'off', 'value' => $work->archive->native_name));?>
			<?=$this->form->field('archive.earliest_date', array('label' => 'Earliest Date', 'autocomplete' => 'off', 'value' => $work->archive->start_date_formatted()));?>
			<?=$this->form->field('archive.latest_date', array('label' => 'Latest Date', 'autocomplete' => 'off', 'value' => $work->archive->end_date_formatted()));?>
			<?=$this->form->field('work.creation_number', array('autocomplete' => 'off', 'label' => 'Artwork ID'));?>

            <?=$this->form->field('work.remarks', array(
                'label' => 'Remarks',
                'type' => 'textarea',
                'rows' => 5
            ));?>

			<div class="control-group" style="margin-bottom: 0">
				<label class="control-label">Published</label>

				<div class="controls">
					<label class="checkbox">
						<?=$this->form->checkbox('archive.published');?>
					</label>
				</div>
			</div>
		</div>

		<div class="well">
			<?=$this->form->submit('Save', array('class' => 'btn btn-large btn-block btn-primary')); ?>
		</div>

	</div>

	<div class="span5">

		<div class="well">
			<legend>Details</legend>
				<?=$this->form->field('work.annotation', array(
					'label' => 'Annotation',
					'type' => 'textarea',
					'rows' => '5',
					'style' => 'width:90%;',
				));?>

			<?php $work_classes_list = array_merge(array('' => 'Choose one...'), $work_classes_list); ?>

			<div class="control-group">
				<?=$this->form->label('archive.classification', 'Classification', array('class' => 'control-label')); ?>
				<div class="controls">
					<?=$this->form->select('archive.classification', $work_classes_list, array('value' => $work->archive->classification)); ?>
				</div>
			</div>

			<?=$this->form->field('work.materials', array('label' => 'Materials', 'type' => 'textarea'));?>
			<?=$this->form->field('work.edition', array('label' => 'Edition', 'autocomplete' => 'off', 'value' => $work->attribute('edition')));?>
			<?=$this->form->field('work.quantity', array('label' => 'Quantity', 'autocomplete' => 'off'));?>

			<?=$this->form->field('work.height', array(
				'label' => "Height (cm)",
				'class' => 'dim two-d',
				'autocomplete' => 'off',
				'value' => $work->height ?: ''
			));?>
			<?=$this->form->field('work.width', array(
				'label' => "Width (cm)",
				'class' => 'dim two-d',
				'autocomplete' => 'off',
				'value' => $work->width ?: ''
			));?>
			<?=$this->form->field('work.depth', array(
				'label' => "Depth (cm)",
				'class' => 'dim three-d',
				'autocomplete' => 'off',
				'value' => $work->depth ?: ''
			));?>
			<?=$this->form->field('work.diameter', array(
				'label' => "Diameter (cm)",
				'class' => 'dim three-d',
				'autocomplete' => 'off',
				'value' => $work->diameter ?: ''
			));?>
			<?=$this->form->field('work.running_time', array('label' => 'Running Time', 'autocomplete' => 'off', 'class' => 'dim four-d'));?>
            <?=$this->form->field('work.measurement_remarks', array(
                'label' => 'Measurement Remarks',
                'type' => 'textarea',
                'class' => 'dim remarks',
                'rows' => 5
            ));?>

			<div class="certification control-group" style="margin-bottom: 0">
				<label class="control-label">Additional Notes</label>

				<div class="controls">
					<label class="checkbox">
						<?=$this->form->checkbox('work.certification', array('checked' => $work->attribute('certification')));?> Certificate of Authenticity
					</label>
				</div>
			</div>
			<div class="signed control-group" style="margin-bottom: 0;">
				<div class="controls">
					<label class="checkbox">
						<?=$this->form->checkbox('work.signed', array('class' => 'two-d', 'checked' => $work->attribute('signed')));?> Artwork is Signed
					</label>
				</div>
			</div>
			<div class="framed control-group" style="margin-bottom: 0;">
				<div class="controls">
					<label class="checkbox">
						<?=$this->form->checkbox('work.framed', array('class' => 'two-d', 'checked' => $work->attribute('framed')));?> Artwork is Framed
					</label>
				</div>
			</div>
			<br/>

			<?php if($inventory): ?>
				<legend>Inventory Info</legend>

				<?php $packing_types_list = array_merge(array('' => 'Choose one...'), $packing_types_list); ?>

				<div class="control-group">
					<?=$this->form->label('work.packing_type', 'Packing Type'); ?>
					<div class="controls">
						<?=$this->form->select('work.packing_type', $packing_types_list, array('value' => $work->attribute('packing_type'))); ?>
					</div>
				</div>

				<div class="control-group">
					<label for="WorksPackPrice" class="control-label">Packing Cost</label>
					<div class="controls control-row">
						<input type="text" name="work[pack_price]" autocomplete="off" id="WorksPackPrice" class="span1" value="<?=$work->attribute('pack_price');?>">

						<?=$this->form->select('pack_price_per', $currencies_list, array('value' => $work->attribute('pack_price_per'), 'class' => 'span1')); ?>
					</div>
				</div>

				<?php if ($locations && sizeof($locations) < 50): ?>

					<?php $locations_list = array_merge(array('' => 'Select location...'), $locations_list); ?>

					<div class="control-group">
						<?=$this->form->label('work.location', 'Location', array('class' => 'control-label')); ?>
						<div class="controls control-row">
							<input type="text" name="work[location]" autocomplete="off" class="span2" id="WorksLocation" value="<?=$work->location?>">
							<?=$this->form->select('work.select_location', $locations_list, array('class' => 'span1', 'value' => $work->location)); ?>
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
					<?=$this->form->field('work.location', array('autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $location_names));?>
				<?php endif; ?>

				<?=$this->form->field('work.in_time', array('label' => 'Received Time', 'autocomplete' => 'off', 'value' => $work->attribute('in_time')));?>
				<?=$this->form->field('work.in_from', array('label' => 'Sent From', 'autocomplete' => 'off', 'value' => $work->attribute('in_from')));?>

				<?php $users_list = array_merge(array('' => 'Choose one...'), $users_list); ?>

				<div class="control-group">
					<?=$this->form->label('work.in_operator', 'Received By'); ?>
					<div class="controls">
						<?=$this->form->select('work.in_operator', $users_list, array('value' => $work->attribute('in_operator'))); ?>
					</div>
				</div>

				<div class="control-group">
					<label for="WorksBuyPrice" class="control-label">Purchase Price</label>
					<div class="controls control-row">
						<input type="text" name="work[buy_price]" autocomplete="off" id="WorksBuyPrice" class="span1" value="<?=$work->attribute('buy_price'); ?>">

						<?=$this->form->select('work.buy_price_per', $currencies_list, array('class' => 'span1', 'value' => $work->attribute('buy_price_per'))); ?>
					</div>
				</div>

				<div class="control-group">
					<label for="WorksSellPrice" class="control-label">Sale Price</label>
					<div class="controls control-row">
						<input type="text" name="work[sell_price]" autocomplete="off" id="WorksSellPrice" class="span1" value="<?=$work->attribute('sell_price'); ?>">

						<?=$this->form->select('work.sell_price_per', $currencies_list, array('class' => 'span1', 'value' => $work->attribute('sell_price_per'))); ?>
					</div>
				</div>

				<?=$this->form->field('work.sell_date', array('label' => 'Date of Sale', 'autocomplete' => 'off', 'value' => $work->attribute('sell_date')));?>

			<?php endif; ?>

		</div>
	</div>

<?=$this->form->end(); ?>


</div>

<script>

$(document).ready(function() {

	var materials = <?php echo $materials_data; ?>

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
		var work = $('#ArchivesClassification').val();

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

	$('#ArchivesClassification').change(function() {
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
			<p>Are you sure you want to permanently delete <strong><?=$work->archive->name; ?></strong>?</p>

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
