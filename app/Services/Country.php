<?php

namespace App\Services;

class Country
{
    public static function findByName(string $countryName)
    {
        $file = file_get_contents(database_path('countries.json'));
        $countries = collect(json_decode($file));

        if ($countryName == 'США') {
            $countryName = 'Соединенные штаты';
        }

        return $countries
            ->filter(fn($country) => mb_strtolower($country->translations->ru) == mb_strtolower($countryName))
            ->values()
            ->first();
    }

    public static function getFlagPath(string $countryName): string
    {
        $country = self::findByName($countryName);
        if (!$country) {
            return '';
        }

        $iso_code = mb_strtolower($country->iso2);

        return "/flags/png-100/{$iso_code}.png";
    }
}
