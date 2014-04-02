<?php

$this->title('Links');

$authority_can_edit = $this->authority->canEdit();

?>

<div id="location" class="row-fluid">

	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Links','/links'); ?>
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
			<?=$this->html->link('Index','/links'); ?>
		</li>

		<li class="active">
			<?=$this->html->link('Search','/links/search'); ?>
		</li>

	</ul>
	<div class="btn-toolbar">
		<?php if($authority_can_edit): ?>

				<a class="btn btn-inverse" href="/links/add"><i class="icon-plus-sign icon-white"></i> Add a Link</a>

		<?php endif; ?>

	</div>
<div>

<div class="well">

	<?=$this->form->create(null, array('class' => 'form-inline', 'action' => 'search')); ?>
		<legend>Search Links</legend>

		<input type="text" name="query" value="<?=$query?>" placeholder="Search…" autocomplete="off">

		<?=$this->form->submit('Submit', array('class' => 'btn btn-inverse')); ?>

	<?=$this->form->end(); ?>

</div>

<?php if($total > 0): ?>

<div id="search-results">

<?=$this->partial->links(compact('links')); ?>

</div>

<?=$this->pagination->pager('links', 'search', $page, $total, $limit, array('condition' => $condition, 'query' => $query, 'limit' => $limit)); ?>

	<script>

		$(document).ready(function() {

			$("#search-results .table, #search-results article").highlight("<?=$query?>");

		 });

	</script>
<?php endif; ?>
