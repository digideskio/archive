<?php 

$this->title('Search');

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Search','/search'); ?>
	</li>

	</ul>

</div>

<div class="actions">
	<ul class="nav nav-tabs">
		<li class="active">
			<?=$this->html->link('All Results','/search'); ?>
		</li>
	</ul>

</div>

<div class="well">

	<?=$this->form->create(null, array('class' => 'form-inline')); ?>
		<legend>Search the Archive</legend>

		<?=$this->form->field('query', array('value' => $query, 'autocomplete' => 'off', 'placeholder' => 'Search…', 'template' => '{:input}')); ?>

		<?=$this->form->submit('Submit', array('class' => 'btn btn-inverse')); ?>

	<?=$this->form->end(); ?>

</div>

<div id="search-results">

	<?php 
		$has_works = sizeof($works) > 0 ? true : false; 
		$has_documents = sizeof($documents) > 0 ? true : false; 
	?>

	<?php if ($query && !$has_works && !$has_documents): ?>

		<div class="alert alert-error">
			<p>No results found.</p>
		</div>

	<?php endif; ?>

	<?php if ($has_works): ?>

		<?=$this->partial->works(array('works' => $works, 'showBar' => true)); ?>

	<? endif; ?>

	<?php if ($has_documents): ?>

		<?=$this->partial->documents(array('documents' => $documents, 'showBar' => true)); ?>

	<? endif; ?>

</div>

	<script>

		$(document).ready(function() {
			$("#search-results .table").highlight("<?=$query?>");
		 });

	</script>
