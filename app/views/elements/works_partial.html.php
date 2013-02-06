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
		<th>Title</th>
		<th>Year</th>
		<th>Dimensions</th>
		<th style="width: 150px">Materials</th>
		<th>Notes</th>
		<th>Classification</th>
	</tr>
</thead>
		
<tbody>

<?php foreach($works as $work): ?>

<tr>
	<td><?=$work->creation_number?></td>
	
	<td align="center" valign="center" style="text-align: center; vertical-align: center; width: 125px;">
		<?php $document = $work->documents('first'); if($document && $document->id) { ?>	
			<a href="/works/view/<?=$work->slug?>">
			<img width="125" height="125" src="/files/<?=$document->view(); ?>" />
			</a>
		<?php } else { ?>
			<span class="label">No Preview</span>
		<?php } ?>
	</td>
    <td><?=$this->html->link($work->title,'/works/view/'.$work->slug); ?></td>
    <td><?=$work->years(); ?></td>
	<td><?php echo(implode('<br/>', array_filter(array(str_replace(', ', '<br/>', $work->dimensions()), $work->measurement_remarks)))); ?></td>
	<td><?=$work->materials ?></td>
    <td><?php echo $work->notes(); ?></td>
    <td><?=$work->classification ?></td>
</tr>
    
<?php endforeach; ?>
    
</tbody>
</table>
