<?php
	$authority_can_edit = $this->authority->canEdit();
?>

<?php if (isset($showBar) && $showBar): ?>

	<div class="navbar">
		<div class="navbar-inner">
		<ul class="nav">
			<li class="meta"><a href="#">Artwork</a></li>
		</ul>
		</div>
	</div>

<?php endif; ?>

<form method="post">

	<table class="table table-bordered">
	<tbody>
	<tr><td>
	<div id="works-toolbar" class="btn-toolbar">
		<div id="select-works" class="btn-group">
		  <a class="btn btn-small dropdown-toggle" data-toggle="dropdown" href="#">
			<i class="icon-ok"></i>
			<span class="caret"></span>
		  </a>
		  <ul class="dropdown-menu">
			<li id="select-all-archives"><a href="#"><i class="icon-ok"></i> Select All</a></li>
			<li id="select-no-archives"><a href="#"><i class="icon-remove"></i> Select None</a></li>
		  </ul>
		</div>
		<?php if($authority_can_edit): ?>
		<div class="btn-group">
		<?=$this->form->submit('Create Album', array('onclick' => "this.form.action='/albums/add'", 'class' => 'btn btn-small batch-edit-btn', 'disabled' => 'disabled')); ?>
		</div>
		<div class="btn-group">
		<?=$this->form->submit('Create Exhibition', array('onclick' => "this.form.action='/exhibitions/add'", 'class' => 'btn btn-small batch-edit-btn', 'disabled' => 'disabled')); ?>
		</div>
		<?php endif; ?>
		<div class="btn-group">
		<?=$this->form->submit('Print', array('onclick' => "this.form.action='/works/publish'", 'class' => 'btn btn-small batch-edit-btn', 'disabled' => 'disabled')); ?>
		</div>
	</div>
	</td></tr>
	</tbody>
	</table>

<?php
	$layout = 'table';
	$artworks = \lithium\core\Environment::get('artworks');
	if ($artworks && isset($artworks['layout'])) {
		$layout = $artworks['layout'];
	}

	$inventory = \lithium\core\Environment::get('inventory');
?>

<?php if ($layout == 'compact'): ?>

<?php $count = 1; ?>

<?php foreach($works as $work): ?>

	<?php if ($count % 2 != 0): ?>
		<div class="row">
	<?php endif;?>

		<div class="span2">
				<?php $document = $work->documents('first'); ?>
				<ul class="thumbnails">

					<li class="span2">
					<div style="position:relative;">
					<?php if($document && $document->id): ?>
						<a href="/works/view/<?=$work->archive->slug ?>" class="thumbnail">
						<img style="max-height:120px;" src="/files/<?=$document->view(array('action' => 'small')); ?>" />
						</a>
					<?php else: ?>
						<div class="thumbnail">
							<span class="label">No Preview</span>
					<?php endif; ?>
						<?php if($authority_can_edit): ?>
							<label class="batch-checkbox archives-label works-label" for="Archive-<?=$work->id?>">
							<?=$this->form->checkbox('archives[]', array('id' => "Archive-$work->id", 'value' => $work->id, 'hidden' => false, 'class' => 'archives-checkbox works-checkbox'));?>
								</label>
						<?php endif; ?>
						</div>
						</li>
				</ul>
		</div>

		<div class="span3" style="margin-left: 5px !important;">

			<table class="table table-condensed table-compact">
				<tr>
					<td class="meta">Artist</td>
					<td class="info-artist" colspan="3">
						<ul class="unstyled" style="margin-bottom:0">
						<?php foreach($work->components as $component): ?>
							<li><?=$this->html->link(
								$component->person->archive->names(),
								$this->url(array(
									'Persons::view',
									'slug' => $component->person->archive->slug
								))
							);?></li>
						<?php endforeach; ?>
						</ul>
					</td>
				</tr>
				<tr>
					<td class="meta">Title</td>
					<td class="info-title" colspan="3">
						<strong>
							<?=$this->html->link($work->archive->names(), '/works/view/'.$work->archive->slug); ?>
						</strong>
					</td>
				</tr>
				<tr>
					<td class="meta">Year</td>
					<td class="info-earliest_date"><?=$work->archive->years(); ?></td>
					<?php if ($work->attribute('edition')): ?>
						<td class="meta">Edition</td>
						<td><?=$work->attribute('edition'); ?></td>
					<?php endif; ?>
				</tr>
				<?php if ($dimensions = $work->dimensions()): ?>
				<tr>
					<td class="meta">DIM.</td>
					<td colspan="3"><?=$dimensions; ?></td>
				</tr>
					<?php endif; ?>
				<?php if ($work->materials): ?>
				<tr>
					<td class="meta">Materials</td>
					<td colspan="3"><?=$work->materials; ?></td>
				</tr>
				<?php endif; ?>
				<?php if ($inventory): ?>
				<tr>
					<?php if ($in_time = $work->attribute('in_time')): ?>
					<td class="meta">Recieved</td>
					<td><?=$in_time; ?></td>
					<?php endif; ?>
					<?php if ($work->location): ?>
					<td class="meta">Location</td>
					<td><?=$work->location; ?></td>
					<?php endif; ?>
				</tr>
				<?php endif; ?>
			</table>

		</div>

	<?php if ($count % 2 == 0): ?>
		</div><div class="row"> </div>
	<?php endif;?>

	<?php $count++; ?>

