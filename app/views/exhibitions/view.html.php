<?php 

$this->title($exhibition->title);

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Exhibitions','/exhibitions'); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
	<?=$this->html->link($exhibition->title,'/exhibitions/view/'.$exhibition->slug); ?>
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li class="active">
		<a href="#">View</a>
	</li>

	<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>
	
		<li><?=$this->html->link('Edit','/exhibitions/edit/'.$exhibition->slug); ?></li>
	
	<?php endif; ?>

</ul>

	<div class="alert alert-info">
	<h1><?=$exhibition->title ?></h1>
	
	<?php 
		date_default_timezone_set('UTC');
		
		$location = $exhibition->location();
		$dates = $exhibition->dates();
		$curator = $exhibition->curator;
	?>
	
	<?php if($location) echo "<p>$location</p>"; ?>
	<?php if($dates) echo "<p>$dates</p>"; ?>
	<?php if($curator) echo "<p>$curator, Curator</p>"; ?>
	
	<p><?=$exhibition->remarks ?></p>
	
	<p><span class="badge"><?=$exhibition->type ?> Show</span></p>
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

<?php foreach($exhibitions_works as $ew): ?>

<tr>
	<td><?=$ew->work->creation_number?></td>
	
	<td align="center" valign="center" style="text-align: center; vertical-align: center; width: 125px;">
	
		<?php $thumbnail = $ew->work->preview(); $work_slug = $ew->work->slug;
		
			if($thumbnail) {
				echo "<a href='/works/view/$work_slug'>";
				echo "<img width='125' height='125' src='/files/thumb/$thumbnail' />";
				echo "</a>";
			} else {
				echo '<span class="label label-warning">No Image</span>';
			}
		
		?>
	
	</td>
    <td><?=$this->html->link($ew->work->title,'/works/view/'.$ew->work->slug); ?></td>
    <td><?=$ew->work->years(); ?></td>
    <td><?php echo $ew->work->notes(); ?></td>
    <td><?=$ew->work->classification ?></td>
</tr>
    
<?php endforeach; ?>

</tbody>

<?php endif; ?>
