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

<?=$this->partial->exhibition(compact('exhibition')); ?>

<?php endforeach; ?>
