<?php $c = strtolower($this->request()->params['controller']); ?>

<div id="sidebar"  class="span2">
<div class="row">
	<div class="span2 affix">
	<div class="well" style="padding: 8px 0;">
		<ul class="nav nav-list">
			<li <?php if ($c == 'collections') echo 'class="active"'; ?> >
				<a href="/collections"><i class="icon-briefcase"></i> Collections</a></li>
			<li <?php if ($c == 'works') echo 'class="active"'; ?> >
				<a href="/works"><i class="icon-picture"></i> Artwork</a></li>
			<li <?php if ($c == 'architectures') echo 'class="active"'; ?> >
				<a href="/architectures"><i class="icon-road"></i> Architecture</a></li>
			<li <?php if ($c == 'exhibitions') echo 'class="active"'; ?> >
				<a href="/exhibitions"><i class="icon-eye-open"></i> Exhibitions</a></li>
			<li <?php if ($c == 'publications') echo 'class="active"'; ?> >
				<a href="/publications"><i class="icon-book"></i> Publications</a></li>
			<li class="divider"></li>
			<li <?php if ($c == 'documents') echo 'class="active"'; ?> >
				<a href="/documents"><i class="icon-hdd"></i> Documents</a></li>
			<li class="divider"></li>
			<li <?php if ($c == 'users') echo 'class="active"'; ?> >
				<a href="/users"><i class="icon-user"></i> Users</a></li>
		</ul>
	</div>
	</div>
</div>
<div class="row">
	<div class="span2">
	<p> </p>
	</div>
</div>
</div>
