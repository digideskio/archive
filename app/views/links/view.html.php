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

<p>
<small style="font-size: smaller;">
	Added <?=$link->date_created ?>
</small>
</p>


</div>

	<?php 
		$has_works = sizeof($works) > 0 ? true : false; 
		$has_exhibitions = sizeof($exhibitions_links) > 0 ? true : false; 
		$has_publications = sizeof($publications_links) > 0 ? true : false; 
	?>

	<?php if ($has_exhibitions): ?>

		<div class="navbar">
			<div class="navbar-inner">
			<ul class="nav">
				<li class="meta"><a href="#">Exhibitions</a></li>
			</ul>
			</div>
		</div>

<?php foreach($exhibitions_links as $el): ?>
<article>
	<div class="alert">
	<h1><?=$this->html->link($el->exhibition->title,'/exhibitions/view/'.$el->exhibition->slug); ?></h1>
	
	<?php 
		date_default_timezone_set('UTC');
		
		$location = $this->exhibition->location($exhibition->archive, $exhibition);
		$dates = $el->exhibition->dates();
		$curator = $el->exhibition->curator;
	?>
	
	<?php if($location) echo "<p>$location</p>"; ?>
	<?php if($dates) echo "<p>$dates</p>"; ?>
	<?php if($curator) echo "<p>$curator, Curator</p>"; ?>
	
	<span class="badge"><?=$el->exhibition->type ?> Show</span>
	
	</div>
</article>
<?php endforeach; ?>

	<?php endif;?>

	<?php if ($has_works): ?>

		<?=$this->partial->works(array('works' => $works, 'showBar' => true)); ?>

	<?php endif; ?>

	<?php if ($has_publications): ?>

		<div class="navbar">
			<div class="navbar-inner">
			<ul class="nav">
				<li class="meta"><a href="#">Publications</a></li>
			</ul>
			</div>
		</div>

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

<?php foreach($publications_links as $pl): ?>

<tr>
	<td>
		<?=$pl->publication->publication_number?>
			<?php 
				if($pl->publication->storage_number) {
					echo "<br/><span class='label label-success'>" . $pl->publication->storage_number. "</span>";
				}
				if($pl->publication->storage_location) {
					echo "<br/><span class='label'>" . $pl->publication->storage_location ."</span>";
				}

				$documents = $pl->publication->documents('all');
				if(sizeof($documents) > 0) {
					echo "<br/><span class='badge badge-info'>" . sizeof($documents) . "</span>";
				}
			?>
	
	</td>
	<!--<td align="center" valign="center" style="text-align: center; vertical-align: center; width: 125px;">
		<?php $document = $pl->publication->documents('first'); if(isset($document->id)) { ?>	
			<a href="/publications/view/<?=$pl->publication->slug?>">
			<img width="125" height="125" src="/files/<?=$document->view(); ?>" />
			</a>
		<?php } else { ?>
			<span class="label label-warning">No Image</span>
		<?php } ?>
	</td>-->
	<td><?=$pl->publication->byline(); ?></td>
	
    <td><?=$this->html->link($pl->publication->title,'/publications/view/'.$pl->publication->slug); ?></td>
    <td><?=$pl->publication->dates(); ?></td>
    <td><?=$pl->publication->publisher ?></td>
</tr>
    
<?php endforeach; ?>
	<?php endif; ?>

</tbody>
</table>
