<ul class="nav nav-tabs">
	
	<?php foreach ($tabs as $tab): ?>
		<?php if (isset($tab['active']) && $tab['active'] == true): ?>
		<li class="active">
		<?php else: ?>
		<li>
		<?php endif; ?>
			<?=$this->html->link($tab['title'], $tab['url']); ?>
	</li>
	<?php endforeach; ?>

</ul>

