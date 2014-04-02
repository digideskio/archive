<?php $check = lithium\security\Auth::check('default'); $username = $check['username']; ?>
<?php $host = $this->request()->env('HTTP_HOST'); ?>
<?php
	$request_query = $this->request()->query;
	$search_term = null;

	// Check the request, and then the custom storage, for a query search term.
	// This allows the contents of the search input to persist across page views.
	if(isset($request_query['query'])) {
		$search_term = $request_query['query'];
	} else {
		$search_term = lithium\storage\Session::read('query', array('name' => 'custom'));
	}
?>
<?php $architecture = \lithium\core\Environment::get('architecture'); ?>

<div class="navbar navbar-fixed-top navbar-inverse" >
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<a class="brand" href="/home"><?=$host ?></a>

			<div class="nav-collapse">

				<ul class="nav">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							Create
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu">
							<li>
								<?=$this->html->link("Artwork", $this->url(array('Works::add'))); ?>
							</li>
							<?php if ($architecture): ?>
							<li>
								<?=$this->html->link("Architecture", $this->url(array('Architectures::add'))); ?>
							</li>
							<?php endif;?>
							<li>
								<?=$this->html->link("Exhibition", $this->url(array('Exhibitions::add'))); ?>
							</li>
							<li>
								<?=$this->html->link("Publication", $this->url(array('Publications::add'))); ?>
							</li>
							<li class="divider"></li>
							<li>
								<?=$this->html->link("Album", $this->url(array('Albums::add'))); ?>
							</li>
							<li>
								<?=$this->html->link("Document", $this->url(array('Documents::add'))); ?>
							</li>
						</ul>
					</li>
				</ul>

				<ul class="nav pull-right">
					<li>
					<?=$this->html->link($username, $this->url(array('Users::view', 'username' => $username))); ?>
					</li>
					<li>
					<a href="/logout">Logout</a>
					</li>
				</ul>

				<form class="navbar-search pull-right form-search" action="/search" method="get">
					<input type="text" class="search-query span3" placeholder="Search" value="<?=$search_term ?>" name="query" autocomplete="off">
				</form>
			</div>
		</div>
	</div>
</div>
