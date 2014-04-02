<?php

$this->title('Search Artwork');

$conditions_list = array(
	'' => 'Search by...',
	'Archives.name' => 'Title',
	'artist' => 'Artist',
	'Archives.classification' => 'Classification',
	'Archives.earliest_date' => 'Year',
	'Works.materials' => 'Materials',
	'Works.remarks' => 'Remarks',
	'Works.creation_number' => 'Artwork ID',
	'Works.annotation' => 'Annotation',
);

$authority_can_edit = $this->authority->canEdit();
$authority_is_admin = $this->authority->isAdmin();

$inventory = (\lithium\core\Environment::get('inventory') && ($authority_is_admin));

if ($inventory) {
	$conditions_list['Works.location'] = 'Location';
}

?>

<?=$this->partial->breadcrumbs(array(
	'crumbs' => array(
		array('title' => 'Artwork', 'url' => $this->url(array('Works::search'))),
		array('title' => 'Search', 'active' => true)
	)
)); ?>

<div class="actions">

<?=$this->partial->navtabs(array(
	'tabs' => array(
		array('title' => 'Index', 'url' => $this->url(array('Works::index'))),
		array('title' => 'Classifications', 'url' => $this->url(array('Works::classifications'))),
		array('title' => 'Locations', 'url' => $this->url(array('Works::locations'))),
		array('title' => 'History', 'url' => $this->url(array('Works::histories'))),
		array('title' => 'Search', 'url' => $this->url(array('Works::search')), 'active' => true),
	)
)); ?>
	<div class="btn-toolbar">
		<?php if($authority_can_edit): ?>

			<a class="btn btn-inverse" href="<?=$this->url(array('Works::add')); ?>"><i class="icon-plus-sign icon-white"></i> Add Artwork</a>

		<?php endif; ?>

	</div>

</div>

<div class="well">

	<?=$this->form->create(null, array('class' => 'form-inline', 'action' => 'search', 'method' => 'get')); ?>
		<legend>Search Artwork</legend>

		<input type="text" name="query" value="<?=$query?>" placeholder="Search…" autocomplete="off">

			<?=$this->form->select('condition', $conditions_list, array('label' => '', 'value' => $condition)); ?>

		<?=$this->form->submit('Submit', array('class' => 'btn btn-inverse')); ?>

	<?=$this->form->end(); ?>

</div>

<?php if($total > 0): ?>

<div id="search-results">

<?=$this->partial->works(compact('works')); ?>

</div>

<?=$this->pagination->pager('works', 'search', $page, $total, $limit, array('condition' => $condition, 'query' => $query, 'limit' => $limit)); ?>


	<?php $condition_class = $condition ? ".info-$condition" : ''; //if we are searching a particular field, only highlight the term in the correct table column ?>

	<?php
		if ($condition == 'Archives.name') {
			$condition_class = '.info-title';
		}
	?>

	<script>

		$(document).ready(function() {

			$("#search-results .table <?=$condition_class?>, #search-results article <?=$condition_class?>").highlight("<?=$query?>");

		 });

	</script>
<?php endif; ?>
