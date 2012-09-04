<?php 

$this->title('Publications');

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Publications','/publications'); ?>
	</li>

	</ul>

</div>

<div class="actions">
	<ul class="nav nav-tabs">
		<li class="active">
			<?=$this->html->link('Index','/publications'); ?>
		</li>

	</ul>
	<div class="btn-toolbar">
		<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

				<a class="btn btn-inverse" href="/publications/add"><i class="icon-plus-sign icon-white"></i> Add a Publication</a>
		
		<?php endif; ?>

	</div>
<div>

<?php if(sizeof($publications) == 0): ?>

	<div class="alert alert-danger">There are no Publications in the Archive.</div>

	<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

		<div class="alert alert-success">You can add the first Publication by clicking the <strong><?=$this->html->link('Add a Publication','/publications/add/'); ?></strong> button.</div>

	<?php endif; ?>

<?php endif; ?>

<table class="table table-bordered">


<thead>
	<tr>
		<th><i class="icon-barcode"></i></th>
		<th>Author</th>
		<th>Title</th>
		<th>Year</th>
		<th>Publisher</th>
	</tr>
</thead>
		
<tbody>

<?php foreach($publications as $publication): ?>

<tr>
	<td>
		<?=$publication->publication_number?>
			<?php 
				if($publication->location_code) {
					echo "<br/><span class='label label-success'>$publication->location_code</span>";
				}
				if($publication->location) {
					echo "<br/><span class='label'>$publication->location</span>";
				}
			?>
	
	</td>
	<td><?=$publication->author?></td>
	
    <td><?=$this->html->link($publication->title,'/publications/view/'.$publication->slug); ?></td>
    <td><?=$publication->years(); ?></td>
    <td><?=$publication->publisher ?></td>
</tr>
    
<?php endforeach; ?>
    
</tbody>
</table>
