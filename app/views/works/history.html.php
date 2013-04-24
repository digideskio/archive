<?php 

$this->title($work->title);

if($auth->timezone_id) {
	$tz = new DateTimeZone($auth->timezone_id);
}

?>

	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Artwork','/works'); ?>
	<span class="divider">/</span>
	</li>

	<li>
	<?=$this->html->link($work->title,'/works/view/'.$work->archive->slug); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		History
	</li>

	</ul>
<ul class="nav nav-tabs">
	<li class="active">
		<li><?=$this->html->link('View','/works/view/'.$work->archive->slug); ?></li>
	</li>

	<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>
	
		<li><?=$this->html->link('Edit','/works/edit/'.$work->archive->slug); ?></li>
	
	<?php endif; ?>

		<li class="active"><?=$this->html->link('History','/works/history/'.$work->archive->slug); ?></li>

</ul>

<?php $wh = $works_histories->first(); ?>

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

				<?php if( $wh->annotation ): ?>
					<tr>
					<td style="width:200px"><span class="label">Annotation</a></td>
					<td><em><?=$wh->annotation ?></em></td>
					</tr>
				<?php endif; ?>

				<?php if( $wh->artist || $wh->attribute('artist_native_name') ): ?>
					<tr>
					<td class="meta">Artist</td>
					<td>
						<?=$wh->artist ?>
						<?php if ($wh->attribute('artist_native_name')) { echo '(' . $wh->attribute('artist_native_name') . ')'; } ?>
					</td>
					</tr>
				<?php endif; ?>

				<?php if( $wh->title || $ah->native_name ): ?>
					<tr>
					<td class="meta">Title</td>
					<td>
						<?=$wh->title ?>
						<?php if ($ah->native_name) { echo '(' . $ah->native_name . ')'; } ?>
					</td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->classification ): ?>
					<tr>
					<td class="meta">Classification</td>
					<td><?=$ah->classification ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $wh->materials ): ?>
					<tr>
					<td class="meta">Materials</td>
					<td><?=$wh->materials ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $wh->attribute('edition') ): ?>
					<tr>
					<td class="meta">Edition</td>
					<td><?=$wh->attribute('edition'); ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $wh->quantity ): ?>
					<tr>
					<td class="meta">Quantity</td>
					<td><?=$wh->quantity ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $wh->remarks ): ?>
					<tr>
					<td class="meta">Remarks</td>
					<td><?=$wh->remarks ?></td>
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


				<?php if( $wh->creation_number ): ?>
					<tr>
					<td class="meta">Artwork ID</td>
					<td><?=$wh->creation_number ?></td>
					</tr>
				<?php endif; ?>


				<?php if( $wh->height ): ?>
					<tr>
					<td class="meta">Height</td>
					<td><?=$wh->height ?> cm</td>
					</tr>
				<?php endif; ?>


				<?php if( $wh->width ): ?>
					<tr>
					<td class="meta">Width</td>
					<td><?=$wh->width ?> cm</td>
					</tr>
				<?php endif; ?>


				<?php if( $wh->depth ): ?>
					<tr>
					<td class="meta">Depth</td>
					<td><?=$wh->depth ?> cm</td>
					</tr>
				<?php endif; ?>


				<?php if( $wh->diameter ): ?>
					<tr>
					<td class="meta">Diameter</td>
					<td><?=$wh->diameter ?> cm</td>
					</tr>
				<?php endif; ?>


				<?php if( $wh->weight ): ?>
					<tr>
					<td class="meta">Weight</td>
					<td><?=$wh->weight ?> kg</td>
					</tr>
				<?php endif; ?>


				<?php if( $wh->running_time ): ?>
					<tr>
					<td class="meta">Running Time</td>
					<td><?=$wh->running_time ?></td>
					</tr>
				<?php endif; ?>


				<?php if( $wh->measurement_remarks ): ?>
					<tr>
					<td class="meta">Measurement Remarks</td>
					<td><?=$wh->measurement_remarks ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $wh->attribute('signed') ): ?>
					<tr>
					<td class="meta">Signed</td>
					<td>Yes</td>
					</tr>
				<?php endif; ?>

				<?php if( $wh->attribute('framed') ): ?>
					<tr>
					<td class="meta">Framed</td>
					<td>Yes</td>
					</tr>
				<?php endif; ?>

			</tbody>
		</table>

	<?php $wh = $works_histories->next(); ?>
	<?php endforeach; ?>
	</div>
</div>

