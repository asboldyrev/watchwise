<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PersonResource;
use App\Models\Person;

class PersonController extends Controller
{

    public function list()
    {
        $persons = Person
            ::withCount([
                'films',
                'nominations' => fn($query) => $query->where('is_win', true),
            ])
            ->with([
                'media',
            ])
            ->orderBy('name->ru')
            ->paginate(24);

        return PersonResource::collection($persons);
    }

    public function show(Person $person)
    {
        $person
            ->load([
                'media',
                'films' => fn($query) => $query
                    ->with([
                        'countries',
                        'genres',
                        'media',
                    ])
                    ->orderBy('year', 'desc')
            ]);

        return PersonResource::make($person);
    }
}
