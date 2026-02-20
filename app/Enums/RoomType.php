<?php

namespace App\Enums;

enum RoomType: string
{
    case Normal = 'normal';
    case Suite = 'suite';

    public function getLabel(): string
    {
        return match($this) {
            self::Normal => 'Standard Room',
            self::Suite => 'Luxury Suite',
        };
    }
}