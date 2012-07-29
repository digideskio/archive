<?php 

$this->title('Artwork');

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Artwork','/works'); ?>
	</li>

	</ul>

</div>

<div id="tools" class="btn-toolbar">

<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

	<div class="action btn-group">

		<a class="btn btn-inverse" href="/works/add/">
			<i class="icon-plus-sign icon-white"></i> Add Artwork
		</a>

	</div>

<?php endif; ?>

</div>

<?php if(sizeof($works) == 0): ?>

	<div class="alert alert-danger">There is no Artwork in the Archive.</div>

	<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

		<div class="alert alert-success">You can add the first Artwork by clicking the <strong><?=$this->html->link('Add Artwork','/works/add/'); ?></strong> button.</div>

	<?php endif; ?>

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

<?php foreach($works as $work): ?>

<tr>
	<td><?=$work->creation_number?></td>
	
	<td align="center" valign="center" style="text-align: center; vertical-align: center; width: 125px;">
		<?php $wd = $work->works_documents[0]; if($wd->id) { ?>	
			<a href="/works/view/<?=$work->slug?>">
			<img width="125" height="125" src="uploads/<?=$wd->preview(); ?>" />
			</a>
		<?php } else { ?>
			<span class="label label-warning">No Image</span>
		<?php } ?>
	</td>
    <td><?=$this->html->link($work->title,'/works/view/'.$work->slug); ?></td>
    <td><?=$work->years(); ?></td>
    <td><?php echo $work->notes(); ?></td>
    <td><?=$work->classification ?></td>
</tr>
    
<?php endforeach; ?>
    
</tbody>
</table>
