<?php

$this->title('Exhibition Venues');

$authority_can_edit = $this->authority->canEdit();

?>

<div id="location" class="row-fluid">

	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Exhibitions','/exhibitions'); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
		Venues
	</li>

	</ul>

</div>

<div class="actions">
	<ul class="nav nav-tabs">
		<li>
			<?=$this->html->link('Index','/exhibitions'); ?>
		</li>
		<li class="active">
			<?=$this->html->link('Venues','/exhibitions/venues'); ?>
		</li>
		<li>
			<?=$this->html->link('History','/exhibitions/histories'); ?>
		</li>
		<li>
			<?=$this->html->link('Search','/exhibitions/search'); ?>
		</li>

	</ul>
	<div class="btn-toolbar">
		<?php if($authority_can_edit): ?>

				<a class="btn btn-inverse" href="/exhibitions/add"><i class="icon-plus-sign icon-white"></i> Add an Exhibition</a>

		<?php endif; ?>
	</div>
</div>

<div class="row">
	<?php if ($venues): ?>
	<div class="span4">
	<h3>Venues</h3>

		<div>

	<?php foreach ($venues as $venue): ?>

		<?php $query = urlencode($venue['name']); ?>
		<p><?=$this->html->link($venue['name'], "/exhibitions/search?condition=venue&query=$query"); ?>&nbsp;<span class="badge"><?=$venue['count'] ?></span></p>

	<?php endforeach; ?>

		</div>
	</div>
	<?php endif; ?>

	<?php if ($cities): ?>
	<div class="span3">

		<h3>Cities</h5>

		<div>

	<?php foreach ($cities as $city): ?>

		<?php $query = urlencode($city['name']); ?>
		<p><?=$this->html->link($city['name'], "/exhibitions/search?condition=city&query=$query"); ?>&nbsp;<span class="badge"><?=$city['count'] ?></span></p>

	<?php endforeach; ?>

		</div>
	</div>
	<?php endif; ?>

	<?php if ($countries): ?>
	<div class="span3">

		<h3>Countries</h5>

		<div>

	<?php foreach ($countries as $country): ?>

		<?php $query = urlencode($country['name']); ?>
		<p><?=$this->html->link($country['name'], "/exhibitions/search?condition=country&query=$query"); ?>&nbsp;<span class="badge"><?=$country['count'] ?></span></p>

	<?php endforeach; ?>

		</div>
	</div>
	<?php endif; ?>
</div>
