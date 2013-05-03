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
		$has_architectures = sizeof($architectures) > 0 ? true : false; 
		$has_exhibitions = sizeof($exhibitions) > 0 ? true : false; 
		$has_publications = sizeof($publications) > 0 ? true : false; 
		$has_documents = sizeof($documents) > 0 ? true : false; 
	?>

	<?php if ($query && !$has_works && !$has_architectures && !$has_exhibitions && !$has_publications && !$has_documents): ?>

		<div class="alert alert-error">
			<p>No results found.</p>
		</div>

	<?php endif; ?>

	<?php if ($has_works): ?>

		<?php if (sizeof($works) > 50): ?>

		<div class="alert alert-error">
			<p>Your search has returned too many Artworks. Please narrow your search criteria using the <a href="/works/search?query=<?=$query?>">Artworks search page</a>.</p>
		</div>

		<?php else: ?>

		<?=$this->partial->works(array('works' => $works, 'showBar' => true)); ?>

		<?php endif; ?>

	<?php endif; ?>

	<?php if ($has_architectures): ?>

		<?php if (sizeof($architectures) > 50): ?>

		<div class="alert alert-error">
			<p>Your search has returned too many Architectures project. Please narrow your search criteria using the <a href="/architectures/search?query=<?=$query?>">Architecture search page</a>.</p>
		</div>

		<?php else: ?>

		<?=$this->partial->architectures(array('architectures' => $architectures, 'showBar' => true)); ?>

		<?php endif; ?>

	<?php endif; ?>

	<?php if ($has_exhibitions): ?>

		<?php if (sizeof($exhibitions) > 50): ?>

		<div class="alert alert-error">
			<p>Your search has returned too many Exhibitions. Please narrow your search criteria using the <a href="/exhibitions/search?query=<?=$query?>&type=All">Exhibitions search page</a>.</p>
		</div>

		<?php else: ?>

		<?=$this->partial->exhibitions(array('exhibitions' => $exhibitions, 'showBar' => true)); ?>

		<?php endif; ?>

	<?php endif; ?>

	<?php if ($has_publications): ?>

		<?php if (sizeof($publications) > 50): ?>

		<div class="alert alert-error">
			<p>Your search has returned too many Publications. Please narrow your search criteria using the <a href="/publications/search?query=<?=$query?>">Publications search page</a>.</p>
		</div>

		<?php else: ?>

		<?=$this->partial->publications(array('publications' => $publications, 'showBar' => true)); ?>

		<?php endif; ?>

	<?php endif; ?>

	<?php if ($has_documents): ?>

		<?php if (sizeof($works) > 50): ?>

		<div class="alert alert-error">
			<p>Your search has returned too many Documents. Please narrow your search criteria using the <a href="/documents/search?query=<?=$query?>">Documents search page</a>.</p>
		</div>

		<?php else: ?>

		<?=$this->partial->documents(array('documents' => $documents, 'showBar' => true)); ?>

		<?php endif; ?>

	<?php endif; ?>

</div>

	<script>

		$(document).ready(function() {
			$("#search-results .table, #search-results article").highlight("<?=$query?>");
		 });

	</script>
