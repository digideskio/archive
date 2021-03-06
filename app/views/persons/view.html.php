<?php

$this->title($person->archive->name);

?>

<?=$this->partial->breadcrumbs(array(
	'crumbs' => array(
		array(
			'title' => 'Artists',
			'url' => $this->url(array('Persons::index'))
		),
		array(
			'title' => $person->archive->name,
			'url' => $this->url(array(
				'controller' => 'persons',
				'action' => 'view',
				'slug' => $person->archive->slug
			)),
			'active' => true
		)
	)
)); ?>

<div class="actions">
<?=$this->partial->navtabs(array(
	'tabs' => array(
		array(
			'title' => 'View',
			'url' => $this->url(array(
				'controller' => 'persons',
				'action' => 'view',
				'slug' => $person->archive->slug
			)),
			'active' => true
		),
		array(
			'title' => 'Edit',
			'url' => $this->url(array(
				'controller' => 'persons',
				'action' => 'edit',
				'slug' => $person->archive->slug
			))
		)
	)
)); ?>
</div>

<p class="lead"><strong><?=$person->archive->names() ?></strong></p>
<p class="lead"><?=$person->archive->classification ?></p>

<?php if ($works->count() > 0): ?>
	<?=$this->partial->works(compact('works')); ?>

	<?=$this->pagination->pager('artists', 'view/' . $person->archive->slug, $page, $total, $limit, array('limit' => $limit)); ?>
<?php endif; ?>
