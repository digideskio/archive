<?php if (isset($showBar) && $showBar): ?>

<div class="navbar">
	<div class="navbar-inner">
	<ul class="nav">
		<li class="meta"><a href="#">Links</a></li>
	</ul>
	</div>
</div>

<?php endif; ?>

<table class="table table-striped table-bordered">

<tbody>

<?php foreach ($links as $link): ?>

<tr <?php if (isset($saved) && $link->id == $saved) { echo 'class="success"'; } ?>>
	<td>
		<?php $title = $link->title ?: $link->url; ?>

		<p class="lead" style="margin-bottom: 0">
			<?=$this->html->link($title, $this->url(array('Links::view', 'id' => $link->id))); ?>

			<small style="font-size: smaller;">
				<?php $date = date('Y-m-d', strtotime($link->date_created)); ?>
					<?=$date ?>
			</small>

			<?php $has_archives = $link->archives_links->count(); ?>

			<?php if ($has_archives): ?>

				<?php
					$work_count = 0;
					$pub_count = 0;
					$exhibit_count = 0;
				?>

				<?php foreach ($link->archives_links as $archive_link): ?>

					<?php
						switch ($archive_link->archive->controller) {
							case 'works':
								$work_count++;
								break;
							case 'publications':
								$pub_count++;
								break;
							case 'exhibitions':
								$exhibit_count++;
								break;
						}
					?>

				<?php endforeach; ?>
	
				<small style="font-size: smaller;">
				<?php if ($work_count > 0): ?>
					<i class="icon-picture"></i>
				<?php endif; ?>

				<?php if ($pub_count > 0): ?>
					<i class="icon-book"></i>
				<?php endif; ?>

				<?php if ($exhibit_count > 0): ?>
					<i class="icon-eye-open"></i>
				<?php endif; ?>
				</small>

			<?php endif; ?>

			<?php if (isset($saved) && $link->id == $saved): ?>
				<span class="label">Saved</span>
			<?php endif; ?>
		</p>

		<p>
			<strong>
				<?=$this->html->link($link->url) ?>
			</strong>
		</p>
	
		<?php if (!empty($link->description)): ?>
		<blockquote><?=$link->description ?></blockquote>
		<?php endif; ?>

	</td>
</tr>

<?php endforeach; ?>

</tbody>

</table>
