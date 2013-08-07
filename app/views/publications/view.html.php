<?php 

$this->title($publication->title);

$hasDocuments = sizeof($archives_documents) > 0;
$hasLinks = sizeof($publication_links) > 0;
$hasExhibitions = sizeof($exhibitions) > 0;

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Publications','/publications'); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
	<?=$this->html->link($publication->title,'/publications/view/'.$publication->archive->slug); ?>
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li class="active">
		<a href="#">View</a>
	</li>

	<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>
	
		<li><?=$this->html->link('Edit','/publications/edit/'.$publication->archive->slug); ?></li>
		<li><?=$this->html->link('Attachments','/publications/attachments/'.$publication->archive->slug); ?></li>
	
	<?php endif; ?>

	<li>
		<?=$this->html->link('History', $this->url(array('Publications::history', 'slug' => $publication->archive->slug))); ?>
	</li>

</ul>

<div class="row">
	<div class="span6">

		<?=$this->partial->archives_documents(array('archives_documents' =>  $archives_documents)); ?>	
		
	</div>
	
	<div class="span4">
	
		<div class="alert alert-block">
    	<p>
    		<?=$this->publication->citation($publication->archive, $publication); ?>
    	</p>
		
		<?php if ($hasLinks): ?>

			<?php foreach ($publication_links as $pl): ?>

				<p><a href="<?=$pl->link->url ?>" target="_blank">
					<strong><?=$pl->link->elision()?></strong>
				</a></p>

			<? endforeach; ?>

		<? endif; ?>

		</div>
	
		<table class="table">
			<thead>
				<tr>
					<th><i class="icon-book"></i></th>
					<th class="meta"></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php
					if($publication->archive->classification):
				?>
				<tr>
					<td><i class="icon-tag"></i></td>
					<td class="meta">Category</td>
					<td><?=$publication->archive->classification ?></td>
				</tr>
				<?php endif; ?>
				<tr>
					<td><i class="icon-barcode"></i></td>
					<td class="meta">Publication&nbsp;ID</td>
					<td>
						<?php 
						
						if($publication->publication_number) {
							echo $publication->publication_number;
						} else {
							echo '<span class="label label-important">Missing</span>';
						}
						
						?>
					</td>
				</tr>
				<tr>
					<td><i class="icon-globe"></i></td>
					<td class="meta">Storage</td>
					<td>
						<?php 
							if($publication->storage_number) {
								echo "<span class='label label-success'>$publication->storage_number</span>\n";
							}
							if($publication->storage_location) {
								echo "<span class='label'>$publication->storage_location</span>";
							}
						?>
					</td>
				</tr>
				<tr>
					<td><i class="icon-tags"></i></td>
					<td class="meta">Subjects</td>
					<td><?=$publication->subject ?></td>
				</tr>
				<tr>
					<td><i class="icon-flag"></i></td>
					<td class="meta">Language</td>
					<td><?=$publication->language ?></td>
				</tr>
				<tr>
					<td><i class="icon-comment"></i></td>
					<td class="meta">Remarks</td>
					<td><?=$publication->remarks ?></td>
				</tr>
			</tbody>
		
		</table>
	
		<?php if ($hasDocuments || $hasLinks || $hasExhibitions): ?>

			<table class="table">
				<thead>
					<tr>
						<th><i class="icon-random"></i></th>
						<th class="meta"></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php if ($hasExhibitions) : ?>
					<tr>
						<td><i class="icon-eye-open"></i></td>
						<td class="meta">Exhibitions</td>
						<td>
							<ul class="unstyled" style="margin-bottom:0">
							
								<?php foreach($exhibitions as $exhibition): ?>
								<li><strong><?=$this->html->link(
									$exhibition->title,
									'/exhibitions/view/'.$exhibition->archive->slug
								);?></strong></li>
								<?php endforeach; ?>
							
							</ul>
						</td>
					</tr>
					<?php endif; ?>

					<?php if ($hasDocuments) : ?>
					<tr>
						<td><i class="icon-folder-open"></i></td>
						<td class="meta">Documents</td>
						<td>
							<ul class="unstyled" style="margin-bottom:0">
						
			
						<?php foreach($archives_documents as $ad): ?>
				
								<li><a href="/documents/view/<?=$ad->document->slug?>">
									<strong><?=$ad->document->slug?>.<?=$ad->format->extension?></strong>
								</a></li>
				
						<?php endforeach; ?>
						</ul>
						<td>
					</tr>
					<?php endif; ?>

					<?php if ($hasLinks) : ?>
					<tr>
						<td><i class="icon-bookmark"></i></td>
						<td class="meta">Links</td>
						<td>
							<ul class="unstyled" style="margin-bottom:0">
						
			
						<?php foreach($publication_links as $pl): ?>
				
								<li><a href="/links/view/<?=$pl->link->id?>">
									<strong><?=$pl->link->elision()?></strong>
								</a></li>
				
						<?php endforeach; ?>
						</ul>
						<td>
					</tr>
					<?php endif; ?>
					
				</tbody>
			</table>
		<?php endif; ?>
	
	</div>
</div>
