<?php

$this->title($publication->archive->name);

$auth = $this->authority->auth();

if($auth->timezone_id) {
	$tz = new DateTimeZone($auth->timezone_id);
}

$authority_can_edit = $this->authority->canEdit();

?>

<div id="location" class="row-fluid">

	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Publications','/publications'); ?>
	<span class="divider">/</span>
	</li>

	<li>
	<?=$this->html->link($publication->archive->name,'/publications/view/'.$publication->archive->slug); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
		History
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li>
		<?=$this->html->link('View', $this->url(array('Publications::view', 'slug' => $publication->archive->slug))); ?>
	</li>

	<?php if($authority_can_edit): ?>

		<li><?=$this->html->link('Edit','/publications/edit/'.$publication->archive->slug); ?></li>
		<li><?=$this->html->link('Attachments','/publications/attachments/'.$publication->archive->slug); ?></li>

	<?php endif; ?>

	<li class="active">
		<?=$this->html->link('History', $this->url(array('Publications::history', 'slug' => $publication->archive->slug))); ?>
	</li>

</ul>

<?php $ph = $publications_histories->first(); ?>

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

				<?php if( $ph->annotation ): ?>
					<tr>
					<td style="width:200px"><span class="label">Annotation</a></td>
					<td><em><?=$ph->annotation ?></em></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->name ): ?>
					<tr>
					<td class="meta">Title</td>
					<td><?=$ah->name ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ph->remarks ): ?>
					<tr>
					<td class="meta">Remarks</td>
					<td><?=$ph->remarks ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ph->storage_location ): ?>
					<tr>
					<td class="meta">Storage Location</td>
					<td><?=$ph->storage_location ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ph->storage_number ): ?>
					<tr>
					<td class="meta">Storage Number</td>
					<td><?=$ph->storage_number ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ph->publication_number ): ?>
					<tr>
					<td class="meta">Publication Number</td>
					<td><?=$ph->publication_number ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ph->subject ): ?>
					<tr>
					<td class="meta">Subject</td>
					<td><?=$ph->subject ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ph->language ): ?>
					<tr>
					<td class="meta">Language</td>
					<td><?=$ph->language ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ph->access_date ): ?>
					<tr>
					<td class="meta">Access Date</td>
					<td><?=$ph->access_date ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ph->address ): ?>
					<tr>
					<td class="meta">Address</td>
					<td><?=$ph->address ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ph->author ): ?>
					<tr>
					<td class="meta">Author</td>
					<td><?=$ph->author ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ph->book_title ): ?>
					<tr>
					<td class="meta">Book Title</td>
					<td><?=$ph->book_title ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ph->chapter ): ?>
					<tr>
					<td class="meta">Chapter</td>
					<td><?=$ph->edition ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ph->editor ): ?>
					<tr>
					<td class="meta">Editor</td>
					<td><?=$ph->editor ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ph->format ): ?>
					<tr>
					<td class="meta">Format</td>
					<td><?=$ph->format ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ph->how_published ): ?>
					<tr>
					<td class="meta">How Published</td>
					<td><?=$ph->how_published ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ph->identifier ): ?>
					<tr>
					<td class="meta">Identifier</td>
					<td><?=$ph->identifier ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ph->institution ): ?>
					<tr>
					<td class="meta">Institution</td>
					<td><?=$ph->institution ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ph->isbn ): ?>
					<tr>
					<td class="meta">ISBN</td>
					<td><?=$ph->isbn ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ph->journal ): ?>
					<tr>
					<td class="meta">Journal</td>
					<td><?=$ph->journal ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ph->location ): ?>
					<tr>
					<td class="meta">Location</td>
					<td><?=$ph->location ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ph->note ): ?>
					<tr>
					<td class="meta">Note</td>
					<td><?=$ph->note ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ph->number ): ?>
					<tr>
					<td class="meta">Number</td>
					<td><?=$ph->number ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ph->organization ): ?>
					<tr>
					<td class="meta">Organization</td>
					<td><?=$ph->organization ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ph->original_date ): ?>
					<tr>
					<td class="meta">Original Date</td>
					<td><?=$ph->original_date ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ph->pages ): ?>
					<tr>
					<td class="meta">Pages</td>
					<td><?=$ph->pages ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ph->publisher ): ?>
					<tr>
					<td class="meta">Publisher</td>
					<td><?=$ph->publisher ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ph->school ): ?>
					<tr>
					<td class="meta">School</td>
					<td><?=$ph->school ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ph->series ): ?>
					<tr>
					<td class="meta">Series</td>
					<td><?=$ph->series ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ph->translator ): ?>
					<tr>
					<td class="meta">Translator</td>
					<td><?=$ph->translator ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ph->url ): ?>
					<tr>
					<td class="meta">URL</td>
					<td><?=$ph->url ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ph->volume ): ?>
					<tr>
					<td class="meta">Volume</td>
					<td><?=$ph->volume ?></td>
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

				<?php if( $ah->classification ): ?>
					<tr>
					<td class="meta">Classification</td>
					<td><?=$ah->classification ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->type ): ?>
					<tr>
					<td class="meta">Type</td>
					<td><?=$ah->type ?></td>
					</tr>
				<?php endif; ?>

			</tbody>
		</table>

	<?php $ph = $publications_histories->next(); ?>
	<?php endforeach; ?>
	</div>
</div>
