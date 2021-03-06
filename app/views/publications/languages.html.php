<?php

$this->title('Publication Languages');

$authority_can_edit = $this->authority->canEdit();

?>

<div id="location" class="row-fluid">

	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Publication','/publications'); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
		Languages
	</li>

	</ul>

</div>

<div class="actions">
	<ul class="nav nav-tabs">
		<li>
			<a href="/publications">Index</a>
		</li>

		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">Filter <b class="caret"></b></a>
			<ul class="dropdown-menu">
		<?php foreach($pub_classifications as $pc): ?>
			<li>
				<?=$this->html->link($pc,'/publications?classification='.$pc); ?>
			</li>
		<?php endforeach; ?>
			<li class="divider"></li>
		<?php foreach($pub_types as $pt): ?>
			<li>
				<?=$this->html->link($pt,'/publications?type='.$pt); ?>
			</li>
		<?php endforeach; ?>
			</ul>
		</li>

		<li class="active">
			<?=$this->html->link('Languages','/publications/languages'); ?>
		</li>
		<li>
			<?=$this->html->link('Subjects','/publications/subjects'); ?>
		</li>
		<li>
			<?=$this->html->link('History','/publications/histories'); ?>
		</li>

		<li>
			<?=$this->html->link('Search','/publications/search'); ?>
		</li>

	</ul>

	<div class="btn-toolbar">
		<?php if($authority_can_edit): ?>

			<a class="btn btn-inverse" href="/publications/add/"><i class="icon-plus-sign icon-white"></i> Add Publication</a>

		<?php endif; ?>
	</div>
</div>

	<?php if ($languages): ?>

	<?php if (sizeof($languages) > 20): ?>
		<div style="-moz-column-count:3; -webkit-column-count:3; column-count:3; -moz-column-gap:40px; -webkit-column-gap:40px; column-gap: 40px;">
	<?php else: ?>
		<div>
	<?php endif; ?>

	<?php foreach ($languages as $language): ?>

		<?php $query = urlencode($language['name']); ?>
		<p><?=$this->html->link($language['name'], "/publications/search?condition=language&query=$query"); ?>&nbsp;<span class="badge"><?=$language['count'] ?></span></p>

	<?php endforeach; ?>
		</div>
		<hr/>
	<?php endif; ?>
