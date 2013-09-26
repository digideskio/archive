Progress Report: <?=$dates['start'] ?> - <?=$dates['end'] ?> 


<?php if ($updates->count()): ?>
# Updates
	<?php foreach ($updates as $update): ?>
	<?php
		$update_time = new DateTime($update->date_created);

		if (isset($tz)) {
			$update_time->setTimeZone($tz);
		}
		$update_display = $update_time->format("d M Y");
	?>

(<?=$update_display ?>) <?=$update->subject ?> - <?php echo $update->body; ?>

	<?php endforeach; ?>
<?php endif; ?>
<?php if ($archives->count()): ?>
	<?php $last_controller = ''; ?>
	<?php foreach ($archives as $archive): ?>
<?php if ($archive->controller != $last_controller): ?> 

# <?php echo \lithium\util\Inflector::humanize($archive->controller); ?>

<?php endif; ?>
<?php $last_controller = $archive->controller; ?>
* <?php echo $archive->name; ?> <?php if ($archive->classification): ?><?=$archive->classification ?><?php endif; ?> (<?=$archive->user->name; ?>)
<?php endforeach; ?>
<?php endif; ?>
