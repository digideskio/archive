<?php 

$this->title($exhibition->title);

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Exhibitions','/exhibitions'); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
	<?=$this->html->link($exhibition->title,'/exhibitions/view/'.$exhibition->slug); ?>
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li class="active">
		<a href="#">View</a>
	</li>

	<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>
	
		<li><?=$this->html->link('Edit','/exhibitions/edit/'.$exhibition->slug); ?></li>
	
	<?php endif; ?>

</ul>

	<div class="alert alert-info">
	<h1><?=$exhibition->title ?></h1>
	
	<?php 
		date_default_timezone_set('UTC');
		
		$location = $exhibition->location();
		$dates = $exhibition->dates();
		$curator = $exhibition->curator;
	?>
	
	<?php if($location) echo "<p>$location</p>"; ?>
	<?php if($dates) echo "<p>$dates</p>"; ?>
	<?php if($curator) echo "<p>$curator, Curator</p>"; ?>
	
	<p><?=$exhibition->remarks ?></p>

	<?php if(sizeof($exhibitions_links) > 0): ?>

		<?php foreach($exhibitions_links as $el): ?>
			<p><?=$this->html->link($el->link->url, $el->link->url); ?></p>
		<?php endforeach; ?>
	<?php endif; ?>
	
	<p><span class="badge"><?=$exhibition->type ?> Show</span></p>
	</div>

<?php if(sizeof($exhibition_documents) > 0): ?>

	<div class="navbar">
		<div class="navbar-inner">
			<ul class="nav">
				<li class="meta"><a href="#">Documents</a></li>
			</ul>
		</div>
	</div>

	<ul class="thumbnails">
	
		<?php
			$span = 'span3';
			$size = 'thumb';
		?>

		<?php foreach($exhibition_documents as $ed): ?>

			<li class="<?$span?>">
				<a href="/documents/view/<?=$ed->document->slug?>" class="thumbnail">
					
					<?php if ($ed->document->published): ?>
						<span class="label label-success">Published</span>
					<?php endif; ?>
					<?php if (!$ed->document->published): ?>
						<span class="label label-important">Private</span>
					<?php endif; ?>

					<img src="/files/<?=$ed->document->view(array('action' => $size)); ?>" alt="<?=$ed->document->title ?>">
				</a>
			</li>

		<?php endforeach; ?>

	</ul>

<?php endif; ?>

<?php if($total > 0): ?>

<?=$this->partial->works(array('works' => $works, 'showBar' => true)); ?>

<?php endif; ?>
