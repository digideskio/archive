<?php

$this->title('Artwork');

$authority_can_edit = $this->authority->canEdit();

?>

<?=$this->partial->breadcrumbs(array(
	'crumbs' => array(
		array('title' => 'Artwork', 'url' => $this->url(array('Works::index')), 'active' => true),
	)
)); ?>

<div class="actions">

<?php if ($action === 'print'): ?>
    <script>
        window.print();
    </script>
<?php endif; ?>

<?=$this->partial->navtabs(array(
	'tabs' => array(
		array('title' => 'Index', 'url' => $this->url(array('Works::index')), 'active' => true),
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

<?php if($total == 0): ?>

	<div class="alert alert-danger">There is no Artwork in the Archive.</div>

	<?php if($authority_can_edit): ?>

		<div class="alert alert-success">You can add the first Artwork by clicking the <strong><?=$this->html->link('Add Artwork','/works/add/'); ?></strong> button.</div>

	<?php endif; ?>

<?php endif; ?>

<?php if($total > 0): ?>

<?php
    if ($action === 'print') {
        $layout = 'table';
    } else {
        $layout = null;
    }
?>

<?=$this->partial->works(compact('works', 'layout')); ?>

<?=$this->pagination->pager('works', 'pages', $page, $total, $limit, array('limit' => $limit)); ?>

<?php endif; ?>
