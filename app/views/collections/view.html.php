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

<div class="actions">

	<ul class="nav nav-tabs">
		<li class="active">
			<a href="#">View</a>
		</li>

		<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>
		
			<li><?=$this->html->link('Edit','/collections/edit/'.$collection->slug); ?></li>
		
		<?php endif; ?>

		<li><?=$this->html->link('History','/collections/history/'.$collection->slug); ?></li>

	</ul>

	<?php if($li3_pdf): ?>

	<div class="btn-toolbar">
		<div class="btn-group">
			<button class="btn btn-inverse dropdown-toggle" data-toggle="dropdown"><i class="icon-print icon-white"></i> Print <span class="caret" style="border-top-color: white; border-bottom-color: white;"></span></button>
			<ul class="dropdown-menu">
				<li><a href="/collections/publish/<?=$collection->slug ?>?view=artwork"><i class="icon-picture"></i> Print Artwork</a></li>
				<li><a href="/collections/publish/<?=$collection->slug ?>?view=images"><i class="icon-camera"></i> Print Images</a></li>
			</ul>
		</div>
	</div>
</div>

	<?php endif; ?>
<?php if($collection->description): ?>
	<div class="alert alert-info">
	<p><?=$collection->description ?></p>
	</div>
<?php endif; ?>

<table class="table table-bordered">

<thead>
	<tr>
		<th>Year</th>
		<th>Image</th>
		<th>Notes</th>
	</tr>
</thead>
		
<tbody>

<?php foreach($collection_works as $cw): ?>

<tr>
	<td class="meta"><?=$cw->work->years(); ?> </td>
	<td align="center" valign="center" style="text-align: center; vertical-align: center; width: 125px;">
	
		<?php 
			$document = $cw->work->documents('first');
		
			if($document->id) {
				$thumbnail = $document->view();
				$work_slug = $cw->work->slug;
				echo "<a href='/works/view/$work_slug'>";
				echo "<img width='125' height='125' src='/files/$thumbnail' />";
				echo "</a>";
			} else {
				echo '<span class="label label-warning">No Image</span>';
			}
		
		?>
	
	</td>
    <td>
		<h5><?=$this->html->link($cw->work->title,'/works/view/'.$cw->work->slug); ?></h5>
		<p><small><?php echo $cw->work->caption(); ?></small></p>
		<blockquote class="pull-right"><?=$cw->work->annotation ?></blockquote>
</tr>
    
<?php endforeach; ?>

</tbody>
