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
