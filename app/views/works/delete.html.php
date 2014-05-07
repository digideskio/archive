<?php

$this->title('Artwork');

$authority_can_edit = $this->authority->canEdit();

?>

<?=$this->partial->breadcrumbs(array(
	'crumbs' => array(
		array('title' => 'Artwork', 'url' => $this->url(array('Works::index'))),
		array('title' => 'Delete', 'active' => true),
	)
)); ?>

<div class="actions">

<?=$this->partial->navtabs(array(
	'tabs' => array(
		array('title' => 'Index', 'url' => $this->url(array('Works::index'))),
		array('title' => 'Classifications', 'url' => $this->url(array('Works::classifications'))),
		array('title' => 'Locations', 'url' => $this->url(array('Works::locations'))),
		array('title' => 'History', 'url' => $this->url(array('Works::histories'))),
		array('title' => 'Search', 'url' => $this->url(array('Works::search'))),
	)
)); ?>
	<div class="btn-toolbar">
			<a class="btn btn-inverse" href="<?=$this->url(array('Works::index')); ?>?action=print&limit=all"><i class="icon-print icon-white"></i> Print Artwork</a>
		<?php if($authority_can_edit): ?>

			<a class="btn btn-inverse" href="<?=$this->url(array('Works::add')); ?>"><i class="icon-plus-sign icon-white"></i> Add Artwork</a>

		<?php endif; ?>

	</div>

</div>

<?php if ($action === 'delete'): ?>

<div class="alert alert-error">
    The work has been deleted.
</div>

<?php endif; ?>
