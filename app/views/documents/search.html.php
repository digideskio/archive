<?php

$this->title('Search Documents');

$conditions_list = array(
	'' => 'Search by...',
	'title' => 'Title',
	'date_created' => 'Year',
	'repository' => 'Repository',
	'credit' => 'Credit',
	'remarks' => 'Remarks',
);

?>

<div id="location" class="row-fluid">

	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Documents','/documents'); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
		Search
	</li>

	</ul>

</div>

<div class="actions">
	<ul class="nav nav-tabs">
		<li>
			<?=$this->html->link('Index','/documents'); ?>
		</li>
		<li class="active">
			<?=$this->html->link('Search ','/documents/search'); ?>
		</li>

	</ul>

	<div class="btn-toolbar">
		<?php if($this->authority->canEdit()): ?>

				<a class="btn btn-inverse" href="/documents/add"><i class="icon-plus-sign icon-white"></i> Add a Document</a>

		<?php endif; ?>

	</div>
</div>

<div class="well">

	<?=$this->form->create(null, array('class' => 'form-inline', 'action' => 'search', 'method' => 'get')); ?>
		<legend>Search Documents</legend>

		<input type="text" name="query" value="<?=$query?>" placeholder="Search…" autocomplete="off">

			<?=$this->form->select('condition', $conditions_list, array('label' => '', 'value' => $condition)); ?>

		<?=$this->form->submit('Submit', array('class' => 'btn btn-inverse')); ?>

	<?=$this->form->end(); ?>

</div>

<?php if($total > 0): ?>

<?=$this->partial->documents(compact('documents')); ?>

<?=$this->pagination->pager('documents', 'search', $page, $total, $limit, array('condition' => $condition, 'query' => $query, 'limit' => $limit)); ?>

<?php endif; ?>
