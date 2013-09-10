<?php

$this->title('Home');

?>

<div class="well" style="margin-bottom: 14px;">
	<h1>Welcome.</h1>
	<p>All of our Artworks, Documents, and Exhibitions are collected here. Use the sidebar to navigate through the site.</p>
</div>

<?php foreach ($alerts as $alert): ?>
	
	<div class="alert alert-block">
		<h4><?=$alert->subject ?> <small class="meta"><?=$alert->date_created ?></small></h4>
		<p><?=$alert->body ?></p>
	</div>

<?php endforeach; ?>

<?php if ($updates->count()): ?>

	<div class="alert alert-info alert-block">
		<table class="table">
		<?php foreach ($updates as $update): ?>
			<tr><td>
				<strong><?=$update->subject ?></strong> &mdash; <?=$update->body ;?>
				<small class="meta"><?=$update->date_created ?>
			</td></tr>

		<? endforeach; ?>
		</table>

	</div>

<?php endif; ?>

<div class="row">

	<div class="span3">

		<div class="breadcrumb">

			<h3><?=$this->html->link('Artwork', 'Works::index'); ?></h3>

			<?php if (!$works || sizeof($works) == 0): ?>

				<?=$this->html->link('Add an artwork', 'Works::add'); ?> to the Archive.	

			<?php endif; ?>

			<?php if ($works && sizeof($works) > 0): ?>

				<table class="table table-condensed update-table">

					<tbody>

						<?php foreach ($works as $work): ?>
							<tr>
								<td class="initials">
								<span style="font-size: smaller;">
									<?=$this->html->link($work->user->initials(), array('Users::view',  'username' => $work->user->username)); ?>
								</span>
								</td>
								<td class="title">
								<div class="no-wrap">
								<strong style="font-size: smaller;">
									<?=$this->html->link($work->name, array('Works::view', 'slug' => $work->slug)); ?>
								</strong>
								</div>
								</td>
							</tr>
						<?php endforeach; ?>

					</tbody>

				</table>

			<?php endif; ?>

		</div>

	</div>
	
	<div class="span4">

		<div class="breadcrumb">

			<h3><?=$this->html->link('Exhibitions', 'Exhibitions::index'); ?></h3>

			<?php if (!$exhibitions || sizeof($exhibitions) == 0): ?>

				<?=$this->html->link('Add an exhibition', 'Exhibitions::add'); ?> to the Archive.	

			<?php endif; ?>

			<?php if ($exhibitions && sizeof($exhibitions) > 0): ?>

				<table class="table table-condensed update-table">

					<tbody>

						<?php foreach ($exhibitions as $exhibition): ?>
							<tr>
								<td class="initials">
								<span style="font-size: smaller;">
									<?=$this->html->link($exhibition->user->initials(), array('Users::view',  'username' => $exhibition->user->username)); ?>
								</span>
								</td>
								<td class="title">
								<div class="no-wrap">
								<strong style="font-size: smaller;">
									<?=$this->html->link($exhibition->name, array('Exhibitions::view', 'slug' => $exhibition->slug)); ?>
								</strong>
								</div>
								</td>
							</tr>
						<?php endforeach; ?>

					</tbody>

				</table>

			<?php endif; ?>

		</div>

	</div>

	<div class="span3">

		<div class="breadcrumb">

			<h3><?=$this->html->link('Publications', 'Publications::index'); ?></h3>

			<?php if (!$publications || sizeof($publications) == 0): ?>

				<?=$this->html->link('Add a publication', 'Publications::add'); ?> to the Archive.	

			<?php endif; ?>

			<?php if ($publications && sizeof($publications) > 0): ?>

				<table class="table table-condensed update-table">

					<tbody>

						<?php foreach ($publications as $publication): ?>
							<tr>
								<td class="initials">
								<span style="font-size: smaller;">
									<?=$this->html->link($publication->user->initials(), array('Users::view',  'username' => $publication->user->username)); ?>
								</span>
								</td>
								<td class="title">
								<div class="no-wrap">
								<strong style="font-size: smaller;">
									<?=$this->html->link($publication->name, array('Publications::view', 'slug' => $publication->slug)); ?>
								</strong>
								</div>
								</td>
							</tr>
						<?php endforeach; ?>

					</tbody>

				</table>

			<?php endif; ?>

		</div>

	</div>

</div>

<?php if ($documents && (sizeof($documents) > 0)): ?>

	<?=$this->partial->documents(compact('documents')); ?>

<?php endif; ?>

<style>

	.update-table {
		table-layout:fixed;
		width: 100%;
	}

	.update-table .initials {
		width: 15%;
	}

	.update-table .title {
		width: 85%;
	}

	.update-table .title .no-wrap {
		text-overflow: ellipsis; white-space: nowrap; overflow: hidden;
	}

</style>
