<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NumberToWordsController extends Controller
{

    public function showConvertForm()
    {
        return view('web\convert');
    }

    public function convertNumberToWords(Request $request)
    {
        // Get the number from the request
        $number = $request->input('number');

        // Separate rupees and paise
        $rupees = intval($number);
        $paise = intval(($number - $rupees) * 100);

        // Convert rupees to words
        $rupees_in_words = $this->convertToWords($rupees) . ' Rupees';

        // Convert paise to words
        $paise_in_words = $this->convertToWords($paise) . ' Paise';

        // Construct the final result
        $result = $rupees_in_words . ' and ' . $paise_in_words ;

        // Return the result
        return view('web\convert', compact('result'));
    }

    private function convertToWords($number)
    {
        $numbers_in_words = [
            0 => 'Zero', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six', 7 => 'Seven',
            8 => 'Eight', 9 => 'Nine', 10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen', 14 => 'Fourteen',
            15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen', 19 => 'Nineteen'
        ];

        $tens_in_words = [
            2 => 'Twenty', 3 => 'Thirty', 4 => 'Forty', 5 => 'Fifty', 6 => 'Sixty', 7 => 'Seventy', 8 => 'Eighty', 9 => 'Ninety'
        ];

        $powers_of_ten_suffixes = ['', 'Thousand', 'Lakh', 'Crore'];

        $parts = explode('.', $number);
        $integer_part = $parts[0];
        $decimal_part = isset($parts[1]) ? $parts[1] : '';

        $result = ' ';
        if ($integer_part > 0) {
            $result .= $this->convertGroupToWords($powers_of_ten_suffixes, $integer_part, $numbers_in_words, $tens_in_words);

        }

        if ($decimal_part > 0) {
            if ($integer_part > 0) {
                $result .= ' and ';
            }
            $result .= $this->convertGroupToWords( $powers_of_ten_suffixes, $decimal_part, $numbers_in_words, $tens_in_words);
        }

        return $result;
    }

    private function convertGroupToWords( $powers_of_ten_suffixes, $number, $numbers_in_words, $tens_in_words)
    {
        $result = '';

        $groups = str_split(strrev((string)$number), 3);

        foreach ($groups as $key => $group) {
            $group = strrev($group);
            if ((int)$group !== 0) {
                $hundred = (int)($group / 100);
                $tens_units = $group % 100;


                $result .= ($hundred > 0 ? $numbers_in_words[$hundred] . ' Hundred ' : '');

                if ($tens_units > 0) {
                    if ($tens_units < 20) {
                        $result .= $numbers_in_words[$tens_units];
                    } else {
                        $tens = (int)($tens_units / 10);
                        $units = $tens_units % 10;
                        $result .= $tens_in_words[$tens];
                        $result .= ($units > 0 ? ' ' . $numbers_in_words[$units] : '');
                    }
                }


                if ($key < count($powers_of_ten_suffixes)) {
                    $result =   $powers_of_ten_suffixes[$key] . $result;
                    // $result .= ' ' . $powers_of_ten_suffixes[$key] . ' ';
                }
            }
        }

        return $result;
    }

}
