<?php 

$this->title('Architecture');

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Architecture','/architectures'); ?>
	</li>

	</ul>

</div>

<div class="actions">
	<ul class="nav nav-tabs">
		<li class="active">
			<?=$this->html->link('Index','/architectures'); ?>
		</li>
		<li>
			<?=$this->html->link('Search','/architectures/search'); ?>
		</li>

	</ul>

	<div class="btn-toolbar">
		<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

			<a class="btn btn-inverse" href="/architectures/add"><i class="icon-plus-sign icon-white"></i> Add a Project</a>
		
		<?php endif; ?>

	</div>
<div>

<?php if(sizeof($architectures) == 0): ?>

	<div class="alert alert-danger">There is no Architecture in the Archive.</div>

	<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

		<div class="alert alert-success">You can add the first Project by clicking the <strong><?=$this->html->link('Add a Project','/architectures/add/'); ?></strong> button.</div>

	<?php endif; ?>

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
