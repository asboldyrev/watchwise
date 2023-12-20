<?php

namespace App\Enums;

enum DistributionType: string
{
    case LOCAL = 'LOCAL';
    case COUNTRY_SPECIFIC = 'COUNTRY_SPECIFIC';
    case PREMIERE = 'PREMIERE';
    case ALL = 'ALL';
    case WORLD_PREMIER = 'WORLD_PREMIER';
}
