<?php

namespace App\Enums;

enum CheckUpStatus: string
{
    case WAITING = 'waiting';

    case FINISHED = 'finished';

    case SKIPPED = 'skipped';
}
