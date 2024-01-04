<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>WatchWise</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body class="p-4">
	<div class="row">
		@foreach ($film->media as $media)
			<div class="col-sm-3">
				<img class="img-fluid" src="{{ $media->getUrl() }}" alt="{{ $media->collection_name }}">
			</div>
		@endforeach
	</div>

	<h1>{{ $film->name->ru ?? '-' }}</h1>
	<span>IMDB: {{ $film->imdb_id }}</span>
	@if (!is_null($film->mpaa))
		<img width="50" src="{{ asset('icons/film_rating/mpaa/' . $film->mpaa . '.png') }}" alt="{{ trans('pg_rating.' . $film->mpaa) }}" title="{{ trans('pg_rating.' . $film->mpaa) }}">
	@endif
	@if (!is_null($film->age_limits))
		<img width="60" src="{{ asset('icons/film_rating/ru/' . $film->age_limits . '.png') }}" alt="">
	@endif
	<h3>
		{{ $film->name->en ?? '-' }}
		@if ($film->name->en != $film->name->original)
			({{ $film->name->original ?? '-' }})
		@endif
	</h3>

	<div>
		@foreach ($film->countries as $country)
			<span class="badge text-bg-secondary">{{ $country->name }}</span>
		@endforeach
	</div>

	<div>
		@foreach ($film->genres as $genre)
			<span class="badge text-bg-secondary">{{ $genre->name }}</span>
		@endforeach
	</div>

	<div class="my-2">
		@foreach ($film->onlineTheaters as $onlineTheater)
			<a class="text-decoration-none mx-2" href="{{ $onlineTheater->pivot->url }}">
				@if ($onlineTheater->media)
					<img width="30" src="{{ $onlineTheater->getFirstMediaUrl('logo', 'logo') }}" alt="">
				@endif
				<span>{{ $onlineTheater->name }}</span>
			</a>
		@endforeach
	</div>

	<div class="row">
		<div class="col-sm-3">
			<div class="card my-4">
				<div class="card-header">Оценки</div>
				<ul class="list-group list-group-flush">
					@foreach ($film->rating as $name => $rating_data)
						@if ($rating_data['review'])
							<li class="list-group-item d-flex justify-content-between align-items-start">
								<div class="me-auto ms-2">{{ $name }}
									@if ($rating_data['count'])
										({{ $rating_data['count'] }})
									@endif
								</div>
								<span class="badge bg-primary rounded-pill">{{ $rating_data['review'] }}</span>
							</li>
						@endif
					@endforeach
				</ul>
			</div>
		</div>
	</div>

	<p><strong>Год</strong>: {{ $film->year }}</p>
	<p><strong>Длина</strong>: {{ $film->length }} мин.</p>

	<p>{{ $film->slogan }}</p>
	<p>{{ $film->description }}</p>
	<p>{{ $film->short_description }}</p>

	<div class="row">
		@foreach ($film->sequelsAndPrequels as $sequelAndPrequel)
			<div class="col-sm-2 my-3" id="{{ $sequelAndPrequel->id }}">
				<a class="card text-decoration-none" href="{{ route('fimls.show', ['film' => $sequelAndPrequel->id]) }}">
					<img class="card-img-top" src="{{ $sequelAndPrequel->getFirstMediaUrl('poster') }}">
					<div class="card-body">
						<h5 class="card-title">{{ $sequelAndPrequel->name->ru ?: $sequelAndPrequel->name->original }}</h5>
					</div>
					<ul class="list-group list-group-flush">
						@if (!is_null($sequelAndPrequel->mpaa))
							<li class="list-group-item">
								<img
									width="50" src="{{ asset('icons/film_rating/mpaa/' . $sequelAndPrequel->mpaa . '.png') }}" alt="{{ trans('pg_rating.' . $sequelAndPrequel->mpaa) }}" title="{{ trans('pg_rating.' . $sequelAndPrequel->mpaa) }}">
							</li>
						@endif
						@if (!is_null($sequelAndPrequel->age_limits))
							<li class="list-group-item">
								<img width="60" src="{{ asset('icons/film_rating/ru/' . $sequelAndPrequel->age_limits . '.png') }}" alt="">
							</li>
						@endif
						<li class="list-group-item">{{ trans('enums.' . $sequelAndPrequel->pivot->type->value) }}</li>
					</ul>
				</a>
			</div>
		@endforeach
	</div>

	@if ($film->serial)
		<ul class="list-group">
			@foreach ($film->seasons()->orderBy('number')->get() as $season)
				@foreach ($season->episodes()->orderBy('episode_number')->get() as $episode)
					<li class="list-group-item">{{ $season->number }}.{{ $episode->episode_number }} — {{ $episode->name->ru ?: $episode->name->en }}</li>
				@endforeach
			@endforeach
		</ul>
	@endif

	<div class="card my-4">
		<div class="card-header">Релизы</div>
		<table class="table-hover table-striped mb-0 table">
			<thead>
				<tr>
					<th>Дата</th>
					<th>Тип</th>
					<th>Подтип</th>
					<th>Компания</th>
					<th>Страна</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($film->distributions()->with(['distributionCompanies', 'country'])->orderBy('date')->get() as $distribution)
					<tr>
						<td>{{ $distribution->date->format('d.m.Y') }}</td>
						<td>{{ $distribution->type ? trans('enums.' . $distribution->type->value) : '' }}</td>
						<td>{{ $distribution->sub_type ? trans('enums.' . $distribution->sub_type->value) : '' }}</td>
						<td>{{ $distribution->distributionCompanies->count() > 1 ? (string) $distribution->distributionCompanies->count() : $distribution->distributionCompanies->first()?->name }}</td>
						<td>{{ $distribution->country?->name }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	<div class="row">
		@foreach ($film->awards as $award)
			<div class="col my-2">
				<div class="card" style="width: 18rem;">
					@if ($award->media()->count())
						<img class="card-img-top" src="{{ $award->getFirstMediaUrl('image') }}" alt="...">
					@endif
					<div class="card-body">
						<h5 class="card-title">{{ $award->name }}</h5>
					</div>
					<ul class="list-group list-group-flush">
						<li class="list-group-item">{{ $award->win ? 'Выиграл' : 'Не выиграл' }}</li>
						<li class="list-group-item">{{ $award->nomination_name }}</li>
						<li class="list-group-item">{{ $award->year }}</li>
					</ul>
				</div>
			</div>
		@endforeach
	</div>

	@foreach ($film->persons()->orderByPivot('profession_key')->get()->groupBy('pivot.profession_key') as $persons)
		<h4 class="mt-4">{{ $persons->first()->pivot->profession_text }}</h4>
		<div class="row">
			@foreach ($persons as $person)
				<div class="col-sm-2 my-2">
					<div class="card" style="width: 18rem;">
						<img class="card-img-top" src="{{ $person->getFirstMediaUrl('poster') }}" alt="...">
						<div class="card-body">
							<h5 class="card-title">{{ $person->name->ru ?: $person->name->en }}</h5>
						</div>
						<ul class="list-group list-group-flush">
							<li class="list-group-item">{{ $person->pivot->profession_text }}</li>
							@if ($person->pivot->description)
								<li class="list-group-item">{{ $person->pivot->description }}</li>
							@endif
							{{-- <li class="list-group-item">{{ $person->pivot->profession_key }}</li> --}}
						</ul>
					</div>
				</div>
			@endforeach
		</div>
	@endforeach

	@dump($film)
	{{-- 'type',
	'start_year',
	'end_year',
	'serial',
	'short',
	'completed', --}}
</body>

</html>
