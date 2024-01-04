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
	</style>
</head>

<body class="antialiased">
	@foreach (\App\Models\WatchList::orderBy('name')->get() as $watch_list)
		<h1>{{ $watch_list->name }}</h1>
		<div class="row mx-4">
			@foreach ($watch_list->films()->orderByPivot('date')->get() as $film)
				<div class="col-sm-2 my-3" id="{{ $film->id }}">
					<a class="card text-decoration-none" href="{{ route('fimls.show', ['film' => $film]) }}">
						<div class="card">
							<img class="card-img-top" src="{{ $film->getFirstMediaUrl('poster') }}">
							<div class="card-body">
								<h5 class="card-title">{{ $film->name->ru ?: $film->name->original }}</h5>
							</div>
							<ul class="list-group list-group-flush">
								<li class="list-group-item">{{ $film->pivot->date->format('d.m.Y H:i') ?: '-' }}</li>
							</ul>
						</div>
					</a>
				</div>
			@endforeach
		</div>
	@endforeach
</body>

</html>
