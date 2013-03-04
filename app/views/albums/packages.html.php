<?php

$this->title('Packages');

if($auth->timezone_id) {
	$tz = new DateTimeZone($auth->timezone_id);
}

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
		<?=$this->html->link('Albums', $this->url(array('Albums::index'))); ?>
		<span class="divider">/</span>
	</li>

	<li class="active">
		Packages
	</li>

	</ul>

</div>

<div class="actions">
	<ul class="nav nav-tabs">
		<li>
			<?=$this->html->link('Index',$this->url(array('Albums::index'))); ?>
		</li>
		<li class="active">
			<?=$this->html->link('Packages',$this->url(array('Albums::packages'))); ?>
		</li>
	</ul>
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

	<?php
		$package_date_created = new DateTime($package->date_created);

		if (isset($tz)) {
			$package_date_created->setTimeZone($tz);
		}
		$package_date_display = $package_date_created->format("Y-m-d H:i:s");
	?>

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
	<td><?=$package_date_display ?></td>
	<td>
			<?=$this->form->create($package, array('url' => "/packages/delete/$package->id", 'method' => 'post', 'style' => 'margin-bottom:0;')); ?>
			<?=$this->form->submit('Delete', array('class' => 'btn btn-mini btn-danger')); ?>
			<?=$this->form->end(); ?>
	</td>

</tr>

<?php endforeach; ?>

</table>
