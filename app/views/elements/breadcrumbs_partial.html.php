<div id="location" class="row-fluid">

	<ul class="breadcrumb">

	<?php for($i = 0; $i < sizeof($crumbs); $i++): ?>

		<?php $crumb = $crumbs[$i]; ?>

		<?php if (isset($crumb['active']) && $crumb['active'] == true): ?>
		<li class="active">
		<?php else: ?>
		<li>
		<?php endif; ?>
			<?php if (isset($crumb['url']) && !empty($crumb['url'])): ?>
				<?=$this->html->link($crumb['title'], $crumb['url']); ?>
			<?php else: ?>
				<?=$crumb['title'] ?>
			<?php endif; ?>

			<?php if ($i < sizeof($crumbs) - 1): ?>
				<span class="divider">/</span>
			<?php endif; ?>
		</li>

	<?php endfor; ?>

	</ul>

</div>
