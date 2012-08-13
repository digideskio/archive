<?php 

$this->title($collection->title);

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Collections','/collections'); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
	<?=$this->html->link($collection->title,'/collections/view/'.$collection->slug); ?>
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li class="active">
		<a href="#">View</a>
	</li>

	<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>
	
		<li><?=$this->html->link('Edit','/collections/edit/'.$collection->slug); ?></li>
	
	<?php endif; ?>

</ul>

<?php if($collection->description): ?>
	<div class="alert alert-info">
	<p><?=$collection->description ?></p>
	</div>
<?php endif; ?>

<table class="table table-bordered">

<thead>
	<tr>
		<th>ID</th>
		<th>Image</th>
		<th>Title</th>
		<th>Year</th>
		<th>Notes</th>
		<th>Classification</th>
	</tr>
</thead>
		
<tbody>

<?php foreach($collection_works as $cw): ?>

<tr>
	<td><?=$cw->work->creation_number?></td>
	
	<td align="center" valign="center" style="text-align: center; vertical-align: center; width: 125px;">
	
		<?php $thumbnail = $cw->work->preview(); $work_slug = $cw->work->slug;
		
			if($thumbnail) {
				echo "<a href='/works/view/$work_slug'>";
				echo "<img width='125' height='125' src='/files/thumb/$thumbnail' />";
				echo "</a>";
			} else {
				echo '<span class="label label-warning">No Image</span>';
			}
		
		?>
	
	</td>
    <td><?=$this->html->link($cw->work->title,'/works/view/'.$cw->work->slug); ?></td>
    <td><?=$cw->work->years(); ?></td>
    <td><?php echo $cw->work->notes(); ?></td>
    <td><?=$cw->work->classification ?></td>
</tr>
    
<?php endforeach; ?>

</tbody>
