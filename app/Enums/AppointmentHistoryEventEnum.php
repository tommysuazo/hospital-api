<?php

namespace App\Enums;

enum AppointmentHistoryEventEnum: string
{
    case Created = 'created';
    case Rescheduled = 'rescheduled';
    case Cancelled = 'cancelled';
    case Completed = 'completed';
    case NoShow = 'no_show';
}
