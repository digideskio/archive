<?php if (isset($showBar) && $showBar): ?>

	<div class="navbar">
		<div class="navbar-inner">
		<ul class="nav">
			<li class="meta"><a href="#">Artwork</a></li>
		</ul>
		</div>
	</div>

<?php endif; ?>

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
				
					<?php if($document && $document->id): ?>
						<li class="span2">
							<a href="/works/view/<?=$work->archive->slug ?>" class="thumbnail">
							<img style="max-height:120px;" src="/files/<?=$document->view(array('action' => 'small')); ?>" />
							</a>
						</li>
					<?php else: ?>
						<li class="span2">
							<div class="thumbnail">
								<span class="label">No Preview</span>
							</div>
						</li>
					<?php endif; ?>

				</ul>
		</div>

		<div class="span3" style="margin-left: 5px !important;">

			<table class="table table-condensed table-compact">
				<tr>
					<td class="meta">Artist</td>
					<td class="info-artist" colspan="3"><strong><?=$work->artists(); ?></strong></td>
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
					<td class="meta">Edition</td>
					<td><?=$work->attribute('edition'); ?></td>
				</tr>
				<tr>
					<td class="meta">Size</td>
					<td colspan="3"><?=$work->dimensions(); ?></td>
				</tr>
				<tr>
					<td class="meta">Materials</td>
					<td colspan="3"><?=$work->materials; ?></td>
				</tr>
				<?php if ($inventory): ?>
				<tr>
					<td class="meta">Recieved</td>
					<td><?=$work->attribute('in_time'); ?></td>
					<td class="meta">Location</td>
					<td><?=$work->location; ?></td>
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

<tr>
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
	<td class="info-title info-artist info-earliest_date"><?=$this->artwork->caption($work->archive, $work, array('link' => true)); ?></td>
	<td class="info-materials"><?=$work->materials ?></td>
    <td class="info-remarks info-annotation"><?php echo $work->notes(); ?></td>
    <td class="info-classification"><?=$work->archive->classification ?></td>
</tr>
    
<?php endforeach; ?>
    
</tbody>
</table>

<?php endif; ?>
