<?php 

$this->title($document->title);

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Documents','/documents'); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
	<?=$this->html->link($document->title,'/documents/view/'.$document->slug); ?>
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li class="active">
		<a href="#">View</a>
	</li>

	<?php if($this->authority->canEdit()): ?>
	
		<li><?=$this->html->link('Edit','/documents/edit/'.$document->slug); ?></li>
	
	<?php endif; ?>

</ul>

<div class="row">
	<div class="span6">
	
		<ul class="thumbnails">
		<?php $span = 'span6'; ?>
		<li class="<?=$span?>" >
		<div class="thumbnail">
		<img src="/files/<?=$document->view(array('action' => 'small')); ?>" alt="<?=$document->title ?>">
		</div>
		</li>
		</ul>
		
	</div>
	
	<div class="span4">
	
		<?php if($document->width && $document->height): ?>
		
		<div class="alert alert-block alert-success" style="font-family:monospace">
			<p><?=$document->resolution(); ?></p>
			<p><?=$document->size(); ?></p>
		</div>
		
		<?php endif; ?>

		<?php if($document->remarks): ?>

			<div class="alert alert-block" style="font-family:monospace">
				<p><?=$document->remarks; ?></p>
			</div>

		<?php endif; ?>
	
   		<?php foreach($works as $work): ?>
			<div class="alert alert-block alert-info">
				<p>
    				<?=$this->artwork->caption($work->archive, $work); ?>
					
					<?php
							echo "(Photo &copy; ";
							if($document->credit) { echo $document->credit . ', '; }
							echo $document->year() . ').';
					?>
					
				</p>
			</div>
		<?php endforeach; ?>

		<?php if ($architecture): ?>
		
		<?php foreach($architectures as $architecture): ?>
	
			<div class="alert alert-block alert-info">
			<p>
    			<?=$this->architecture->caption($architecture->archive, $architecture); ?>
					
					<?php
							echo "(Photo &copy; ";
							if($document->credit) { echo $document->credit . ', '; }
							echo $document->year() . ').';
					?>
			</p>
			</div>
		
		<?php endforeach; ?>

		<?php endif; ?>
	
		<?php foreach($exhibitions as $exhibition): ?>
	
			<div class="alert alert-block alert-info">
			<p>
				<?php echo $exhibition->title . ', '; ?>
					
					<?php if($exhibition->venue) { echo $exhibition->venue; } ?>

					<?php
							echo "(Photo &copy; ";
							if($document->credit) { echo $document->credit . ', '; }
							echo $document->year() . ').';
					?>
			</p>
			</div>
		
		<?php endforeach; ?>
		<?php foreach($publications as $publication): ?>
	
			<div class="alert alert-block alert-info">
			<p>
    			<?=$this->publication->citation($publication->archive, $publication); ?>
			</p>
			</div>
		
		<?php endforeach; ?>

		<table class="table">
			<thead>
				<tr>
					<th><i class="icon-hdd"></i></th>
					<th class="meta"></th>
					<th></th>
				</tr>
			</thead>
			<tbody>

				<?php if ($document->published): ?>
					<tr>
						<td><i class="icon-eye-close"></i></td>
						<td class="meta">Status</td>
						<td><span class="label label-success">Published</span</td>
					</tr>

				<?php endif; ?>
				
				<?php if (!$document->published): ?>
					<tr>
						<td><i class="icon-eye-close"></i></td>
						<td class="meta">Status</td>
						<td><span class="label label-important">Private</span</td>
					</tr>

				<?php endif; ?>
	
				<tr>
					<td><i class="icon-barcode"></i></td>
					<td class="meta">File Type</td>
					<td>
						<span class="label"><?= $document->format->mime_type ?></span>
					</td>
				</tr>
				<tr>
					<td><i class="icon-bookmark"></i></td>
					<td class="meta">Title</td>
					<td><?=$document->title ?></td>
				</tr>
				<tr>
					<td><i class="icon-calendar"></i></td>
					<td class="meta">Date</td>
					<td><?=$document->file_date ?></td>
				</tr>
				<tr>
					<td><i class="icon-camera"></i></td>
					<td class="meta">PhotoCredit</td>
					<td>
					<?php
						if($document->credit) { 
							echo $document->credit;
						 } else {
						 	echo '<span class="label label-warning">Unknown</span>';
						 }
					?>
					</td>
				</tr>
				<tr>
					<td><i class="icon-download-alt"></i></td>
					<td class="meta">Download</td>
					<td>
						<?=$this->html->link(
							$document->slug . '.' .$document->format->extension,
							'/files/download/'.$document->slug . '.' .$document->format->extension
						);?>
					</td>
				</tr>
				
			</tbody>
		
		</table>

		<?php
			$hasAlbums = sizeof($albums) > 0;
			$hasArtwork = sizeof($works) > 0;
			$hasArchitecture = $architecture && sizeof($architectures) > 0;
			$hasExhibitions = sizeof($exhibitions) > 0;
			$hasPublications = sizeof($publications) > 0;
		?>

		<?php if ($hasAlbums || $hasArtwork || $hasArchitecture || $hasExhibitions || $hasPublications): ?>

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
					<?php if ($hasArtwork) : ?> 
				
					<tr>
						<td><i class="icon-picture"></i></td>
						<td class="meta">Artwork</td>
						<td>
							<ul class="unstyled" style="margin-bottom:0">
							
								<?php foreach($works as $work): ?>
								<li><strong><?=$this->html->link(
									$work->title,
									'/works/view/'.$work->archive->slug
								);?></strong></li>
								<?php endforeach; ?>
							
							</ul>
						</td>
					</tr>
					
					<?php endif; ?>

					<?php if ($hasArchitecture) : ?> 
					<tr>
						<td><i class="icon-road"></i></td>
						<td class="meta">Architecture</td>
						<td>
							<ul class="unstyled" style="margin-bottom:0">
							
								<?php foreach($architectures as $architecture): ?>
								<li><strong><?=$this->html->link(
									$architecture->archive->name,
									'/architectures/view/'.$architecture->archive->slug
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
					
					<?php if ($hasPublications) : ?> 
					<tr>
						<td><i class="icon-book"></i></td>
						<td class="meta">Publications</td>
						<td>
							<ul class="unstyled" style="margin-bottom:0">
							
								<?php foreach($publications as $publication): ?>
								<li><strong><?=$this->html->link(
									$publication->title,
									'/publications/view/'.$publication->archive->slug
								);?></strong></li>
								<?php endforeach; ?>
							
							</ul>
						</td>
					</tr>
					<?php endif; ?>
				</tbody>
			</table>
		<?php endif; ?>
	</div>
</div>
