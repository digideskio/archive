<?php

$this->title('Architecture');

$authority_can_edit = $this->authority->canEdit();

?>

<div id="location" class="row-fluid">

	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Architecture','/architectures'); ?>
	</li>

	</ul>

</div>

<div class="actions">
	<ul class="nav nav-tabs">
		<li class="active">
			<?=$this->html->link('Index','/architectures'); ?>
		</li>
		<li>
			<?=$this->html->link('History','/architectures/histories'); ?>
		</li>
		<li>
			<?=$this->html->link('Search','/architectures/search'); ?>
		</li>

	</ul>

	<div class="btn-toolbar">
		<?php if($authority_can_edit): ?>

			<a class="btn btn-inverse" href="/architectures/add"><i class="icon-plus-sign icon-white"></i> Add a Project</a>

		<?php endif; ?>

	</div>
<div>

<?php if($total == 0): ?>

	<div class="alert alert-danger">There is no Architecture in the Archive.</div>

	<?php if($authority_can_edit): ?>

		<div class="alert alert-success">You can add the first Project by clicking the <strong><?=$this->html->link('Add a Project','/architectures/add/'); ?></strong> button.</div>

	<?php endif; ?>

<?php endif; ?>

<?php if($total > 0): ?>

	<?=$this->partial->architectures(compact('architectures')); ?>

	<?=$this->pagination->pager('architectures', 'pages', $page, $total, $limit, array('limit' => $limit)); ?>

<?php endif; ?>
