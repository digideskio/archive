<?php $display_class = $exhibition->archive->type == 'Group' ? 'breadcrumb' : 'alert'; ?>

<article>
	<div class="<?=$display_class?>">
	<h3>
		<?=$this->html->link($exhibition->archive->name,'/exhibitions/view/'.$exhibition->archive->slug); ?>
		<?=$exhibition->venue?>&nbsp;<small class="meta"><?=$exhibition->archive->type?>&nbsp;Show</small>
	</h3>

	<?php
		date_default_timezone_set('UTC');

		$location = $this->escape($this->exhibition->location($exhibition->archive, $exhibition));
		$dates = $this->escape($exhibition->archive->dates());
		$curator = $this->escape($exhibition->curator);
	?>

	<?php if($location) echo "<p>$location</p>"; ?>
	<?php if($dates) echo "<p>$dates</p>"; ?>
	<?php if($curator) echo "<p>$curator, Curator</p>"; ?>

	<?php if($exhibition->remarks): ?>
		<p class="muted">
			<?php echo nl2br($this->escape($exhibition->remarks)); ?>
		</p>
		<hr/>
	<?php endif; ?>

	<p>

	<?php if ($exhibition->archive->published): ?>
		<span class="label label-success">Published</span>
	<?php endif; ?>

	<?php

		$has_components = isset($exhibition->components) && $exhibition->components->count() ? true : false;

		if ($has_components) {
			$work_count = 0;
			$pub_count = 0;

			foreach ($exhibition->components as $ec) {
				$work_count =  ($ec->type == 'exhibitions_works') ? $work_count + 1 : $work_count;
				$pub_count =  ($ec->type == 'exhibitions_publications') ? $pub_count + 1 : $pub_count;
			}

			if ($work_count) {
				echo '<span class="label label-info">';
				echo $work_count;
				echo $work_count == '1' ? ' Artwork' : ' Artworks';
				echo '</span>';
				echo ' ';
			}

			if ($pub_count) {
				echo '<span class="label label-inverse">';
				echo $pub_count;
				echo $pub_count == '1' ? ' Publication' : ' Publications';
				echo '</span>';
			}
		}

	?>

	</p>

	<?php
		$has_links = !empty($exhibition->archives_links) && $exhibition->archives_links->count() ? true : false;
	?>

		<?php if ($has_links): ?>

			<?php foreach ($exhibition->archives_links as $al): ?>
				<?=$this->link->caption($al->link); ?>
			<?php endforeach; ?>
		<?php endif; ?>

	</div>
</article>
