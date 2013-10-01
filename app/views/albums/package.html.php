<?php 

$this->title($album->archive->name);

if($this->authority->timezone()) {
	$tz = new DateTimeZone($this->authority->timezone());
}

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
		<?=$this->html->link('Albums', $this->url(array('Albums::index'))); ?>
		<span class="divider">/</span>
	</li>

	<li>
		<?=$this->html->link($album->archive->name, $this->url(array('Albums::view', 'slug' => $album->archive->slug))); ?>
		<span class="divider">/</span>
	</li>
	
	<li class="active">
		Package
	</li>

	</ul>

</div>

<div class="actions">

	<ul class="nav nav-tabs">
		<li><?=$this->html->link('View', $this->url(array('Albums::view', 'slug' => $album->archive->slug))); ?></li>

		<?php if($this->authority->canEdit()): ?>
		
			<li><?=$this->html->link('Edit', $this->url(array('Albums::edit', 'slug' => $album->archive->slug))); ?></li>
		
		<?php endif; ?>

		<li><?=$this->html->link('History', $this->url(array('Albums::history', 'slug' => $album->archive->slug))); ?></li>

		<li class="active"><?=$this->html->link('Packages', $this->url(array('Albums::package', 'slug' => $album->archive->slug))); ?></li>

	</ul>

</div>

<?=$this->partial->packages(compact('packages')); ?>

<?php if($this->authority->canEdit()): ?>

<div class="well">
<legend>Create Package</legend>
<?=$this->form->create(null, array('url' => "/packages/add/", 'method' => 'post')); ?>
	<?=$this->form->hidden('album_id', array('value' => $album->id)); ?>
	<?=$this->form->hidden('slug', array('value' => $album->archive->slug)); ?>
<fieldset>
	<label class="radio inline">
		<input type="radio" name="filesystem" id="secure" value="secure" checked>
		<i class="icon-lock"></i> Secure Package
	</label>
	<label class="radio inline">
		<input type="radio" name="filesystem" id="packages" value="packages">
		<i class="icon-eye-open"></i> Public Package
	</label>
</fieldset>
<br/>
<fieldset>
	<?=$this->form->submit('Create Package', array('class' => 'btn btn-primary')); ?> 
</fieldset>
<?=$this->form->end(); ?> 
</div>
<?php endif; ?>
