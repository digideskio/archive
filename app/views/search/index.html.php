<?php 

$this->title('Search');

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Search','/search'); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		<?=$this->html->link($query,'/search?query='.$query); ?>
	</li>

	</ul>

</div>

	<?php 
		$has_works = sizeof($works) > 0 ? true : false; 
		$has_documents = sizeof($documents) > 0 ? true : false; 
	?>

	<?php if (!$has_works && !$has_documents): ?>

		<div class="alert alert-error">
			<p>We could find nothing in the archive called <strong><?=$query ?></strong>.</p>
		</div>

	<?php endif; ?>

	<?php if ($has_works): ?>

		<?=$this->partial->works(array('works' => $works, 'showBar' => true)); ?>

	<? endif; ?>

	<?php if ($has_documents): ?>

		<?=$this->partial->documents(array('documents' => $documents, 'showBar' => true)); ?>

	<? endif; ?>
