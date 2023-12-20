<?php

namespace App\Enums;

enum FilmRelationType: string
{
    case SEQUEL = 'SEQUEL';
    case PREQUEL = 'PREQUEL';
    case REMAKE = 'REMAKE';
    case UNKNOWN = 'UNKNOWN';
}
