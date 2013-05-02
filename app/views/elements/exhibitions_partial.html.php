<?php if (isset($showBar) && $showBar): ?>

	<div class="navbar">
		<div class="navbar-inner">
		<ul class="nav">
			<li class="meta"><a href="#">Exhibitions</a></li>
		</ul>
		</div>
	</div>

<?php endif; ?>

<?php foreach($exhibitions as $exhibition): ?>
<article>
	<div class="alert">
	<h1><?=$this->html->link($exhibition->title,'/exhibitions/view/'.$exhibition->archive->slug); ?></h1>
	
	<?php 
		date_default_timezone_set('UTC');
		
		$location = $exhibition->location();
		$dates = $exhibition->archive->dates();
		$curator = $exhibition->curator;
	?>
	
	<?php if($location) echo "<p>$location</p>"; ?>
	<?php if($dates) echo "<p>$dates</p>"; ?>
	<?php if($curator) echo "<p>$curator, Curator</p>"; ?>
	
	<?php 
	
		$has_works = isset($exhibition->components[0]->id) ? true : false;
	
		if ($has_works) echo '<span class="badge badge-info">' . sizeof($exhibition->components) . '</span>';
		
	?>
	
	<span class="badge"><?=$exhibition->archive->type ?> Show</span>
	
	</div>
</article>
<?php endforeach; ?>
