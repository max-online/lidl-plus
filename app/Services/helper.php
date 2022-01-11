<?php

    if (! function_exists('money')) {
        function money($value)
        {
            return number_format($value, 2, ',', '.');
        }
    }

    if (! function_exists('inPercent')) {
        function inPercent($partion, $total)
        {
            return number_format($partion/$total * 100, 1, ',', '');
        }
    }



