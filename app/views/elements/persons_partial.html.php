<?php if (isset($showBar) && $showBar): ?>

	<div class="navbar">
		<div class="navbar-inner">
		<ul class="nav">
			<li class="meta"><a href="#">Artists</a></li>
		</ul>
		</div>
	</div>

<?php endif; ?>

<?php if ($persons->count() > 20): ?>
	<div style="-moz-column-count:3; -webkit-column-count:3; column-count:3;">
<?php else: ?>
	<div>
<?php endif; ?>

<?php foreach ($persons as $person): ?>

	<p>
		<?=$this->html->link(
			$person->archive->name . ' ' . $person->archive->native_name,
			$this->url(array(
				'controller' => 'persons',
				'action' => 'view',
				'slug' => $person->archive->slug
			))
		); ?>
	</p>

<?php endforeach; ?>
</div>
