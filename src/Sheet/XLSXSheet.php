<?php
declare(strict_types=1);

namespace YAXLSX\Sheet;

use LogicException;
use YAXLSX\Chart\XLSXChartSpace;
use YAXLSX\Core\XLSXColumnSetup;
use YAXLSX\Core\XLSXListDataValidation;
use YAXLSX\Core\XLSXStreamFile;
use YAXLSX\Core\XLSXWriter;
use YAXLSX\Drawing\XLSXDrawing;
use YAXLSX\Exceptions\XLSXRectangleIntersection;
use function assert;
use function count;
use function implode;
use function max;
use function min;

final class XLSXSheet
{
    private XLSXStreamFile $stream;

    private string $sheetName;

    private int $rowCount;

    private ?XLSXRow $currentRow;

    private int $rowGroupLevel;

    /** @var array<array<int>> */
    private array $columnGroups;

    /** @var float[] */
    private array $columnWidths;

    private ?XLSXDrawing $currentDrawing = null;

    private XLSXWriter $writer;

    /** @var XLSXRectangle[] */
    private array $mergedRects;

    /** @var array<string> */
    private array $dataValidationXmls;

    private bool $headerWritten;

    public function __construct(XLSXWriter $writer, string $sheetName)
    {
        $this->writer = $writer;
        $this->stream = XLSXStreamFile::tempFile($writer->temporaryDir(), 'xlsx_sheet_');
        $this->sheetName = $sheetName;
        $this->columnGroups = [];
        $this->columnWidths = [];
        $this->mergedRects = [];
        $this->dataValidationXmls = [];
        $this->rowCount = 0;
        $this->rowGroupLevel = 0;
        $this->currentDrawing = null;
        $this->headerWritten = false;
    }

    public function __destruct()
    {
        $this->stream->delete();
    }

    /** @internal Не вызывайте метод открытия листа вручную. XLSXWriter сам вызовет его, когда нужно */
    public function open(): void
    {
        if ($this->stream->opened()) {
            return;
        }

        $this->currentRow = null;
        $this->stream->open();
        $this->headerWritten = false;
    }

    private function writeHeader(): void
    {
        if (!$this->stream->opened()) {
            return;
        }

        $xmlHeader = /** @lang XML */
            '<?xml version="1.0" encoding="UTF-8"?>' .
            '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" ' .
            'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">' .
            '<sheetPr><outlinePr summaryBelow="0" summaryRight="0"/></sheetPr>' .
            '<sheetFormatPr defaultRowHeight="15" />' .
            $this->columnsXml() .
            '<sheetData>';

        $this->stream->writeString($xmlHeader);
        $this->headerWritten = true;
    }

    /** @internal Не вызывайте метод закрытия листа вручную. XLSXWriter сам вызовет его, когда нужно */
    public function close(): void
    {
        if (!$this->stream->opened()) {
            return;
        }

        if (!$this->headerWritten) {
            $this->writeHeader();
        }

        $this->writeCurrentRow();

        $this->stream->writeString('</sheetData>');
        $this->stream->writeString($this->drawingsXml());
        $this->stream->writeString($this->mergedCellsXml());
        $this->stream->writeString($this->dataValidationsXml());
        $this->stream->writeString('</worksheet>');
        $this->stream->close();
    }

    public function relsXml(): string
    {
        if (!$this->currentDrawing) {
            return '';
        }

        $relsXml = /** @lang XML */
            '<Relationship ' .
            'Id="rId' . $this->currentDrawing->index() . '" ' .
            'Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/drawing" ' .
            'Target="../drawings/drawing' . $this->currentDrawing->index() . '.xml"/>';

        return /** @lang XML */
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' .
            $relsXml .
            '</Relationships>';
    }

    public function setupColumn(int $index, float $width): void
    {
        if ($this->headerWritten) {
            throw new LogicException("You can\'t setup columns after first row is saved");
        }

        $this->columnWidths[ $index ] = $width;
    }

