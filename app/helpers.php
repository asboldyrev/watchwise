<?php

if (!function_exists('get_film_id')) {
    function get_film_id(stdClass $filmData)
    {
        if ($filmData?->filmId ?? false) {
            return $filmData->filmId;
        }

        return $filmData->kinopoiskId ?? null;
    }
}
