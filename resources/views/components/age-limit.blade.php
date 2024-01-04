@if (!is_null($film->mpaa) || !is_null($film->age_limits))
	<div class="position-absolute ms-1 mt-1">
		@if (!is_null($film->mpaa))
			<div class="d-inline-block bg-light text-uppercase rounded border border-2 border-black px-2 py-1 text-center text-black" style="min-width: 40px;">
				<small>
					<strong>{{ $film->mpaa }}</strong>
				</small>
			</div>
		@endif
		@if (!is_null($film->age_limits))
			<div class="d-inline-block bg-light text-uppercase rounded border border-2 border-black px-2 py-1 text-center text-black" style="min-width: 40px;">
				<small>
					<strong>{{ __($film->age_limits) }}</strong>
				</small>
			</div>
		@endif
	</div>
@endif
