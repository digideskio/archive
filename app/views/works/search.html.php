<?php

$this->title('Search Artwork');

$conditions_list = array(
	'' => 'Search by...',
	'title' => 'Title',
	'artist' => 'Artist',
	'classification' => 'Classification',
	'earliest_date' => 'Year',
	'materials' => 'Materials',
	'remarks' => 'Remarks',
	'creation_number' => 'Artwork ID',
	'annotation' => 'Annotation',
);

$authority_can_edit = $this->authority->canEdit();
$authority_is_admin = $this->authority->isAdmin();

$inventory = (\lithium\core\Environment::get('inventory') && ($authority_is_admin));

if ($inventory) {
	$conditions_list['location'] = 'Location';	
}

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Artwork','/works'); ?>
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
			<a href="/works">Index</a>
		</li>

		<li>
			<?=$this->html->link('Artists','/works/artists'); ?>
		</li>

		<li>
			<?=$this->html->link('Classifications','/works/classifications'); ?>
		</li>

		<?php if($inventory): ?>

			<li>
				<?=$this->html->link('Locations','/works/locations'); ?>
			</li>
		
		<?php endif; ?>

		<li>
			<?=$this->html->link('History','/works/histories'); ?>
		</li>

		<li class="active">
			<?=$this->html->link('Search','/works/search'); ?>
		</li>

	</ul>
	
	<div class="btn-toolbar">
		<?php if($authority_can_edit): ?>

			<a class="btn btn-inverse" href="/works/add/"><i class="icon-plus-sign icon-white"></i> Add Artwork</a>
		
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

	<script>

		$(document).ready(function() {

			$("#search-results .table <?=$condition_class?>, #search-results article <?=$condition_class?>").highlight("<?=$query?>");

		 });

	</script>
<?php endif; ?>
