@extends('template')

@section('content')
	<div class="row mx-4">
		@foreach ($filmUsers as $film_user)
			<div class="col-sm-2 my-3" id="{{ $film_user->film->id }}">
				<x-film-card :film="$film_user->film" :vote="$film_user->vote" :date="$film_user->date" />
			</div>
		@endforeach
	</div>

	<div class="my-4">
		{{ $filmUsers->links() }}
	</div>
@endsection
