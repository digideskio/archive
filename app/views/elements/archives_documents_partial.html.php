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
					$num_thumbs = sizeof($archives_documents);
					$span = $num_thumbs > 1 ? 'span3' : 'span6';
					$size = $num_thumbs > 1 ? 'thumb' : 'small';
				?>

			<?php foreach($archives_documents as $ad): ?>

				<li class="<?=$span?>">
					<a href="<?=$this->url(array('Documents::view', 'slug' => $ad->document->slug)); ?>" class="thumbnail">

						<?php if ($ad->document->published): ?>
							<span class="label label-success">Published</span>
						<?php endif; ?>
						<?php if (!$ad->document->published): ?>
							<span class="label label-important">Private</span>
						<?php endif; ?>

                        <?php
                            $document = $ad->document;
                            $file_name = $document->title . '.' .$document->format->extension;
                            $img_url = $this->url(array("Files::$size", 'slug' => $document->slug, 'file' => $file_name));
                        ?>

                            <img src="<?=$img_url ?>" alt="<?=$ad->document->title ?>">
					</a>
				</li>

			<?php endforeach; ?>

			<?php if(sizeof($archives_documents) == 0): ?>
				<li class="<?=$span?>">
				<div class="thumbnail">
				<span class="label label-warning">No Image</span>
				</div>
				</li>
			<?php endif; ?>

		</ul>
