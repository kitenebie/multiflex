<?php

if (!function_exists('numberToWords')) {
    function numberToWords($number) {
        $number = number_format($number, 2, ".", "");
        $parts = explode(".", $number);
        $integerPart = (int)$parts[0];
        $decimalPart = isset($parts[1]) ? (int)$parts[1] : 0;

        $words = '';

        if ($integerPart > 0) {
            $words .= convertIntegerToWords($integerPart) . ' Pesos';
        }

        if ($decimalPart > 0) {
            $words .= ' and ' . convertIntegerToWords($decimalPart) . ' Centavos';
        }

        return $words . ' Only';
    }
}

if (!function_exists('convertIntegerToWords')) {
    function convertIntegerToWords($number) {
        $ones = array(
            0 => 'Zero', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four',
            5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen',
            14 => 'Fourteen', 15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen',
            18 => 'Eighteen', 19 => 'Nineteen'
        );
        $tens = array(
            2 => 'Twenty', 3 => 'Thirty', 4 => 'Forty', 5 => 'Fifty',
            6 => 'Sixty', 7 => 'Seventy', 8 => 'Eighty', 9 => 'Ninety'
        );
        $hundreds = array(
            1 => 'One Hundred', 2 => 'Two Hundred', 3 => 'Three Hundred',
            4 => 'Four Hundred', 5 => 'Five Hundred', 6 => 'Six Hundred',
            7 => 'Seven Hundred', 8 => 'Eight Hundred', 9 => 'Nine Hundred'
        );

        $words = '';

        if ($number == 0) {
            return 'Zero';
        }

        if ($number < 20) {
            return $ones[$number];
        }

        if ($number < 100) {
            $words .= $tens[floor($number / 10)];
            if ($number % 10 > 0) {
                $words .= ' ' . $ones[$number % 10];
            }
            return $words;
        }

        if ($number < 1000) {
            $words .= $hundreds[floor($number / 100)];
            $remainder = $number % 100;
            if ($remainder > 0) {
                $words .= ' ' . convertIntegerToWords($remainder);
            }
            return $words;
        }

        if ($number < 1000000) {
            $words .= convertIntegerToWords(floor($number / 1000)) . ' Thousand';
            $remainder = $number % 1000;
            if ($remainder > 0) {
                $words .= ' ' . convertIntegerToWords($remainder);
            }
            return $words;
        }

        // Add more for millions if needed
        return $words;
    }
}