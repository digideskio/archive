<?php

$this->title('Search Exhibitions');

$conditions_list = array(
	'' => 'Search by...',
	'Archives.name' => 'Title',
	'venue' => 'Venue',
	'city' => 'City',
	'country' => 'Country',
	'curator' => 'Curator',
	'earliest_date' => 'Year',
	'remarks' => 'Remarks',
);

$authority_can_edit = $this->authority->canEdit();

?>

<div id="location" class="row-fluid">

	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Exhibitions','/exhibitions'); ?>
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
			<?=$this->html->link('Index','/exhibitions'); ?>
		</li>
		<li>
			<?=$this->html->link('Venues','/exhibitions/venues'); ?>
		</li>
		<li>
			<?=$this->html->link('History','/exhibitions/histories'); ?>
		</li>
		<li class="active">
			<?=$this->html->link('Search','/exhibitions/search'); ?>
		</li>

	</ul>
	<div class="btn-toolbar">
		<?php if($authority_can_edit): ?>

				<a class="btn btn-inverse" href="/exhibitions/add"><i class="icon-plus-sign icon-white"></i> Add an Exhibition</a>

		<?php endif; ?>
	</div>
</div>

<div class="well">

	<?=$this->form->create(null, array('class' => 'form-inline', 'action' => 'search', 'method' => 'get')); ?>
		<legend>Search Exhibitions</legend>

		<input type="text" name="query" value="<?=$query?>" placeholder="Search…" autocomplete="off">

			<?=$this->form->select('condition', $conditions_list, array('label' => '', 'value' => $condition)); ?>

		<?=$this->form->submit('Submit', array('class' => 'btn btn-inverse')); ?>

		<p></p>

		<fieldset>
		<label class="radio">
		<?php $checked = $type == 'All'; ?>
		<?=$this->form->radio('type', array('value' => 'All', 'checked' => $checked)); ?>
		All shows
		</label>
		<label class="radio">
		<?php $checked = $type == 'Solo'; ?>
		<?=$this->form->radio('type', array('value' => 'Solo', 'checked' => $checked)); ?>
		Solo shows
		</label>
		<label class="radio">
		<?php $checked = $type == 'Group'; ?>
		<?=$this->form->radio('type', array('value' => 'Group', 'checked' => $checked)); ?>
		Group shows
		</label>
		</fieldset>

	<?=$this->form->end(); ?>

</div>

<?php if($total > 0): ?>

<div id="search-results">

<?=$this->partial->exhibitions(compact('exhibitions')); ?>

</div>

<?=$this->pagination->pager('exhibitions', 'search', $page, $total, $limit, array('condition' => $condition, 'query' => $query, 'type' => $type, 'limit' => $limit)); ?>

	<script>

		$(document).ready(function() {

			$("#search-results .table, #search-results article").highlight("<?=$query?>");

		 });

	</script>

<?php endif; ?>