    private function columnsXml(): string
    {
        $columnSetups = [];
        foreach ($this->columnGroups as $group) {
            for ($colId = $group[ 0 ]; $colId <= $group[ 1 ]; $colId++) {
                $columnSetup = $columnSetups[ $colId + 1 ] ?? new XLSXColumnSetup($colId + 1);
                $columnSetup->setGroupLevel($columnSetup->groupLevel + 1);
                $columnSetups[ $colId + 1 ] = $columnSetup;
            }
        }

        foreach ($this->columnWidths as $colId => $width) {
            $columnSetup = $columnSetups[ $colId + 1 ] ?? new XLSXColumnSetup($colId + 1);
            $columnSetup->setWidth($width);
            $columnSetups[ $colId + 1 ] = $columnSetup;
        }

        $xml = '';
        foreach ($columnSetups as $columnSetup) {
            assert($columnSetup instanceof XLSXColumnSetup);
            $xml .= /** @lang XML */
                '<col min="' . $columnSetup->columnId . '" max="' . $columnSetup->columnId . '"' .
                ($columnSetup->width > 0 ? ' customWidth="1" width="' . $columnSetup->width . '"' : '') .
                ($columnSetup->groupLevel > 0 ? ' outlineLevel="' . $columnSetup->groupLevel . '"' : '') .
                '/>';
        }

        return $xml ? "<cols>$xml</cols>" : '';
    }

    public function openRowGroup(): void
    {
        if (!$this->currentRow) {
            return;
        }

        $this->rowGroupLevel = min($this->rowGroupLevel + 1, 8);
        $this->currentRow->setGroupLevel($this->rowGroupLevel);
    }

    public function closeRowGroup(): void
    {
        if (!$this->currentRow) {
            return;
        }

        $this->rowGroupLevel = max($this->rowGroupLevel - 1, 0);
        $this->currentRow->setGroupLevel($this->rowGroupLevel);
    }

    public function addColumnGroup(int $fromId, int $toId): void
    {
        $this->columnGroups[] = [ $fromId, $toId ];
    }

    public function newRow(): XLSXRow
    {
        if (!$this->stream->opened()) {
            $this->open();
        }

        if (!$this->headerWritten) {
            $this->writeHeader();
        }

        $this->writeCurrentRow();

        $this->currentRow = new XLSXRow($this);
        $this->currentRow->setGroupLevel($this->rowGroupLevel);
        $this->rowCount++;

        return $this->currentRow;
    }

    public function setDrawing(XLSXDrawing $drawing): void
    {
        if ($this->currentDrawing === $drawing) {
            return;
        }

        $this->currentDrawing = $drawing;
    }

    public function newDrawing(): XLSXDrawing
    {
        $drawing = $this->writer->newDrawing();

        $this->setDrawing($drawing);

        return $drawing;
    }

    public function currentDrawing(): XLSXDrawing
    {
        return $this->currentDrawing ?: $this->newDrawing();
    }

    public function newChartSpace(): XLSXChartSpace
    {
        $drawing = $this->currentDrawing ?: $this->newDrawing();

        $chartSpace = $this->writer->newChartSpace();
        $drawing->addChartSpace($chartSpace);

        return $chartSpace;
    }

    private function writeCurrentRow(): void
    {
        if (!$this->currentRow) {
            return;
        }

        $this->stream->writeString($this->currentRow->asXml());
    }

    public function addMergedCellRectangle(XLSXRectangle $rectangle): void
    {
        foreach ($this->mergedRects as $existingRectangle) {
            if ($existingRectangle->intersectRectangle($rectangle)) {
                throw XLSXRectangleIntersection::fromRects($rectangle, $existingRectangle);
            }
        }

        $this->mergedRects[] = $rectangle;
    }

    public function markRectangleAsDictionary(
        XLSXRectangle $sourceRectangle,
        XLSXRectangle $destinationRectangle
    ): void {
        $this->dataValidationXmls[] = XLSXListDataValidation::asXml(
            $sourceRectangle,
            $destinationRectangle
        );
    }

    public function fileName(): string
    {
        return $this->stream->fileName();
    }

    public function sheetName(): string
    {
        return $this->sheetName;
    }

    public function writer(): XLSXWriter
    {
        return $this->writer;
    }

    public function rowCount(): int
    {
        return $this->rowCount;
    }

    private function drawingsXml(): string
    {
        return $this->currentDrawing ? '<drawing r:id="rId' . $this->currentDrawing->index() . '"/>' : '';
    }

    private function mergedCellsXml(): string
    {
        if (!$this->mergedRects) {
            return '';
        }

        $rectsXml = '';
        foreach ($this->mergedRects as $rect) {
            $rectsXml .= '<mergeCell ref = "' . $rect->asExcelCellsRegion() . '"/>';
        }

        return /** @lang XML */
            '<mergeCells count = "' . count($this->mergedRects) . '" >' .
            $rectsXml .
            '</mergeCells >';
    }

    private function dataValidationsXml(): string
    {
        if (!$this->dataValidationXmls) {
            return '';
        }

        return /** @lang XML */
            '<dataValidations count="' . count($this->dataValidationXmls) . '">' .
            implode($this->dataValidationXmls) .
            '</dataValidations>';
    }
}
