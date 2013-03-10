<?php

$this->title($publication->title);

if($auth->timezone_id) {
	$tz = new DateTimeZone($auth->timezone_id);
}

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Publications','/publications'); ?>
	<span class="divider">/</span>
	</li>

	<li>
	<?=$this->html->link($publication->title,'/publications/view/'.$publication->archive->slug); ?>
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

	<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>
	
		<li><?=$this->html->link('Edit','/publications/edit/'.$publication->archive->slug); ?></li>
	
	<?php endif; ?>

	<li class="active">
		<?=$this->html->link('History', $this->url(array('Publications::history', 'slug' => $publication->archive->slug))); ?>
	</li>

</ul>

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

				<?php if( $ah->publications_history->annotation ): ?>
					<tr>
					<td style="width:200px"><span class="label">Annotation</a></td>
					<td><em><?=$ah->publications->annotation ?></em></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->publications_history->title ): ?>
					<tr>
					<td class="meta">Title</td>
					<td><?=$ah->publications_history->title ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->publications_history->remarks ): ?>
					<tr>
					<td class="meta">Remarks</td>
					<td><?=$ah->publications_history->remarks ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->publications_history->storage_location ): ?>
					<tr>
					<td class="meta">Storage Location</td>
					<td><?=$ah->publications_history->storage_location ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->publications_history->storage_number ): ?>
					<tr>
					<td class="meta">Storage Number</td>
					<td><?=$ah->publications_history->storage_number ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->publications_history->publication_number ): ?>
					<tr>
					<td class="meta">Publication Number</td>
					<td><?=$ah->publications_history->publication_number ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->publications_history->subject ): ?>
					<tr>
					<td class="meta">Subject</td>
					<td><?=$ah->publications_history->subject ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->publications_history->language ): ?>
					<tr>
					<td class="meta">Language</td>
					<td><?=$ah->publications_history->language ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->publications_history->access_date ): ?>
					<tr>
					<td class="meta">Access Date</td>
					<td><?=$ah->publications_history->access_date ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->publications_history->address ): ?>
					<tr>
					<td class="meta">Address</td>
					<td><?=$ah->publications_history->address ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->publications_history->author ): ?>
					<tr>
					<td class="meta">Author</td>
					<td><?=$ah->publications_history->author ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->publications_history->book_title ): ?>
					<tr>
					<td class="meta">Book Title</td>
					<td><?=$ah->publications_history->book_title ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->publications_history->chapter ): ?>
					<tr>
					<td class="meta">Chapter</td>
					<td><?=$ah->publications_history->edition ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->publications_history->editor ): ?>
					<tr>
					<td class="meta">Editor</td>
					<td><?=$ah->publications_history->editor ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->publications_history->format ): ?>
					<tr>
					<td class="meta">Format</td>
					<td><?=$ah->publications_history->format ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->publications_history->how_published ): ?>
					<tr>
					<td class="meta">How Published</td>
					<td><?=$ah->publications_history->how_published ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->publications_history->identifier ): ?>
					<tr>
					<td class="meta">Identifier</td>
					<td><?=$ah->publications_history->identifier ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->publications_history->institution ): ?>
					<tr>
					<td class="meta">Institution</td>
					<td><?=$ah->publications_history->institution ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->publications_history->isbn ): ?>
					<tr>
					<td class="meta">ISBN</td>
					<td><?=$ah->publications_history->isbn ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->publications_history->journal ): ?>
					<tr>
					<td class="meta">Journal</td>
					<td><?=$ah->publications_history->journal ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->publications_history->location ): ?>
					<tr>
					<td class="meta">Location</td>
					<td><?=$ah->publications_history->location ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->publications_history->note ): ?>
					<tr>
					<td class="meta">Note</td>
					<td><?=$ah->publications_history->note ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->publications_history->number ): ?>
					<tr>
					<td class="meta">Number</td>
					<td><?=$ah->publications_history->number ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->publications_history->organization ): ?>
					<tr>
					<td class="meta">Organization</td>
					<td><?=$ah->publications_history->organization ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->publications_history->original_date ): ?>
					<tr>
					<td class="meta">Original Date</td>
					<td><?=$ah->publications_history->original_date ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->publications_history->pages ): ?>
					<tr>
					<td class="meta">Pages</td>
					<td><?=$ah->publications_history->pages ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->publications_history->publisher ): ?>
					<tr>
					<td class="meta">Publisher</td>
					<td><?=$ah->publications_history->publisher ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->publications_history->school ): ?>
					<tr>
					<td class="meta">School</td>
					<td><?=$ah->publications_history->school ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->publications_history->series ): ?>
					<tr>
					<td class="meta">Series</td>
					<td><?=$ah->publications_history->series ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->publications_history->translator ): ?>
					<tr>
					<td class="meta">Translator</td>
					<td><?=$ah->publications_history->translator ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->publications_history->url ): ?>
					<tr>
					<td class="meta">URL</td>
					<td><?=$ah->publications_history->url ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->publications_history->volume ): ?>
					<tr>
					<td class="meta">Volume</td>
					<td><?=$ah->publications_history->volume ?></td>
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

	<?php endforeach; ?>
	</div>
</div>
