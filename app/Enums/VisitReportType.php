<?php

namespace App\Enums;

enum VisitReportType
{
    case BY_GENDER;

    case BY_AGE_GROUP;

    case BY_TIME_OF_DAY;

    case BY_SPECIALIZATION;

    case BY_BPJS;

    case BY_PRESCRIPTION;

    case OVER_TIME;
}
