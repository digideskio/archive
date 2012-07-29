<?php 

$this->title('Documents');

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Documents','/documents'); ?>
	</li>

	</ul>

</div>

<div id="tools" class="btn-toolbar">

<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

	<div class="action btn-group">

		<a class="btn btn-inverse" href="/documents/add/">
			<i class="icon-plus-sign icon-white"></i> Add a Document
		</a>

	</div>

<?php endif; ?>

</div>

<?php if(sizeof($documents) == 0): ?>

	<div class="alert alert-danger">There are no Documents in the Archive.</div>

	<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

		<div class="alert alert-success">You can add the first Document by clicking the <strong><?=$this->html->link('Add a Document','/documents/add/'); ?></strong> button.</div>

	<?php endif; ?>

<?php endif; ?>

<ul class="thumbnails">

<?php foreach($documents as $document): ?>

	<?php
		$span = 'span2';
	?>
	
	<li class="<?=$span?>">
		<a href="/documents/view/<?=$document->slug?>" class="thumbnail" title="<?=$document->title?>">
			<img src="/files/thumb/<?=$document->slug?>.jpeg" alt="<?=$document->title ?>">
		</a>
	</li>

<?php endforeach; ?>

</ul>