<?php endforeach; ?>

	<?php if ($count % 2 == 0): ?>
		</div>
	<?php endif; ?>

<?php else: ?>

<table class="table table-bordered">

<thead>
	<tr>
<?php if($authority_can_edit): ?>
		<th style="text-align: center;">
			<i class="icon-ok"></i>
		</th>
<?php endif; ?>
		<th>ID</th>
		<th>Image</th>
		<th>Info</th>
		<th style="width: 150px">Materials</th>
		<th>Notes</th>
		<th>Classification</th>
	</tr>
</thead>

<tbody>


<?php foreach($works as $work): ?>

<?php
    $albums = array();
    if (!empty($work->components)) {
        $components = $work->components;

        foreach ($components as $c) {
            if ($c->type == 'albums_works') {
                array_push($albums, $c->album);
            }
        }
    }

?>

<tr>
<?php if($authority_can_edit): ?>
	<td>
		<label class="batch-checkbox archives-label works-label" for="Archive-<?=$work->id?>">
			<?=$this->form->checkbox('archives[]', array('id' => "Archive-$work->id", 'value' => $work->id, 'hidden' => false, 'class' => 'archives-checkbox works-checkbox'));?>
		</label>

	</td>
<?php endif; ?>
	<td class="info-creation_number"><?=$work->creation_number?></td>

	<td align="center" valign="center" style="text-align: center; vertical-align: center; width: 125px;">
		<?php $document = $work->documents('first'); if($document && $document->id) { ?>
			<a href="/works/view/<?=$work->archive->slug ?>">
			<img width="125" height="125" src="/files/<?=$document->view(); ?>" />
			</a>
		<?php } else { ?>
			<span class="label">No Preview</span>
		<?php } ?>
	</td>
    <td class="info-title info-artist info-earliest_date"><?=$this->artwork->caption($work, array('link' => true)); ?></td>
	<td class="info-materials"><?=$work->materials ?></td>
    <td class="info-remarks info-annotation">
        <ul class="unstyled" style="margin-bottom:0">
            <li><?=$this->artwork->notes($work); ?></li>
            <?php if(!empty($albums)): ?>
               <li><strong>Albums</strong>
                <?php foreach($albums as $album): ?>
                   &middot; <?=$this->html->link(
                        $album->archive->name,
                        $this->url(array('Albums::view', 'slug' => $album->archive->slug))
                    );?>
                <?php endforeach; ?>
                </li>
            <?php endif; ?>
        </ul>
    </td>
    <td class="info-classification"><?=$work->archive->classification ?></td>
</tr>

<?php endforeach; ?>

</tbody>
</table>

<?php endif; ?>

<?php if($authority_can_edit): ?>
<?=$this->form->end(); ?>

<script>

$(document).ready(function() {

	$('#select-works #select-all-archives').click(function(event) {
		event.preventDefault();
		$('.works-checkbox').attr('checked', true);
		$('.works-label').addClass('checked');
		handleWorksButtons();
	});

	$('#select-works #select-no-archives').click(function(event) {
		event.preventDefault();
		$('.works-checkbox').attr('checked', false);
		$('.works-label').removeClass('checked');
		handleWorksButtons();
	});

	function handleWorksButtons() {
		if ($('.works-checkbox:checked').length) {
			$('#works-toolbar .batch-edit-btn').removeAttr('disabled');
			$('#works-toolbar .batch-edit-btn').addClass('btn-success');
		} else {
			$('#works-toolbar .batch-edit-btn').attr('disabled', 'disabled');
			$('#works-toolbar .batch-edit-btn').removeClass('btn-success');
		}
	}

	$('.works-checkbox').change(function() {
		if ($(this).attr('checked')) {
			$(this).closest('.works-label').addClass('checked');
		} else {
			$(this).closest('.works-label').removeClass('checked');
		}

		handleWorksButtons();

	});

	handleWorksButtons();

});

</script>
<?php endif; ?>
