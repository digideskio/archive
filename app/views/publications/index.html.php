<?php 

$type = isset($options['type']) ? $options['type'] : NULL;

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

		<li <?php if (!$type) { echo 'class="active"'; } ?>>
			<?=$this->html->link('Index','/publications'); ?>
		</li>

		<?php foreach($publications_types as $pt): ?>
			<li <?php if ($pt == $type) { echo 'class="active"'; } ?>>
				<?=$this->html->link($pt,'/publications?type='.$pt); ?> 
			</li>
		<?php endforeach; ?>

		<li>
			<?=$this->html->link('Search','/publications/search'); ?>
		</li>

	</ul>
	<div class="btn-toolbar">
		<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

				<a class="btn btn-inverse" href="/publications/add"><i class="icon-plus-sign icon-white"></i> Add a Publication</a>
		
		<?php endif; ?>

	</div>
<div>

<?php if($total == 0 && !$type): ?>

	<div class="alert alert-danger">There are no Publications in the Archive.</div>

	<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

		<div class="alert alert-success">You can add the first Publication by clicking the <strong><?=$this->html->link('Add a Publication','/publications/add/'); ?></strong> button.</div>

	<?php endif; ?>

<?php endif; ?>

<?php if($total > 0): ?>

<table class="table table-bordered">

<thead>
	<tr>
		<th><i class="icon-barcode"></i></th>
		<th>Author</th>
		<th>Title</th>
		<th style="width: 100px;">Date</th>
		<th>Publisher</th>
	</tr>
</thead>
		
<tbody>

<?php foreach($publications as $publication): ?>

<tr>
	<td>
		<?=$publication->publication_number?>
			<?php 
				if($publication->storage_number) {
					echo "<br/><span class='label label-success'>$publication->storage_number</span>";
				}
				if($publication->storage_location) {
					echo "<br/><span class='label'>$publication->storage_location</span>";
				}

				$documents = $publication->documents('all');
				if(sizeof($documents) > 0) {
					echo "<br/><span class='badge badge-info'>" . sizeof($documents) . "</span>";
				}
			?>
	
	</td>
	<!--<td align="center" valign="center" style="text-align: center; vertical-align: center; width: 125px;">
		<?php $document = $publication->documents('first'); if(isset($document->id)) { ?>	
			<a href="/publications/view/<?=$publication->slug?>">
			<img width="125" height="125" src="/files/<?=$document->view(); ?>" />
			</a>
		<?php } else { ?>
			<span class="label label-warning">No Image</span>
		<?php } ?>
	</td>-->
	<td><?=$publication->author?></td>
	
    <td><?=$this->html->link($publication->title,'/publications/view/'.$publication->slug); ?></td>
    <td><?=$publication->dates(); ?></td>
    <td><?=$publication->publisher ?></td>
</tr>
    
<?php endforeach; ?>
    
</tbody>
</table>

<div class="pagination">
    <ul>
	<?php $query = $type ? "?type=$type" : ''; ?>
    <?php if($page > 1):?>
	 <?php $prev = $page - 1; ?>
    <li><?=$this->html->link('«', "/publications/pages/$prev$query");?></li> 
    <?php endif;?> 
        <li class="active"><a href=""><?=$page ?> / <?= ceil($total / $limit); ?></a></li>
     <?php if($total > ($limit * $page)):?>
	 <?php $next = $page + 1; ?>
     <li><?=$this->html->link('»', "/publications/pages/$next$query");?></li>
     <?php endif;?> 
    </ul>
</div>

<?php endif; ?>
