@extends('template')

@section('content')
	<div class="my-4">
		<ul class="nav nav-pills justify-content-center">
			@foreach ($watchLists as $watch_list)
				@if ($watch_list->id == $currentWatchList->id)
					<li class="nav-item">
						<span class="nav-link active" aria-current="page">
							{{ $watch_list->name }} <small class="ms-1">({{ $watch_list->films_count }})</small>
						</span>
					</li>
				@else
					<li class="nav-item">
						<a class="nav-link" href="{{ route('watch-lists.show', ['watchList' => $watch_list]) }}">
							{{ $watch_list->name }} <small class="ms-1">({{ $watch_list->films_count }})</small>
						</a>
					</li>
				@endif
			@endforeach
		</ul>
	</div>

	<h1>{{ $currentWatchList->name }}</h1>

	<div class="row mx-4">
		@foreach ($films as $film)
			<div class="col-sm-2 my-3" id="{{ $film->id }}">
				<x-film-card :$film :date="$film->pivot->date" />
			</div>
		@endforeach

		<div class="my-4">
			{{ $films->links() }}
		</div>
	</div>
@endsection
