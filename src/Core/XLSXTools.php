<?php
declare(strict_types=1);

namespace YAXLSX\Core;

use DateTimeImmutable;
use YAXLSX\Sheet\XLSXCellCoordinates;
use function array_slice;
use function array_sum;
use function htmlspecialchars;
use function mb_substr;
use function preg_match;
use function strtr;
use const ENT_QUOTES;
use const ENT_XML1;

final class XLSXTools
{
    private const INVALID_CHARS = "\x00\x01\x02\x03\x04\x05\x06\x07\x08\x0b\x0c\x0e\x0f\x10\x11\x12\x13\x14\x15\x16\x17\x18\x19\x1a\x1b\x1c\x1d\x1e\x1f\x7f";
    private const SPACES = '                              ';
    private const ALPHABET_LENGTH = 26;
    public const BACKED_ALPHABET_LENGTH = 104;
    private const BACKED_ALPHABET =
        [
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
            'G',
            'H',
            'I',
            'J',
            'K',
            'L',
            'M',
            'N',
            'O',
            'P',
            'Q',
            'R',
            'S',
            'T',
            'U',
            'V',
            'W',
            'X',
            'Y',
            'Z',
            'AA',
            'AB',
            'AC',
            'AD',
            'AE',
            'AF',
            'AG',
            'AH',
            'AI',
            'AJ',
            'AK',
            'AL',
            'AM',
            'AN',
            'AO',
            'AP',
            'AQ',
            'AR',
            'AS',
            'AT',
            'AU',
            'AV',
            'AW',
            'AX',
            'AY',
            'AZ',
            'BA',
            'BB',
            'BC',
            'BD',
            'BE',
            'BF',
            'BG',
            'BH',
            'BI',
            'BJ',
            'BK',
            'BL',
            'BM',
            'BN',
            'BO',
            'BP',
            'BQ',
            'BR',
            'BS',
            'BT',
            'BU',
            'BV',
            'BW',
            'BX',
            'BY',
            'BZ',
            'CA',
            'CB',
            'CC',
            'CD',
            'CE',
            'CF',
            'CG',
            'CH',
            'CI',
            'CJ',
            'CK',
            'CL',
            'CM',
            'CN',
            'CO',
            'CP',
            'CQ',
            'CR',
            'CS',
            'CT',
            'CU',
            'CV',
            'CW',
            'CX',
            'CY',
            'CZ',
        ];

    public static function filterChars(string $value): string
    {
        return strtr(htmlspecialchars($value ?? '', ENT_QUOTES | ENT_XML1), self::INVALID_CHARS, self::SPACES);
    }

    public static function truncateToMaxLength(string $value): string
    {
        return mb_substr($value, 0, XLSXConstraints::MAX_CHARS_IN_CELL);
    }

    public static function excelNotation(XLSXCellCoordinates $coordinates, bool $fixed = false): string
    {
        $colId = $coordinates->columnId;
        $rowId = $coordinates->rowId + 1;

        if ($colId < self::BACKED_ALPHABET_LENGTH) {
            return $fixed ? '$' . self::BACKED_ALPHABET[ $colId ] . '$' . $rowId :
                self::BACKED_ALPHABET[ $colId ] . $rowId;
        }

        for ($charsCoordinate = ''; $colId >= 0; $colId = (int) ($colId / self::ALPHABET_LENGTH) - 1) {
            $charsCoordinate = self::BACKED_ALPHABET[ $colId % self::ALPHABET_LENGTH ] . $charsCoordinate;
        }

        return $fixed ? '$' . $charsCoordinate . '$' . $rowId : $charsCoordinate . $rowId;
    }

    /**
     * @todo [Функция - адаптированная копипаста, выглядит диковато, можно бы причесать]
     * thanks to Excel::Writer::XLSX::Worksheet.pm (perl)
     */
    public static function convertDateTime(DateTimeImmutable $dateInput): float
    {
        $days = 0; // Number of days since epoch
        $seconds = 0; // Time expressed as fraction of 24h hours in seconds
        $year = $month = $day = 0;
        $hour = $min = $sec = 0;
        $dateTime = $dateInput->format('Y-m-d H:i:s');
        if (preg_match("/(\d{4})(\d{2})(\d{2})/", $dateTime, $matches)) {
            [ $junk, $year, $month, $day ] = $matches;
        }

        if (preg_match("/(\d+):(\d{2}):(\d{2})/", $dateTime, $matches)) {
            [ $junk, $hour, $min, $sec ] = $matches;
            $seconds = ($hour * 60 * 60 + $min * 60 + $sec) / (24 * 60 * 60);
        }

        // using 1900 as epoch, not 1904, ignoring 1904 special case
        // Special cases for Excel.
        if ("$year-$month-$day" === '1899-12-31') {
            return $seconds;
        } // Excel 1900 epoch

        if ("$year-$month-$day" === '1900-01-00') {
            return $seconds;
        } // Excel 1900 epoch

        if ("$year-$month-$day" === '1900-02-29') {
            return 60 + $seconds;
        } // Excel false leapday

        // We calculate the date by calculating the number of days since the epoch
        // and adjust for the number of leap days. We calculate the number of leap
        // days by normalising the year in relation to the epoch. Thus the year 2000
        // becomes 100 for 4 and 100 year leapdays and 400 for 400 year leapdays.
        $epoch = 1900;
        $offset = 0;
        $norm = 300;
        $range = $year - $epoch;
        // Set month days and check for leap year.
        $leap = ($year % 400 === 0) || (($year % 4 === 0) && ($year % 100)) ? 1 : 0;
        $mdays = [ 31, ($leap ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ];
        // Some boundary checks
        if ($year < $epoch || $year > 9999) {
            return 0;
        }

        if ($month < 1 || $month > 12) {
            return 0;
        }

        if ($day < 1 || $day > $mdays[ $month - 1 ]) {
            return 0;
        }

        // Accumulate the number of days since the epoch.
        $days = $day; // Add days for current month
        $days += array_sum(array_slice($mdays, 0, $month - 1)); // Add days for past months
        $days += $range * 365; // Add days for past years
        $days += (int) ($range / 4); // Add leapdays
        $days -= (int) (($range + $offset) / 100); // Subtract 100 year leapdays
        $days += (int) (($range + $offset + $norm) / 400); // Add 400 year leapdays
        $days -= $leap; // Already counted above
        // Adjust for Excel erroneously treating 1900 as a leap year.
        if ($days > 59) {
            $days++;
        }

        return $days + $seconds;
    }
}
