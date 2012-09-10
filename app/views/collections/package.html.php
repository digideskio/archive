<?php 

$this->title($collection->title);

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
		<?=$this->html->link('Collections','/collections'); ?>
		<span class="divider">/</span>
	</li>

	<li>
		<?=$this->html->link($collection->title,'/collections/view/'.$collection->slug); ?>
		<span class="divider">/</span>
	</li>
	
	<li class="active">
		Package
	</li>

	</ul>

</div>

<div class="actions">

	<ul class="nav nav-tabs">
		<li><?=$this->html->link('View','/collections/view/'.$collection->slug); ?></li>

		<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>
		
			<li><?=$this->html->link('Edit','/collections/edit/'.$collection->slug); ?></li>
		
		<?php endif; ?>

		<li><?=$this->html->link('History','/collections/history/'.$collection->slug); ?></li>

		<li class="active"><?=$this->html->link('Packages','/collections/package/'.$collection->slug); ?></li>

	</ul>

<!--	<div class="btn-toolbar">

			<div class="btn-group">
				<button class="btn btn-inverse dropdown-toggle" data-toggle="dropdown"><i class="icon-gift icon-white"></i> Add Package <span class="caret"</span></button>
				<ul class="dropdown-menu">
					<li><a href="/packages/add"><i class="icon-lock"></i> Secure Package</a></li>
					<li><a href="/packages/add"><i class="icon-download-alt"></i> Public Package</a></li>
				</ul>
			</div>
	</div>
-->
</div>


<table class="table table-striped table-bordered">
<thead>
	<tr>
		<th style="width:14px"><i class="icon-eye-close"></i></th>
		<th>Package</th>
		<th>Date</th>
		<th>Delete</th>
	</tr>
</thead>

<?php foreach ($packages as $package): ?>

<tr>
	
	<td>
		<?php
			$filesystem = $package->filesystem;

			switch ($filesystem) {
				case "secure":
					echo '<i class="icon-lock">';
				break;
				case "packages":
					echo '<i class="icon-eye-open">';
				break;
			}

		?>
	</td>
	<td><?=$this->html->link($package->name, $package->url()); ?></td>
	<td><?=$package->date_created ?></td>
	<td>
			<?=$this->form->create($package, array('url' => "/packages/delete/$package->id", 'method' => 'post', 'style' => 'margin-bottom:0;')); ?>
			<?=$this->form->submit('Delete', array('class' => 'btn btn-mini btn-danger')); ?>
			<?=$this->form->end(); ?>
	</td>

</tr>

<?php endforeach; ?>

</table>

<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?> 

<div class="well">
<legend>Create Package</legend>
<?=$this->form->create(null, array('url' => "/packages/add/", 'method' => 'post')); ?>
	<?=$this->form->hidden('collection_id', array('value' => $collection->id)); ?>
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
