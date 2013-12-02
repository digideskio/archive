<?php 

$this->title('Add Artwork');

$authority_is_admin = $this->authority->isAdmin();

$inventory = (\lithium\core\Environment::get('inventory') && ($authority_is_admin));

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

$default_artist = $artist_names ? $artist_names[0] : '';

$artworks = \lithium\core\Environment::get('artworks');
if ($artworks && isset($artworks['artist']) && isset($artworks['artist']['default'])) {
	if ($artworks['artist']['default']) {
		$default_artist = $artworks['artist']['default'] === true ? $default_artist : $artworks['artist']['default'];
	} else {
		$default_artist = '';
	}
}

$default_artist_native_name = isset($artist_names_assoc[$default_artist]) ? $artist_names_assoc[$default_artist] : '';

$artist = $work->artist ?: $default_artist;
$artist_native_name = $work->artist_native_name ?: $default_artist_native_name;

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

		<?php if($inventory): ?>

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

			<a class="btn btn-inverse disabled" href="#"><i class="icon-plus-sign icon-white"></i> Add Artwork</a>
		
	</div>
</div>

<div class="row">

<?=$this->form->create(compact('archive', 'work', 'link'), array('id' => 'WorksForm', 'class' => 'form-horizontal')); ?>

	<div class="span5">
		<div class="well">
			<legend>Artwork Info</legend>

			<?=$this->form->field('work.artist', array('label' => 'Artist', 'value' => $artist, 'autocomplete' => 'off'));?>
			<?=$this->form->field('work.artist_native_name', array('value' => $artist_native_name, 'label' => 'Artist (Native Language)', 'autocomplete' => 'off'));?>

			<?=$this->form->field('archive.name', array('label' => 'Title', 'autocomplete' => 'off'));?>
			<?=$this->form->field('archive.native_name', array('label' => 'Title (Native Language)', 'autocomplete' => 'off'));?>
			<?=$this->form->field('archive.earliest_date', array('label' => 'Earliest Date', 'autocomplete' => 'off'));?>
			<?=$this->form->field('archive.latest_date', array('label' => 'Latest Date', 'autocomplete' => 'off'));?>
			<?=$this->form->field('work.creation_number', array('label' => 'Artwork ID', 'autocomplete' => 'off'));?>

			<?=$this->form->field('work.remarks', array('label' => 'Remarks', 'type' => 'textarea'));?>
			<?=$this->form->field('link.url', array('label' => 'URL', 'autocomplete' => 'off'));?>

			<div class="control-group" style="margin-bottom: 0">
				<label class="control-label">Published</label>

				<div class="controls">
					<label class="checkbox">
						<?=$this->form->checkbox('archive.published');?>
					</label>
				</div>
			</div>

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

		<?=$this->partial->archives_documents_add(array(
			'documents' => $documents,
		)); ?>		

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
				<?=$this->form->select('archive.classification', $work_classes_list); ?>
			</div>
		</div>

		<?=$this->form->field('work.materials', array('label' => 'Materials', 'type' => 'textarea'));?>
		<?=$this->form->field('work.edition', array('label' => 'Edition', 'autocomplete' => 'off'));?>
		<?=$this->form->field('work.quantity', array('label' => 'Quantity', 'autocomplete' => 'off'));?>

		<?=$this->form->field('work.height', array(
			'label' => "Height (cm)",
			'class' => 'dim two-d',
			'autocomplete' => 'off'
		));?>
		<?=$this->form->field('work.width', array(
			'label' => "Width (cm)",
			'class' => 'dim two-d',
			'autocomplete' => 'off'
		));?>
		<?=$this->form->field('work.depth', array(
			'label' => "Depth (cm)",
			'class' => 'dim three-d',
			'autocomplete' => 'off'
		));?>
		<?=$this->form->field('work.diameter', array(
			'label' => "Diameter (cm)",
			'class' => 'dim three-d',
			'autocomplete' => 'off'
		));?>
		<?=$this->form->field('work.running_time', array('label'=> 'Running Time', 'autocomplete' => 'off', 'class' => 'dim four-d'));?>
		<?=$this->form->field('work.measurement_remarks', array('label' => 'Measurement Remarks', 'type' => 'textarea', 'class' => 'dim remarks'));?>

		<div class="certification control-group" style="margin-bottom: 0">
			<label class="control-label">Additional Notes</label>

			<div class="controls">
				<label class="checkbox">
					<?=$this->form->checkbox('work.certification');?> Certificate of Authenticity
				</label>
			</div>
		</div>
		<div class="signed control-group" style="margin-bottom: 0;">
			<div class="controls">
				<label class="checkbox">
					<?=$this->form->checkbox('work.signed', array('class' => 'two-d'));?> Artwork is Signed
				</label>
			</div>
		</div>
		<div class="framed control-group" style="margin-bottom: 0;">
			<div class="controls">
				<label class="checkbox">
					<?=$this->form->checkbox('work.framed', array('class' => 'two-d'));?> Artwork is Framed
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
					<?=$this->form->select('work.packing_type', $packing_types_list); ?>
				</div>
			</div>

			<div class="control-group">
				<label for="WorksPackPrice" class="control-label">Packing Cost</label>
				<div class="controls control-row">
					<input type="text" name="work[pack_price]" autocomplete="off" id="WorksPackPrice" class="span1" value="<?=$work->pack_price; ?>">

					<?=$this->form->select('work.pack_price_per', $currencies_list, array('class' => 'span1')); ?>
				</div>
			</div>
		
			<?php if ($locations && sizeof($locations) < 50): ?>

				<?php $locations_list = array_merge(array('' => 'Select location...'), $locations_list); ?>

				<div class="control-group"><span></span>
					<?=$this->form->label('work.location', 'Location', array('class' => 'control-label')); ?>
					<div class="controls control-row">
						<input type="text" name="work[location]" autocomplete="off" class="span2" id="WorksLocation" value="<?=$work->location?>">
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
				<?=$this->form->field('work.location', array('autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $location_names));?>
			<?php endif; ?>

			<?=$this->form->field('work.in_time', array('label' => 'Received Time', 'autocomplete' => 'off', 'value' => $in_time));?>
			<?=$this->form->field('work.in_from', array('label' => 'Sent From', 'autocomplete' => 'off'));?>

			<?php $users_list = array_merge(array('' => 'Choose one...'), $users_list); ?>

			<div class="control-group">
				<?=$this->form->label('work.in_operator', 'Received By'); ?>
				<div class="controls">
					<?=$this->form->select('work.in_operator', $users_list); ?>
				</div>
			</div>

			<div class="control-group">
				<label for="WorksBuyPrice" class="control-label">Purchase Price</label>
				<div class="controls control-row">
					<input type="text" name="work[buy_price]" autocomplete="off" id="WorksBuyPrice" class="span1" value="<?=$work->buy_price; ?>">

					<?=$this->form->select('work.buy_price_per', $currencies_list, array('class' => 'span1')); ?>
				</div>
			</div>

			<div class="control-group">
				<label for="WorksSellPrice" class="control-label">Sale Price</label>
				<div class="controls control-row">
					<input type="text" name="work[sell_price]" autocomplete="off" id="WorksSalePrice" class="span1" value="<?=$work->sell_price; ?>">

					<?=$this->form->select('work.sell_price_per', $currencies_list, array('class' => 'span1')); ?>
				</div>
			</div>

			<?=$this->form->field('work.sell_date', array('label' => 'Date of Sale', 'autocomplete' => 'off'));?>

		<?php endif; ?>

	</div>
</div>

<?=$this->form->end(); ?>
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
