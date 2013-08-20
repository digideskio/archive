<?php 

$this->title($work->title);

$authority_can_edit = $this->authority->canEdit();
$authority_is_admin = $this->authority->isAdmin();

$inventory = (\lithium\core\Environment::get('inventory') && ($authority_is_admin));

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Artwork','/works'); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
		<?=$work->title ?>
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li class="active">
		<a href="#">View</a>
	</li>

	<?php if($authority_can_edit): ?>
	
		<li><?=$this->html->link('Edit','/works/edit/'.$work->archive->slug); ?></li>

		<li><?=$this->html->link('Attachments','/works/attachments/'.$work->archive->slug); ?></li>
	
	<?php endif; ?>

		<li><?=$this->html->link('History','/works/history/'.$work->archive->slug); ?></li>

</ul>

<div class="row">
	<div class="span6">

		<?=$this->partial->archives_documents(array('archives_documents' => $archives_documents)); ?>
		
	</div>
	
	<div class="span4">
	
		<?php if($work->annotation): ?>
		
		<div class="popover" style="display:block; position: static; margin-bottom:18px; width:100%">
		<div class="popover-inner">
			<div class="popover-title">
			<strong><?=$work->title ?></strong>
			</div>
			<div class="popover-content">
			<p><?=$work->annotation ?></p>
			</div>
		</div>
		</div>
		
		<?php endif; ?>
	
		<div class="alert alert-block">
    	<p>
    		<?=$this->artwork->caption($work->archive, $work); ?>
    	</p>
		</div>
	
		<table class="table">
			<thead>
				<tr>
					<th><i class="icon-picture"></i></th>
					<th class="meta"></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><i class="icon-font"></i></td>
					<td class="meta">Artist</td>
					<td class="info-title" colspan="3"><?=$work->artists(); ?></td>
				</tr>
				<tr>
					<td><i class="icon-text-height"></i></td>
					<td class="meta">Title</td>
					<td class="info-title" colspan="3"><?=$work->archive->names(); ?></td>
				</tr>
				<tr>
					<td><i class="icon-wrench"></i></td>
					<td class="meta">Materials</td>
					<td colspan="3"><?=$work->materials ?></td>
				</tr>
				<tr>
					<td><i class="icon-resize-full"></i></td>
					<td class="meta">Size</td>
					<td class="info-title" colspan="3"><?=$work->dimensions(); ?></td>
				</tr>
				<tr>
				<tr>
					<td><i class="icon-calendar"></i></td>
					<td class="meta">year</td>
					<td class="info-title"><?=$work->archive->years(); ?></td>
					<td class="meta">Edition</td>
					<td><?=$work->attribute('edition'); ?></td>
				</tr>
					<td><i class="icon-tag"></i></td>
					<td class="meta">Classification</td>
					<td colspan="3"><?=$work->archive->classification ?></td>
				</tr>
				<tr>
					<td><i class="icon-barcode"></i></td>
					<td class="meta">Artwork ID</td>
					<td colspan="3">
						<?php 
						
						if($work->creation_number) {
							echo $work->creation_number;
						} else {
							echo '<span class="label label-warning">Missing</span>';
						}
						
						?>
					</td>
				</tr>
				<?php if($inventory): ?>
				<tr>
					<td><i class="icon-globe"></i></td>
					<td class="meta">Location</td>
					<td colspan="3"><?=$work->location ?></td>
				</tr>
				<?php endif; ?>
				<?php if($inventory): ?>
				<tr>
					<td><i class="icon-gift"></i></td>
					<td class="meta">Inventory</td>
					<td colspan="3">
						<?php
							echo $work->inventory(); 
						?>
					</td>
				</tr>
				<?php endif; ?>
				<tr>
					<td><i class="icon-info-sign"></i></td>
					<td class="meta">Notes</td>
					<td colspan="3">
						<?php 
						
						echo $work->notes();
						
						?>
					
				</tr>
			</tbody>

		</table>

		<?php 
			$hasAlbums = sizeof($albums) > 0;
			$hasExhibitions = sizeof($exhibitions) > 0;
			$hasDocuments = sizeof($archives_documents) > 0;
			$hasLinks = sizeof($work_links) > 0;
		?>

		<?php if ($hasAlbums || $hasExhibitions || $hasDocuments || $hasLinks) : ?>

			<table class="table">
				<thead>
					<tr>
						<th><i class="icon-random"></i></th>
						<th class="meta"></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				
					<?php if ($hasAlbums) : ?>
					<tr>
						<td><i class="icon-briefcase"></i></td>
						<td class="meta">Albums</td>
						<td>
							<ul class="unstyled" style="margin-bottom:0">
							
								<?php foreach($albums as $album): ?>
								<li><strong><?=$this->html->link(
									$album->title,
									$this->url(array('Albums::view', 'slug' => $album->archive->slug))
								);?></strong></li>
								<?php endforeach; ?>
							
							</ul>
						</td>
					</tr>
					<?php endif; ?>

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
									<strong><?=$ad->document->slug?>.<?=$ad->document->format->extension?></strong>
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
						
			
						<?php foreach($work_links as $wl): ?>
				
								<li><a href="/links/view/<?=$wl->link->id?>">
									<strong><?=$wl->link->elision()?></strong>
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
