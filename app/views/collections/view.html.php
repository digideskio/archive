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

<div id="tools" class="btn-toolbar">

<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

	<div class="action btn-group">

		<a class="btn btn-inverse" href="/collections/edit/<?=$collection->slug ?>">
			<i class="icon-pencil icon-white"></i> Edit Collection
		</a>
		<a class="btn btn-inverse dropdown-toggle" data-toggle="dropdown" href="">
			<span class="caret"></span>
		</a>
		<ul class="dropdown-menu">
			<li>
				<a href="/collections/edit/<?=$collection->slug ?>">
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
	
		
	
	</td>
    <td><?=$this->html->link($cw->work->title,'/works/view/'.$cw->work->slug); ?></td>
    <td><?=$cw->work->years(); ?></td>
    <td><?php echo $cw->work->notes(); ?></td>
    <td><?=$cw->work->classification ?></td>
</tr>
    
<?php endforeach; ?>

</tbody>


<div class="modal fade hide" id="deleteModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Delete Collection</h3>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to permanently delete <strong><?=$collection->title; ?></strong>?</p>
			</div>
			<div class="modal-footer">
			<?=$this->form->create($collection, array('url' => "/collections/delete/$collection->slug", 'method' => 'post')); ?>
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
			<?=$this->form->submit('Delete', array('class' => 'btn btn-danger')); ?>
			<?=$this->form->end(); ?>
	</div>
</div>
