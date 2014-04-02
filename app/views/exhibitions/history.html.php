<?php

$this->title($exhibition->archive->name);

$auth = $this->authority->auth();

if($auth->timezone_id) {
	$tz = new DateTimeZone($auth->timezone_id);
}

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
	<span class="divider">/</span>
	</li>

	<li class="active">
		History
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li><?=$this->html->link('View','/exhibitions/view/'.$exhibition->archive->slug); ?></li>

	<?php if($authority_can_edit): ?>

		<li><?=$this->html->link('Edit','/exhibitions/edit/'.$exhibition->archive->slug); ?></li>
		<li><?=$this->html->link('Attachments','/exhibitions/attachments/'.$exhibition->archive->slug); ?></li>

	<?php endif; ?>

	<li class="active">
		<a href="#">
			History
		</a>
	</li>

</ul>

<?php $eh = $exhibitions_histories->first(); ?>

<div class="row">
	<div class="span10">
	<?php foreach($archives_histories as $ah): ?>

		<?php
			$start_date_string = date("Y-m-d H:i:s", $ah->start_date);
			$start_date_time = new DateTime($start_date_string);

			if (isset($tz)) {
				$start_date_time->setTimeZone($tz);
			}

			$start_date_display = $start_date_time->format("Y-m-d H:i:s T");
		?>

		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<td colspan="2">
						<strong><?=$start_date_display ?></strong>
						<?php if( $ah->user->id ): ?>
						<small style="font-size: smaller;">by <?=$this->html->link($ah->user->name,'/users/view/'.$ah->user->username); ?></small>
						<?php endif; ?>
					</td>
				</tr>
			</thead>

		<tbody>

				<?php if( $ah->names() ): ?>
					<tr>
					<td class="meta">Title</td>
					<td>
						<?=$ah->names(); ?>
					</td>
					</tr>
				<?php endif; ?>

				<?php if( $eh->curator ): ?>
					<tr>
					<td class="meta">Curator</td>
					<td><?=$eh->curator ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $eh->venue ): ?>
					<tr>
					<td class="meta">Venue</td>
					<td><?=$eh->venue ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $eh->city ): ?>
					<tr>
					<td class="meta">City</td>
					<td><?=$eh->city ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $eh->country ): ?>
					<tr>
					<td class="meta">Country</td>
					<td><?=$eh->country ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->start_date() ): ?>
					<tr>
					<td class="meta">Earliest Date</td>
					<td><?=$ah->start_date_formatted() ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->end_date() ): ?>
					<tr>
					<td class="meta">Latest Date</td>
					<td><?=$ah->end_date_formatted() ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->type ): ?>
					<tr>
					<td class="meta">Type</td>
					<td><?=$ah->type ?> Show</td>
					</tr>
				<?php endif; ?>

				<?php if( $eh->remarks ): ?>
					<tr>
					<td class="meta">Remarks</td>
					<td><?=$eh->remarks ?></td>
					</tr>
				<?php endif; ?>

		</tbody>
		</table>

	<?php $eh = $exhibitions_histories->next(); ?>
	<?php endforeach; ?>
	</div>
</div>
