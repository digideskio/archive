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
	
		$has_components = isset($exhibition->components[0]->id) ? true : false;
	
		if ($has_components) { 
			$work_count = 0;
			$pub_count = 0;

			foreach ($exhibition->components as $ec) {
				$work_count =  ($ec->type == 'exhibitions_works') ? $work_count + 1 : $work_count;
				$pub_count =  ($ec->type == 'exhibitions_publications') ? $pub_count + 1 : $pub_count;
			}
			
			if ($work_count) {
				echo '<span class="label label-success">';
				echo $work_count;
				echo $work_count == '1' ? ' Artwork' : ' Artworks';
				echo '</span>';
			}
			
			if ($pub_count) {
				echo '<span class="label label-info">';
				echo $pub_count;
				echo $pub_count == '1' ? ' Publication' : ' Publications';
				echo '</span>';
			}
		}
		
	?>
	
	</div>
</article>
