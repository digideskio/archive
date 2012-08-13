<?php 

$this->title($collection->title);

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Exhibitions','/exhibitions'); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
	<?=$this->html->link($collection->title,'/exhibitions/view/'.$collection->slug); ?>
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li class="active">
		<a href="#">View</a>
	</li>

	<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>
	
		<li><?=$this->html->link('Edit','/exhibitions/edit/'.$collection->slug); ?></li>
	
	<?php endif; ?>

</ul>

	<div class="alert alert-info">
	<h1><?=$collection->title ?></h1>
	
	<?php 
		date_default_timezone_set('UTC');
		
		$location = $collection->exhibition->location();
		$dates = $collection->date->dates();
		$curator = $collection->exhibition->curator;
	?>
	
	<?php if($location) echo "<p>$location</p>"; ?>
	<?php if($dates) echo "<p>$dates</p>"; ?>
	<?php if($curator) echo "<p>$curator, Curator</p>"; ?>
	
	<p><?=$collection->exhibition->remarks ?></p>
	
	<p><span class="badge"><?=$collection->exhibition->type ?> Show</span></p>
	</div>
	
<?php if($total > 0): ?>

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

<?php foreach($collections_works as $cw): ?>

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

<?php endif; ?>
