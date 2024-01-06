@extends('template')

@section('content')

	<div class="row my-5">
		<div class="col-sm-4">

			<h1>
				{{ $film->getName() }}
				@if ($film->name->ru && ($film->name->ru || $film->name->original))
					<span class="h4 text-secondary">({{ $film->name->en ?: $film->name->original }})</span>
				@endif
			</h1>

			<div class="mb-4">
				<x-age-limit :$film />
				<img class="img-fluid" src="{{ $film->getFirstMediaUrl('poster') }}">
			</div>
		</div>

		<div class="col-sm-8">
			<ul class="nav nav-tabs" role="tablist">
				<li class="nav-item" role="presentation">
					<button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#main" type="button" role="tab">Основное</button>
				</li>

				@if ($film->sequelsAndPrequels->count())
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#siquels-and-priquels" type="button" role="tab">Сиквелы и приквелы</button>
					</li>
				@endif

				<li class="nav-item" role="presentation">
					<button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#relises" type="button" role="tab">Релизы</button>
				</li>

				@if ($film->awards->count())
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#awards" type="button" role="tab">Награды</button>
					</li>
				@endif

				<li class="nav-item" role="presentation">
					<button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#staff" type="button" role="tab">Состав</button>
				</li>

				@if ($film->serial)
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#seasons" type="button" role="tab">Сезоны</button>
					</li>
				@endif
			</ul>

			<div class="tab-content" id="myTabContent">

				{{-- Основное --}}
				<div class="tab-pane fade show active" id="main" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
					<div class="mt-4">
						<p><small class="border-secondary rounded-1 text-secondary border px-2 py-1">{{ $film->getSing() }}</small></p>
						<p><strong>Год</strong>: {{ $film->year }}</p>
						<p><strong>Длина</strong>: {{ $film->length }} мин.</p>
						<p>
							<em>{{ $film->slogan }}</em>
						</p>
						<p>{{ $film->description }}</p>
						{{-- <p>{{ $film->short_description }}</p> --}}
					</div>


					<div class="mt-4">
						@if ($film->imdb_id)
							<a class="me-3" href="{{ 'https://www.imdb.com/title/' . $film->imdb_id }}" target="_blank">IMDB</a>
						@endif

						@if ($film->serial)
							<a href="{{ 'https://www.kinopoisk.ru/series/' . $film->id }}" target="_blank">Кинопоиск</a>
						@else
							<a href="{{ 'https://www.kinopoisk.ru/film/840725' . $film->id }}" target="_blank">Кинопоиск</a>
						@endif
					</div>

					<div class="mt-4">
						<div>
							@foreach ($film->countries as $country)
								<span class="badge text-bg-secondary">{{ $country->name }}</span>
							@endforeach
						</div>

						<div class="mt-2">
							@foreach ($film->genres as $genre)
								<span class="badge text-bg-secondary">{{ $genre->name }}</span>
							@endforeach
						</div>
					</div>

					<div class="row">
						<div class="col-sm-6">
							<div class="card mt-4">
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

						@if ($film->onlineTheaters->count())
							<div class="col-sm-6">
								<div class="card mt-4">
									<div class="card-header">Онлайн кинотеатры</div>
									<ul class="list-group list-group-flush">
										@foreach ($film->onlineTheaters as $onlineTheater)
											<li class="list-group-item">
												<a class="text-decoration-none mx-2" href="{{ $onlineTheater->pivot->url }}">
													@if ($onlineTheater->media)
														<img class="img-fluid" width="30" src="{{ $onlineTheater->getFirstMediaUrl('logo', 'logo') }}" alt="">
													@endif
													<span>{{ $onlineTheater->name }}</span>
												</a>
											</li>
										@endforeach
									</ul>
								</div>
							</div>
						@endif
					</div>
				</div>

				{{-- Сиквелы и приквелы --}}
				@if ($film->sequelsAndPrequels->count())
					<div class="tab-pane fade" id="siquels-and-priquels" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
						<div class="row">
							@foreach ($film->sequelsAndPrequels as $sequelAndPrequel)
								<div class="col-sm-3 my-3" id="{{ $sequelAndPrequel->id }}">
									<x-film-card :film="$sequelAndPrequel" />
								</div>
							@endforeach
						</div>
					</div>
				@endif

				{{-- Релизы --}}
				<div class="tab-pane fade" id="relises" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
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

				{{-- Награды --}}
				@if ($film->awards->count())
					<div class="tab-pane fade" id="awards" role="tabpanel" aria-labelledby="disabled-tab" tabindex="0">
						<table class="table-hover table-striped mb-0 table">
							<thead>
								<tr>
									<th></th>
									<th>Название</th>
									<th>Год</th>
									<th>Номинация</th>
									<th>Выиграл</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($film->awards as $award)
									<tr>
										<td>
											@if ($award->media()->count())
												<img height="70" src="{{ $award->getFirstMediaUrl('image') }}">
											@endif
										</td>
										<td>{{ $award->name }}</td>
										<td>{{ $award->year }}</td>
										<td>{{ $award->nomination_name }}</td>
										<td>{{ $award->win ? 'Да' : 'Нет' }}</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				@endif

				{{-- Состав --}}
				<div class="tab-pane fade mt-5" id="staff" role="tabpanel" aria-labelledby="disabled-tab" tabindex="0">

					<ul class="nav nav-tabs" role="tablist">
						@foreach ($film->persons()->orderByPivot('profession_key')->select(['film_person.profession_text', 'film_person.profession_key'])->distinct()->pluck('film_person.profession_text', 'film_person.profession_key') as $key => $name)
							<li class="nav-item" role="presentation">
								<button class="nav-link {{ $loop->first ? 'active' : '' }}" id="home-tab" data-bs-toggle="tab" data-bs-target="#{{ $key }}" type="button" role="tab">{{ $name }}</button>
							</li>
						@endforeach
					</ul>

					<div class="tab-content mt-3" id="myTabContent">
						@foreach ($film->persons()->orderByPivot('profession_key')->get()->groupBy('pivot.profession_key') as $persons)
							<div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $persons->first()->pivot->profession_key }}" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
								<div class="row">
									@foreach ($persons as $person)
										<div class="col-sm-3 my-2">
											<div class="card">
												<img class="card-img-top img-fluid" src="{{ $person->getFirstMediaUrl('poster') }}" alt="...">
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
							</div>
						@endforeach

					</div>
				</div>

				{{-- Сезоны --}}
				@if ($film->serial)
					<div class="tab-pane fade" id="seasons" role="tabpanel" aria-labelledby="disabled-tab" tabindex="0">
						<table class="table-hover table-striped mb-0 table">
							<thead>
								<tr>
									<th>Сезон</th>
									<th>Серия</th>
									<th>Название</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($film->seasons()->orderBy('number')->get() as $season)
									@foreach ($season->episodes()->orderBy('episode_number')->get() as $episode)
										<tr>
											<td>{{ $season->number }}</td>
											<td>{{ $episode->episode_number }}</td>
											<td>{{ $episode->name->ru ?: $episode->name->en }}</td>
										</tr>
									@endforeach
								@endforeach
							</tbody>
						</table>
					</div>
				@endif
			</div>
		</div>
	</div>

	{{-- @dump($film) --}}
	{{-- 'type',
'start_year',
'end_year',
'serial',
'short',
'completed', --}}
@endsection
