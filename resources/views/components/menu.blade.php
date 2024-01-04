<nav class="navbar navbar-expand-lg bg-body-tertiary">
	<div class="container-fluid">
		<a class="navbar-brand" href="#">WatchWise</a>
		<button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" type="button" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="navbar-collapse collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mb-lg-0 mb-2 me-auto">
				@if ($currentRouteName == 'votes.list')
					<li class="nav-item">
						<span class="nav-link active" aria-current="page">Оценки</span>
					</li>
				@else
					<li class="nav-item">
						<a class="nav-link" href="{{ route('votes.list') }}">Оценки</a>
					</li>
				@endif

				@if ($currentRouteName == 'watch-lists.show')
					<li class="nav-item">
						<span class="nav-link active" aria-current="page">Списки</span>
					</li>
				@else
					<li class="nav-item">
						<a class="nav-link" href="{{ route('watch-lists.show', ['watchList' => $firstWatchList]) }}">Списки</a>
					</li>
				@endif
			</ul>
		</div>
	</div>
</nav>
