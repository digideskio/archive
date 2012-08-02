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

<div id="tools" class="btn-toolbar">

<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

	<div class="action btn-group">

		<a class="btn btn-inverse" href="/exhibitions/edit/<?=$exhibition->slug ?>">
			<i class="icon-pencil icon-white"></i> Edit Exhibition
		</a>
		<a class="btn btn-inverse dropdown-toggle" data-toggle="dropdown" href="">
			<span class="caret"></span>
		</a>
		<ul class="dropdown-menu">
			<li>
				<a href="/exhibitions/edit/<?=$exhibition->slug ?>">
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
	<h1><?=$exhibition->title ?></h1>
	
	<?php 
		date_default_timezone_set('UTC');
		
		$opening_date = date('d M Y', strtotime($exhibition->earliest_date));
		$closing_date = date('d M Y', strtotime($exhibition->latest_date));
	?>
	
	<?php if($exhibition->venue) echo "<p><strong>$exhibition->venue</strong></p>"; ?>
	<?php if($exhibition->city) echo "<p>$exhibition->city</p>"; ?>
	<?php if($exhibition->country) echo "<p>$exhibition->country</p>"; ?>
	<?php if($exhibition->earliest_date) echo "<p>Opening Date: $opening_date</p>"; ?>
	<?php if($exhibition->latest_date) echo "<p>Closing Date: $closing_date</p>"; ?>
	<?php if($exhibition->curator) echo "<p>$exhibition-curator, Curator</p>"; ?>
	
	<p><?=$exhibition->remarks ?></p>
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

<?php foreach($exhibition_works as $ew): ?>

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


<div class="modal fade hide" id="deleteModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Delete Exhibition</h3>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to permanently delete <strong><?=$exhibition->title; ?></strong>? This will not delete any of the artworks inside. It will only remove this Exhibition from the listings.</p>
			</div>
			<div class="modal-footer">
			<?=$this->form->create($exhibition, array('url' => "/exhibitions/delete/$exhibition->slug", 'method' => 'post')); ?>
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
			<?=$this->form->submit('Delete', array('class' => 'btn btn-danger')); ?>
			<?=$this->form->end(); ?>
	</div>
</div>
