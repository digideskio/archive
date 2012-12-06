		<?php if (isset($showBar) && $showBar): ?>


	<div class="navbar">
		<div class="navbar-inner">
			<ul class="nav">
				<li class="meta"><a href="#">Documents</a></li>
			</ul>
		</div>
	</div>

		<?php endif; ?>
		
		<ul class="thumbnails">
			
				<?php
					$num_thumbs = sizeof($models_documents);
					$span = 'span3';// $span = $num_thumbs > 1 ? 'span3' : 'span6';
					$size = 'thumb';// $size = $num_thumbs > 1 ? 'thumb' : 'small';
				?>
		
			<?php foreach($models_documents as $md): ?>
			
				<li class="<?=$span?>">
					<a href="<?=$this->url(array('Documents::view', 'slug' => $md->document->slug)); ?>" class="thumbnail">
						
						<?php if ($md->document->published): ?>
							<span class="label label-success">Published</span>
						<?php endif; ?>
						<?php if (!$md->document->published): ?>
							<span class="label label-important">Private</span>
						<?php endif; ?>

						<img src="/files/<?=$md->document->view(array('action' => $size)); ?>" alt="<?=$md->document->title ?>">
					</a>
				</li>
			
			<?php endforeach; ?>
			
			<?php if(sizeof($models_documents) == 0): ?>
				<li class="<?=$span?>">
				<div class="thumbnail">
				<span class="label label-warning">No Image</span>
				</div>
				</li>
			<?php endif; ?>
		
		</ul>
