<?php if (isset($showBar) && $showBar): ?>

	<div class="navbar">
		<div class="navbar-inner">
		<ul class="nav">
			<li class="meta"><a href="#">Publications</a></li>
		</ul>
		</div>
	</div>

<?php endif; ?>

<table class="table table-bordered">

<thead>
	<tr>
		<th><i class="icon-barcode"></i></th>
		<th>Author</th>
		<th>Title</th>
		<th style="width: 100px;">Date</th>
		<th>Publisher</th>
	</tr>
</thead>
		
<tbody>

<?php foreach($publications as $publication): ?>

<tr>
	<td class="info-storage_location info-storage_number info-publication_number">
		<?=$publication->publication_number?>
			<?php 
				if($publication->storage_number) {
					echo "<br/><span class='label label-success'>$publication->storage_number</span>";
				}
				if($publication->storage_location) {
					echo "<br/><span class='label'>$publication->storage_location</span>";
				}

				$documents = $publication->documents('all');
				if(sizeof($documents) > 0) {
					echo "<br/><span class='badge badge-info'>" . sizeof($documents) . "</span>";
				}
			?>
	
	</td>
	<!--<td align="center" valign="center" style="text-align: center; vertical-align: center; width: 125px;">
		<?php $document = $publication->documents('first'); if(isset($document->id)) { ?>	
			<a href="/publications/view/<?=$publication->archive->slug?>">
			<img width="125" height="125" src="/files/<?=$document->view(); ?>" />
			</a>
		<?php } else { ?>
			<span class="label label-warning">No Image</span>
		<?php } ?>
	</td>-->
	<td class="info-author info-editor"><?=$publication->byline(); ?></td>
	
    <td class="info-title"><?=$this->html->link($publication->title,'/publications/view/'.$publication->archive->slug); ?></td>
    <td class="info-earliest_date"><?=$publication->archive->dates(); ?></td>
    <td class="info-publisher"><?=$publication->publisher ?></td>
</tr>
    
<?php endforeach; ?>
    
</tbody>
</table>
