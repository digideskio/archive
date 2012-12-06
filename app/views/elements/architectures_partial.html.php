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
			<a href="/architectures/view/<?=$architecture->slug?>">
			<img width="125" height="125" src="/files/<?=$document->view(); ?>" />
			</a>
		<?php } else { ?>
			<span class="label label-warning">No Image</span>
		<?php } ?>
	</td>
    <td><?=$this->html->link($architecture->title,'/architectures/view/'.$architecture->slug); ?></td>
    <td><?=$architecture->years(); ?></td>
    <td><?php echo $architecture->caption(); ?></td>
</tr>
    
<?php endforeach; ?>
    
</tbody>
</table>
