<?php

namespace App\Enums;

enum AppointmentStatusEnum: string
{
    case Confirmed = 'confirmed';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case NoShow = 'no_show';
}
