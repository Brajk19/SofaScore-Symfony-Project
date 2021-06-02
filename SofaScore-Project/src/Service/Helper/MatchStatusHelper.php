<?php

namespace App\Service\Helper;

class MatchStatusHelper
{

    /*
     * nije počela, 1. poluvrijeme, 2. poluvrijeme, pauza, 1.-4. četvrtina, otkazana, završena, produžeci (košarka)
     */
    private const STATUS_CODE = [
        '0' => 'Not started',
        '1' => '1st half',
        '2' => '2nd half',
        '3' => '1st quarter',
        '4' => '2nd quarter',
        '5' => '3rd quarter',
        '6' => '4th quarter',
        '7' => 'Pause',
        '8' => 'Postponed',
        '9' => 'Finished',
        '10' => 'Overtime',
    ];

    public static function getStatus($code): string
    {
        $code = strval($code);

        return self::STATUS_CODE[$code];
    }

}