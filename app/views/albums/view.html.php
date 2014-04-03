<?php

$this->title($album->archive->name);

?>

<div id="location" class="row-fluid">

	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Albums', $this->url(array('Albums::index'))); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
	<?=$this->html->link($album->archive->name, $this->url(array('Albums::view', 'slug' => $album->archive->slug))); ?>
	</li>

	</ul>

</div>

<div class="actions">

	<ul class="nav nav-tabs">
		<li class="active">
			<a href="#">View</a>
		</li>

		<?php if($this->authority->canEdit()): ?>

			<li><?=$this->html->link('Edit', $this->url(array('Albums::edit', 'slug' => $album->archive->slug))); ?></li>

		<?php endif; ?>

		<li><?=$this->html->link('History', $this->url(array('Albums::history', 'slug' => $album->archive->slug))); ?></li>

		<li><?=$this->html->link('Packages', $this->url(array('Albums::package', 'slug' => $album->archive->slug))); ?></li>

	</ul>

	<div class="btn-toolbar">
		<div class="btn-group">
			<button class="btn btn-inverse dropdown-toggle" data-toggle="dropdown"><i class="icon-print icon-white"></i> Print <span class="caret"</span></button>
			<ul class="dropdown-menu pull-right">
				<li><a href="<?=$this->url(array('Albums::publish', 'slug' => $album->archive->slug)); ?>?layout=download&view=images"><i class="icon-qrcode"></i> Print Image Info</a></li>
				<li><a href="<?=$this->url(array('Albums::publish', 'slug' => $album->archive->slug)); ?>?layout=download&view=notes"><i class="icon-list"></i> Print Artwork Notes</a></li>
			</ul>
		</div>
	</div>

</div>

<?php if ($album->remarks): ?>
	<div class="alert alert-info">
	<p><?=$album->remarks ?></p>
	</div>
<?php endif; ?>

<?php if ($works->count() > 0): ?>

	<?=$this->partial->works(array('works' => $works, 'showBar' => true)); ?>

<?php endif; ?>

<?php if($publications->count() > 0): ?>

	<?=$this->partial->publications(array('publications' => $publications, 'showBar' => true)); ?>

<?php endif; ?>

<?php if ($archives_documents->count() > 0): ?>

	<?=$this->partial->archives_documents(array('archives_documents' => $archives_documents, 'showBar' => true)); ?>

<?php endif; ?>
