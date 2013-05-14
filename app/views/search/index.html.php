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

<div class="alert alert-info">
	<span class="meta">Search for:</span> <?=$this->html->link('Artworks',"/works/search?query=$query"); ?> / <?php if ($architecture): ?> <?=$this->html->link('Architecture',"/architectures/search?query=$query"); ?> / <?php endif; ?> <?=$this->html->link('Exhibitions', "/exhibitions/search?query=$query"); ?> / <?=$this->html->link('Publications', "/publications/search?query=$query"); ?> / <?=$this->html->link('Documents', "/documents/search?query=$query"); ?>
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
		<legend>Search the Whole Archive</legend>

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

		<?=$this->partial->works(array('works' => $works, 'showBar' => true)); ?>

		<p><a class="btn btn-large btn-block btn-success" href="/works/search?query=<?=$query?>">More Artworks &raquo;</a></p>

		<hr/>

	<?php endif; ?>

	<?php if ($has_architectures): ?>

		<?=$this->partial->architectures(array('architectures' => $architectures, 'showBar' => true)); ?>

		<p><a class="btn btn-large btn-block btn-warning" href="/architectures/search?query=<?=$query?>">More Architecture &raquo;</a></p>

		<hr/>

	<?php endif; ?>

	<?php if ($has_exhibitions): ?>

		<?=$this->partial->exhibitions(array('exhibitions' => $exhibitions, 'showBar' => true)); ?>

		<p><a class="btn btn-large btn-block btn-info" href="/exhibitions/search?query=<?=$query?>">More Exhibitions &raquo;</a></p>

		<hr/>

	<?php endif; ?>

	<?php if ($has_publications): ?>

		<?=$this->partial->publications(array('publications' => $publications, 'showBar' => true)); ?>

		<p><a class="btn btn-large btn-block btn-primary" href="/publications/search?query=<?=$query?>">More Publications &raquo;</a></p>

		<hr/>

	<?php endif; ?>

	<?php if ($has_documents): ?>

		<?=$this->partial->documents(array('documents' => $documents, 'showBar' => true)); ?>

		<p><a class="btn btn-large btn-block btn-inverse" href="/documents/search?query=<?=$query?>">More Documents &raquo;</a></p>

		<hr/>

	<?php endif; ?>

</div>

	<script>

		$(document).ready(function() {
			$("#search-results .table, #search-results article").highlight("<?=$query?>");
		 });

	</script>
