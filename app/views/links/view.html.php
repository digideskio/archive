<?php 

$title = $link->title ?: "Link";

$this->title($title);

?>


<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Links','/Links'); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
	<?=$this->html->link($title,'/links/view/'.$link->id); ?>
	</li>

	</ul>

</div>

<div class="actions">

	<ul class="nav nav-tabs">
		<li class="active">
			<?=$this->html->link('View','/links/view/'.$link->id); ?>
		</li>

		<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>
		<li>
			<?=$this->html->link('Edit','/links/edit/'.$link->id); ?>
		</li>
		<?php endif; ?>
	</ul>

</div>

<div class="alert alert-info">

<h2><?=$link->title ?></h2>

<h4><?=$this->html->link($link->url, $link->url); ?></h4><br/>

<p><?=$link->description ?></p>

<p>Added <?=$link->date_created ?></p>

</div>

	<?php 
		$has_works = sizeof($works_links) > 0 ? true : false; 
	?>

	<?php if ($has_works): ?>

		<div class="navbar">
			<div class="navbar-inner">
			<ul class="nav">
				<li class="meta"><a href="#">Artwork</a></li>
			</ul>
			</div>
		</div>

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

<?php foreach($works_links as $wl): ?>

<tr>
	<td><?=$wl->work->creation_number?></td>
	
	<td align="center" valign="center" style="text-align: center; vertical-align: center; width: 125px;">
		<?php $document = $wl->work->documents('first'); if($document->id) { ?>	
			<a href="/works/view/<?=$work->slug?>">
			<img width="125" height="125" src="/files/<?=$document->view(); ?>" />
			</a>
		<?php } else { ?>
			<span class="label label-warning">No Image</span>
		<?php } ?>
	</td>
    <td><?=$this->html->link($wl->work->title,'/works/view/'.$wl->work->slug); ?></td>
    <td><?=$wl->work->years(); ?></td>
    <td><?php echo $wl->work->notes(); ?></td>
    <td><?=$wl->work->classification ?></td>
</tr>

<?php endforeach; ?>
	<?php endif; ?>
