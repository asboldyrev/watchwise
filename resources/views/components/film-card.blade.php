<a class="card text-decoration-none" href="{{ route('fimls.show', ['film' => $film->id]) }}">
	<img class="card-img-top" src="{{ $film->getFirstMediaUrl('poster') }}">

	<div class="card-body">
		<h5 class="card-title">{{ $film->name->ru ?: $film->name->original }}</h5>
		@if ($vote)
			<span class="badge {{ get_vote_class($vote) }}">{{ $vote ?: '-' }}</span>
		@endif
	</div>

	<ul class="list-group list-group-flush">
		<li class="list-group-item">{{ $date->format('d.m.Y H:i') ?: '-' }}</li>
		@if (!is_null($film->mpaa) || !is_null($film->age_limits))
			<li class="list-group-item">
				@if (!is_null($film->mpaa))
					<img width="50" src="{{ asset('icons/film_rating/mpaa/' . $film->mpaa . '.png') }}" alt="{{ trans('pg_rating.' . $film->mpaa) }}" title="{{ trans('pg_rating.' . $film->mpaa) }}">
				@endif
				@if (!is_null($film->age_limits))
					<img width="60" src="{{ asset('icons/film_rating/ru/' . $film->age_limits . '.png') }}" alt="">
				@endif
			</li>
		@endif
	</ul>
</a>
