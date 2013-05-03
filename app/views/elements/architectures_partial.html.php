<?php if (isset($showBar) && $showBar): ?>

	<div class="navbar">
		<div class="navbar-inner">
		<ul class="nav">
			<li class="meta"><a href="#">Architecture</a></li>
		</ul>
		</div>
	</div>

<?php endif; ?>


<table class="table table-bordered">

<thead>
	<tr>
		<th>Image</th>
		<th>Title</th>
		<th>Year</th>
		<th>Notes</th>
	</tr>
</thead>
		
<tbody>

<?php foreach($architectures as $architecture): ?>

<tr>
	
	<td align="center" valign="center" style="text-align: center; vertical-align: center; width: 125px;">
		<?php $document = $architecture->documents('first'); if($document && $document->id) { ?>	
			<a href="/architectures/view/<?=$architecture->archive->slug?>">
			<img width="125" height="125" src="/files/<?=$document->view(); ?>" />
			</a>
		<?php } else { ?>
			<span class="label label-warning">No Image</span>
		<?php } ?>
	</td>
    <td class="info-title"><?=$this->html->link($architecture->title,'/architectures/view/'.$architecture->archive->slug); ?></td>
    <td class="info-earliest_date"><?=$architecture->archive->years(); ?></td>
    <td class="info-architect info-location info-city info-country info-status"><?=$this->architecture->caption($architecture->archive, $architecture); ?></td>
</tr>
    
<?php endforeach; ?>
    
</tbody>
</table>
