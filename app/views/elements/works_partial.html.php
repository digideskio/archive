<?php if (isset($showBar) && $showBar): ?>

	<div class="navbar">
		<div class="navbar-inner">
		<ul class="nav">
			<li class="meta"><a href="#">Artwork</a></li>
		</ul>
		</div>
	</div>

<?php endif; ?>

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
