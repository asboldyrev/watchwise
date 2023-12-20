<?php

namespace App\Enums;

enum Profession: string
{
    case WRITER = 'WRITER';
    case OPERATOR = 'OPERATOR';
    case EDITOR = 'EDITOR';
    case COMPOSER = 'COMPOSER';
    case PRODUCER_USSR = 'PRODUCER_USSR';
    case TRANSLATOR = 'TRANSLATOR';
    case DIRECTOR = 'DIRECTOR';
    case DESIGN = 'DESIGN';
    case PRODUCER = 'PRODUCER';
    case ACTOR = 'ACTOR';
    case VOICE_DIRECTOR = 'VOICE_DIRECTOR';
    case UNKNOWN = 'UNKNOWN';
}
