<?php 

$this->title($exhibition->archive->name);

$authority_can_edit = $this->authority->canEdit();

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Exhibitions','/exhibitions'); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
	<?=$this->html->link($exhibition->archive->name,'/exhibitions/view/'.$exhibition->archive->slug); ?>
	</li>

	</ul>

</div>

<div class="actions">
<ul class="nav nav-tabs">
	<li class="active">
		<a href="#">View</a>
	</li>

	<?php if($authority_can_edit): ?>
	
		<li><?=$this->html->link('Edit','/exhibitions/edit/'.$exhibition->archive->slug); ?></li>
		<li><?=$this->html->link('Attachments','/exhibitions/attachments/'.$exhibition->archive->slug); ?></li>
	
	<?php endif; ?>

		<li><?=$this->html->link('History','/exhibitions/history/'.$exhibition->archive->slug); ?></li>

</ul>
<div class="btn-toolbar">
	<div class="btn-group">
		<?php
			$print_query = array(
				'exhibition' => $exhibition->archive->id,
				'template' => 'list'
			);
			$print_url = $this->url(array('Works::publish')) . '?' . http_build_query($print_query);
		?>
		<a class="btn btn-inverse" href="<?=$print_url ?>"><i class="icon-print icon-white"></i> Print</a>
	</div>
</div>
</div>

<?=$this->partial->exhibition(compact('exhibition')); ?>

<?php if($exhibition->annotation): ?>
	<p class="lead">Description</p>
	<p class="muted">
		<?php echo nl2br($this->escape($exhibition->annotation)); ?>
	</p>
<?php endif; ?>

<?php if ($archives_links->count()): ?>

	<div class="alert alert-info alert-block">
	<?php foreach($archives_links as $al): ?>
		<?=$this->link->caption($al->link); ?>
	<?php endforeach; ?>
	</div>

<?php endif; ?>

<?php if(sizeof($archives_documents) > 0): ?>

	<?=$this->partial->archives_documents(array('archives_documents' => $archives_documents, 'showBar' => true)); ?>

<?php endif; ?>

<?php if($total > 0): ?>

<?=$this->partial->works(array('works' => $works, 'showBar' => true)); ?>

<?php endif; ?>

<?php if(sizeof($publications) > 0): ?>

	<?=$this->partial->publications(array('publications' => $publications, 'showBar' => true)); ?>

<?php endif; ?>
