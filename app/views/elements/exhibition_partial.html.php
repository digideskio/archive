<?php $display_class = $exhibition->archive->type == 'Group' ? 'breadcrumb' : 'alert'; ?>

<article>
	<div class="<?=$display_class?>">
	<h3>
		<?=$this->html->link($exhibition->title,'/exhibitions/view/'.$exhibition->archive->slug); ?>
		<?=$exhibition->venue?>&nbsp;<small class="meta"><?=$exhibition->archive->type?>&nbsp;Show</small>
	</h3>
	
	<?php 
		date_default_timezone_set('UTC');
		
		$location = $this->exhibition->location($exhibition->archive, $exhibition);
		$dates = $exhibition->archive->dates();
		$curator = $exhibition->curator;
	?>
	
	<?php if($location) echo "<p>$location</p>"; ?>
	<?php if($dates) echo "<p>$dates</p>"; ?>
	<?php if($curator) echo "<p>$curator, Curator</p>"; ?>
	
	<?php 
	
		$has_works = isset($exhibition->components[0]->id) ? true : false;
	
		if ($has_works) { 
			echo '<span class="label label-success">';
			echo sizeof($exhibition->components);
			echo sizeof($exhibition->components) == '1' ? ' Artwork' : ' Artworks';
			echo '</span>';
		}
		
	?>
	
	</div>
</article>
