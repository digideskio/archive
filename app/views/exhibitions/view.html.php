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
	<?=$this->html->link($exhibition->title,'/exhibitions/view/'.$exhibition->archive->slug); ?>
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li class="active">
		<a href="#">View</a>
	</li>

	<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>
	
		<li><?=$this->html->link('Edit','/exhibitions/edit/'.$exhibition->archive->slug); ?></li>
		<li><?=$this->html->link('Attachments','/exhibitions/attachments/'.$exhibition->archive->slug); ?></li>
	
	<?php endif; ?>

		<li><?=$this->html->link('History','/exhibitions/history/'.$exhibition->archive->slug); ?></li>

</ul>

<?=$this->partial->exhibition(compact('exhibition')); ?>

<?php if(sizeof($archives_documents) > 0): ?>

	<?=$this->partial->archives_documents(array('archives_documents' => $archives_documents, 'showBar' => true)); ?>

<?php endif; ?>

<?php if($total > 0): ?>

<?=$this->partial->works(array('works' => $works, 'showBar' => true)); ?>

<?php endif; ?>
