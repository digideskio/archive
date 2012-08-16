<?php 

$this->title('Exhibitions');

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Exhibitions','/exhibitions'); ?>
	</li>

	</ul>

</div>

<div id="tools" class="btn-toolbar">

<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

	<div class="action btn-group">

		<a class="btn btn-inverse" href="/exhibitions/add/">
			<i class="icon-plus-sign icon-white"></i> Add Exhibition
		</a>

	</div>

<?php endif; ?>

</div>

<?php if(sizeof($exhibitions) == 0): ?>

	<div class="alert alert-danger">There are no Exhibitions in the Archive.</div>

	<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

		<div class="alert alert-success">You can create the first Exhibition by clicking the <strong><?=$this->html->link('Add a Exhibition','/exhibitions/add/'); ?></strong> button.</div>

	<?php endif; ?>

<?php endif; ?>

<?php foreach($exhibitions as $exhibition): ?>
<article>
	<div class="alert">
	<h1><?=$this->html->link($exhibition->title,'/exhibitions/view/'.$exhibition->slug); ?></h1>
	
	<?php 
		date_default_timezone_set('UTC');
		
		$location = $exhibition->location();
		$dates = $exhibition->dates();
		$curator = $exhibition->curator;
	?>
	
	<?php if($location) echo "<p>$location</p>"; ?>
	<?php if($dates) echo "<p>$dates</p>"; ?>
	<?php if($curator) echo "<p>$curator, Curator</p>"; ?>
	
	<?php 
	
		$has_works = isset($exhibition->exhibitions_works[0]->id) ? true : false;
	
		if ($has_works) echo '<span class="badge badge-info">' . sizeof($exhibition->exhibitions_works) . '</span>';
		
	?>
	
	<span class="badge"><?=$exhibition->type ?> Show</span>
	
	</div>
</article>
<?php endforeach; ?>
