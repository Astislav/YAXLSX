<?php
declare(strict_types=1);

namespace YAXLSX\Sheet;

use DateTimeImmutable;
use YAXLSX\Core\XLSXTools;
use YAXLSX\Style\XLSXManagedStyle;
use function is_infinite;
use function is_nan;
use function max;

final class XLSXRow
{
    private int $rowIndex;

    private int $colIndex;

    private string $xml;

    private int $groupLevel;

    private XLSXSheet $sheet;

    public function __construct(XLSXSheet $sheet)
    {
        $this->rowIndex = $sheet->rowCount();
        $this->colIndex = 0;
        $this->groupLevel = 0;
        $this->xml = '';
        $this->groupLevel = 0;
        $this->sheet = $sheet;
    }

    private function addCellXml(string $valueXml, string $typeStr, ?XLSXManagedStyle $style = null): XLSXCellCoordinates
    {
        $cellCoordinates = new XLSXCellCoordinates($this->colIndex, $this->rowIndex);
        $excelCell = $cellCoordinates->asExcelCell();

        $this->xml .= "<c r=\"$excelCell\"";
        if ($typeStr) {
            $this->xml .= " t=\"$typeStr\"";
        }

        if ($style) {
            $this->xml .= " s=\"{$style->index()}\"";
        }

        $this->xml .= ">$valueXml</c>";

        $this->colIndex++;

        return $cellCoordinates;
    }

    public function addDate(DateTimeImmutable $date, ?XLSXManagedStyle $style = null): XLSXCellCoordinates
    {
        return $this->addNumber(XLSXTools::convertDateTime($date), $style);
    }

    public function addNumber(float $value, ?XLSXManagedStyle $style = null): XLSXCellCoordinates
    {
        if (is_nan($value) || is_infinite($value)) {
            return $this->addInlineString('', $style);
        }

        $managedStyle = $style ?: $this->sheet->writer()->styleManager()->defaultNumberManagedStyle();

        return $this->addCellXml("<v>$value</v>", 'n', $managedStyle);
    }

    public function addInlineString(string $value, ?XLSXManagedStyle $style = null): XLSXCellCoordinates
    {
        $preparedValue = XLSXTools::filterChars($value);
        $preparedValue = XLSXTools::truncateToMaxLength($preparedValue);

        $managedStyle = $style ?: $this->sheet->writer()->styleManager()->defaultStringManagedStyle();

        return $this->addCellXml(
            $value !== '' ? "<is><t>$preparedValue</t></is>" : '',
            'inlineStr',
            $managedStyle
        );
    }

    public function addFormula(string $value, ?XLSXManagedStyle $style = null): XLSXCellCoordinates
    {
        $preparedValue = XLSXTools::filterChars($value);
        $preparedValue = XLSXTools::truncateToMaxLength($preparedValue);

        $managedStyle = $style ?: $this->sheet->writer()->styleManager()->defaultGeneralManagedStyle();

        return $this->addCellXml(
            $value !== '' ? "<f>$preparedValue</f>" : '',
            '',
            $managedStyle
        );
    }

    public function addEmptyCells(int $count, ?XLSXManagedStyle $style = null): XLSXRectangle
    {
        $left = new XLSXCellCoordinates($this->colIndex, $this->rowIndex);
        $right = $left;

        for ($i = 0; $i < $count; $i++) {
            $right = $this->addCellXml('', '', $style);
        }

        return new XLSXRectangle($left, $right, $this->sheet);
    }

    /** @param array<string> $strings */
    public function addStringsArray(array $strings, ?XLSXManagedStyle $style = null): XLSXRectangle
    {
        $left = new XLSXCellCoordinates($this->colIndex, $this->rowIndex);
        $right = $left;

        foreach ($strings as $string) {
            $right = $this->addInlineString($string, $style);
        }

        return new XLSXRectangle($left, $right, $this->sheet);
    }

    public function setGroupLevel(int $level): self
    {
        $this->groupLevel = max($level, 0);

        return $this;
    }

    public function asXml(): string
    {
        if (!$this->xml) {
            return '';
        }

        $outlineLevel = $this->groupLevel ? ' outlineLevel="' . $this->groupLevel . '"' : '';
        $rowId = $this->rowIndex + 1;

        return /** @lang XML */ "<row r=\"$rowId\"$outlineLevel>$this->xml</row>";
    }
}
