<?php

namespace App\Enums;

enum AppointmentForEnum: string
{
    case Self = 'self';
    case Dependent = 'dependent';
}
