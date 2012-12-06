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
	?>

	<?php if ($has_works): ?>

		<?=$this->partial->works(array('works' => $works, 'showBar' => true)); ?>

	<? endif; ?>

<div class="navbar">
	<div class="navbar-inner">
	<ul class="nav">
		<li class="meta"><a href="#">Documents</a></li>
	</ul>
	</div>
</div>

<ul class="thumbnails">
<?php foreach ($documents as $document): ?>

	<?php
		$span = 'span2';
	?>
	
	<li class="<?=$span?>">
		<a href="/documents/view/<?=$document->slug?>"  class="thumbnail" title="<?=$document->slug?>">
			<img src="/files/thumb/<?=$document->slug?>.jpeg" alt="<?=$document->title ?>">
		</a>
	</li>

<?php endforeach; ?>
</ul>
