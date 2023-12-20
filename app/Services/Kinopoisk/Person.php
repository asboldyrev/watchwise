<?php

namespace App\Services\Kinopoisk;

use App\Models\Person as ModelsPerson;
use App\Services\KinopoiskApiUnofficial\Client;
use Carbon\Carbon;
use GuzzleHttp\Exception\ClientException;

class Person
{
    public static function import(int|string $personId)
    {
        $person = ModelsPerson::find($personId);

        if (!$person) {
            $client = new Client();

            $try = 3;
            while ($try > 0) {
                try {
                    $person_data = $client->getPerson($personId);
                    break;
                } catch (ClientException $exception) {
                    if ($exception->getCode() == 404) {
                        $try--;
                        sleep(1);
                    } else {
                        dd($exception);
                    }
                }
            }

            $person = ModelsPerson::create([
                'id' => $person_data->personId,
                'name' => [
                    'ru' => $person_data->nameRu,
                    'en' => $person_data->nameEn,
                ],
                'sex' => $person_data->sex,
                'growth' => $person_data->growth,
                'birthday' => $person_data->birthday ? Carbon::createFromFormat('Y-m-d', $person_data->birthday) : null,
                'death' => $person_data->death ? Carbon::createFromFormat('Y-m-d', $person_data->death) : null,
                'age' => $person_data->age < 255 && $person_data->age > 0 ? $person_data->age : null,
                'birth_place' => $person_data->birthplace,
                'death_place' => $person_data->deathplace,
                'has_awards' => $person_data->hasAwards,
                'profession' => $person_data->profession,
                'facts' => $person_data->facts,
            ]);

            $person
                ->addMediaFromUrl($person_data->posterUrl)
                ->withCustomProperties(['orig_url' => $person_data->posterUrl])
                ->toMediaCollection('poster');
        }

        return $person;
    }
}
