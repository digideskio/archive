<table class="table table-bordered">
<thead>
	<tr>
		<th style="width:14px"><i class="icon-eye-close"></i></th>
		<th>Album</th>
		<th>Package</th>
		<th>Download</th>
		<th>Date</th>
		<?php if($this->authority->canEdit()): ?>
		<th>Delete</th>
		<?php endif; ?>
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
	<td>
		<strong>
		<?=$this->html->link($package->album->archive->name, $this->url(array('Albums::view', 'slug' => $package->album->archive->slug))); ?>
		</strong>
	</td>
	<td>
	<?=$this->html->link(
		$package->name,
		$this->url($package->url(), array('absolute' => 'true')),
		array('id' => "package-$package->id"));
	?>
	<?=$this->clippy->clip($this->url($package->url(), array('absolute' => 'true'))); ?>
	</td>
	<td><?=$this->html->link('Download', $package->url(), array('class' => 'btn btn-mini btn-success')); ?></td>
	<td><?=$package_date_display ?></td>
	<?php if($this->authority->canEdit()): ?>
	<td>
			<?=$this->form->create($package, array('url' => "/packages/delete/$package->id", 'method' => 'post', 'style' => 'margin-bottom:0;')); ?>
			<?=$this->form->submit('Delete', array('class' => 'btn btn-mini btn-danger')); ?>
			<?=$this->form->end(); ?>
	</td>
	<?php endif; ?>

</tr>

<?php endforeach; ?>

</table>
