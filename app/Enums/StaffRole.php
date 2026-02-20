<?php

namespace App\Enums;

enum StaffRole: string {
    case Cleaner = 'cleaner';
    case Maintenance = 'maintenance';
    case Manager = 'manager';
}