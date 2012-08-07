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

<div id="tools" class="btn-toolbar">

<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

	<div class="action btn-group">

		<a class="btn btn-inverse" href="/exhibitions/edit/<?=$collection->slug ?>">
			<i class="icon-pencil icon-white"></i> Edit Exhibition
		</a>
		<a class="btn btn-inverse dropdown-toggle" data-toggle="dropdown" href="">
			<span class="caret"></span>
		</a>
		<ul class="dropdown-menu">
			<li>
				<a href="/exhibitions/edit/<?=$collection->slug ?>">
					<i class="icon-pencil"></i> Edit
				</a>
			</li>
			<li>
				<a data-toggle="modal" href="#deleteModal">
					<i class="icon-trash"></i> Delete
				</a>
			</li>
		</ul>

	</div>
	
<?php endif; ?>

</div>

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


<div class="modal fade hide" id="deleteModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Delete Exhibition</h3>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to permanently delete <strong><?=$collection->title; ?></strong>? This will not delete any of the artworks inside. It will only remove this Exhibition from the listings.</p>
			</div>
			<div class="modal-footer">
			<?=$this->form->create($collection, array('url' => "/exhibitions/delete/$collection->slug", 'method' => 'post')); ?>
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
			<?=$this->form->submit('Delete', array('class' => 'btn btn-danger')); ?>
			<?=$this->form->end(); ?>
	</div>
</div>
