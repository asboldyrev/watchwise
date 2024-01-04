<a class="card text-decoration-none" href="{{ route('fimls.show', ['film' => $film->id]) }}">
	<x-age-limit :$film />

	<img class="card-img-top" src="{{ $film->getFirstMediaUrl('poster') }}">

	<div class="card-body">
		<h5 class="card-title">{{ $film->getName() }}</h5>
		@if ($vote)
			<div class="progress" role="progressbar" aria-valuenow="{{ $vote }}" aria-valuemin="0" aria-valuemax="10">
				<div class="progress-bar {{ get_vote_class($vote) }}" style="width: {{ $vote * 10 }}%">{{ $vote ?: '-' }}</div>
			</div>
		@endif
	</div>

	<ul class="list-group list-group-flush">
		<li class="list-group-item">{{ $date->format('d.m.Y H:i') ?: '-' }}</li>
	</ul>
</a>
