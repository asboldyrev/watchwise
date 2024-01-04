<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>WatchWise</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	<style>
		.vote-0 {
			background-color: #adb5bd;
		}

		.vote-1 {
			background-color: #58151c;
		}

		.vote-2 {
			background-color: #842029;
		}

		.vote-3 {
			background-color: #b02a37;
		}

		.vote-4 {
			background-color: #dc3545;
		}

		.vote-5 {
			background-color: #e35d6a;
		}

		.vote-6 {
			background-color: #0a3622;
		}

		.vote-7 {
			background-color: #0f5132;
		}

		.vote-8 {
			background-color: #146c43;
		}

		.vote-9 {
			background-color: #198754;
		}

		.vote-10 {
			background-color: #479f76;
		}

		svg {
			width: 25px;
			height: 25px;
		}
	</style>
</head>

<body>
	<div class="container">
		<div class="row mx-4">
			@foreach ($filmUsers as $film_user)
				<div class="col-sm-2 my-3" id="{{ $film_user->film->id }}">
					<a class="card text-decoration-none" href="{{ route('fimls.show', ['film' => $film_user->film->id]) }}">
						<img class="card-img-top" src="{{ $film_user->film->getFirstMediaUrl('poster') }}">
						<div class="card-body">
							<h5 class="card-title">{{ $film_user->film->name->ru ?: $film_user->film->name->original }}</h5>
							<span class="badge {{ get_vote_class($film_user->film) }}">{{ $film_user->vote ?: '-' }}</span>
						</div>
						<ul class="list-group list-group-flush">
							<li class="list-group-item">{{ $film_user->date->format('d.m.Y H:i') ?: '-' }}</li>
							@if (!is_null($film_user->film->mpaa) || !is_null($film_user->film->age_limits))
								<li class="list-group-item">
									@if (!is_null($film_user->film->mpaa))
										<img width="50" src="{{ asset('icons/film_rating/mpaa/' . $film_user->film->mpaa . '.png') }}" alt="{{ trans('pg_rating.' . $film_user->film->mpaa) }}" title="{{ trans('pg_rating.' . $film_user->film->mpaa) }}">
									@endif
									@if (!is_null($film_user->film->age_limits))
										<img width="60" src="{{ asset('icons/film_rating/ru/' . $film_user->film->age_limits . '.png') }}" alt="">
									@endif
								</li>
							@endif
						</ul>
					</a>
				</div>
			@endforeach
		</div>

		<div class="my-4">
			{{ $filmUsers->links() }}
		</div>
	</div>

</body>

</html>
