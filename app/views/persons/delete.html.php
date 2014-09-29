<?php

$this->title('Artists');

$authority_can_edit = $this->authority->canEdit();
$authority_is_admin = $this->authority->isAdmin();

?>

<?=$this->partial->breadcrumbs(array(
	'crumbs' => array(
		array('title' => 'Artists', 'url' => $this->url(array('Persons::add')), 'active' => true),
	)
)); ?>

<div class="actions">

<?=$this->partial->navtabs(array(
	'tabs' => array(
		array('title' => 'Index', 'url' => $this->url(array('Persons::index')), 'active' => true),
	)
)); ?>
	<div class="btn-toolbar">
		<?php if($authority_can_edit): ?>

			<a class="btn btn-inverse" href="<?=$this->url(array('Persons::add')); ?>"><i class="icon-plus-sign icon-white"></i> Add Artist</a>

		<?php endif; ?>

	</div>

</div>

<?php if ($action === 'delete'): ?>

<div class="alert alert-error">
    The artist has been deleted.
</div>

<?php endif; ?>
