<?php
declare(strict_types=1);

namespace YAXLSX\Core;

/**
 * http://www.ecma-international.org/publications/standards/Ecma-376.htm
 * http://officeopenxml.com/SSstyles.php
 * http://office.microsoft.com/en-us/excel-help/excel-specifications-and-limits-HP010073849.aspx
 * https://c-rex.net/projects/samples/ooxml/e1/search.html?searchQuery=
 */

use YAXLSX\Chart\XLSXChartSpace;
use YAXLSX\Chart\XLSXChartSpaceManager;
use YAXLSX\Drawing\XLSXDrawing;
use YAXLSX\Drawing\XLSXDrawingManager;
use YAXLSX\Sheet\XLSXSheet;
use YAXLSX\Style\XLSXStyleManager;
use YAXLSX\Xml\XLSXContentTypes;
use ZipArchive;

final class XLSXWriter
{
    public string $company;
    public string $author;
    public string $subject;
    public string $title;
    private string $tempDir;
    /** @var XLSXSheet[] */
    private array $sheets;
    private XLSXStyleManager $styleManager;

    private XLSXChartSpaceManager $chartSpaceManager;

    private XLSXDrawingManager $drawingManager;

    public function __construct(string $tempDir)
    {
        $this->company = 'Company';
        $this->author = 'Author';
        $this->subject = 'Subject';
        $this->title = 'Title';
        $this->tempDir = $tempDir;

        $this->styleManager = new XLSXStyleManager();
        $this->chartSpaceManager = new XLSXChartSpaceManager();
        $this->drawingManager = new XLSXDrawingManager();
    }

    /** @param int[] $columnWidths */
    public function newSheet(string $sheetName, array $columnWidths = []): XLSXSheet
    {
        $sheet = new XLSXSheet($this, $sheetName);
        foreach ($columnWidths as $index => $width) {
            $sheet->setupColumn($index, $width);
        }

        $sheet->open();
        $this->sheets[] = $sheet;

        return $sheet;
    }

    public function saveToFile(string $fileName): void
    {
        $zip = new ZipArchive();
        $zip->open($fileName, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $zip->addEmptyDir('docProps/');
        $zip->addEmptyDir('_rels/');
        $zip->addEmptyDir('xl/worksheets/');
        $zip->addEmptyDir('xl/_rels/');

        $contentTypes = [];
        $this->finalizeStyles($zip, $contentTypes);
        $this->finalizeSheets($zip, $contentTypes);
        $this->finalizeChartSpaces($zip, $contentTypes);
        $this->finalizeDrawings($zip, $contentTypes);

        $zip->addFromString('[Content_Types].xml', XLSXSubFiles::contentTypesXml($contentTypes));
        $zip->addFromString('docProps/app.xml', XLSXSubFiles::appXml($this->company));
        $zip->addFromString('docProps/core.xml', XLSXSubFiles::coreXml($this->title, $this->subject, $this->author));
        $zip->addFromString('_rels/.rels', XLSXSubFiles::relsXml());

        $zip->close();
    }

    /** @param string[] $contentTypes */
    private function finalizeStyles(ZipArchive $zip, array &$contentTypes): void
    {
        $inZipName = 'xl/styles.xml';
        $contentTypes[] = XLSXContentTypes::forStyles($inZipName);
        $zip->addFromString($inZipName, $this->styleManager->asXml());
    }

    /** @param string[] $contentTypes */
    private function finalizeSheets(ZipArchive $zip, array &$contentTypes): void
    {
        foreach ($this->sheets as $index => $sheet) {
            $sheet->close();
            $inZipName = 'xl/worksheets/sheet' . ($index + 1) . '.xml';
            $contentTypes[] = XLSXContentTypes::forSheet($inZipName);
            $zip->addFile($sheet->fileName(), $inZipName);

            $rels = $sheet->relsXml();
            if (!$rels) {
                continue;
            }

            $zip->addEmptyDir('xl/worksheets/_rels');
            $zip->addFromString('xl/worksheets/_rels/sheet' . ($index + 1) . '.xml.rels', $rels);
        }

        $zip->addFromString('xl/workbook.xml', XLSXSubFiles::workbookXml($this->sheets));
        $zip->addFromString('xl/_rels/workbook.xml.rels', XLSXSubFiles::workbookRelsXml($this->sheets));
    }

    /** @param string[] $contentTypes */
    private function finalizeChartSpaces(ZipArchive $zip, array &$contentTypes): void
    {
        if ($this->chartSpaceManager->isEmpty()) {
            return;
        }

        $fileNames = $this->chartSpaceManager->save($this->tempDir);
        $zip->addEmptyDir('xl/charts/');
        foreach ($fileNames as $index => $fileName) {
            $inZipName = "xl/charts/chart$index.xml";
            $contentTypes[] = XLSXContentTypes::forChartSpace($inZipName);
            $zip->addFile($fileName, $inZipName);
        }
    }

    /** @param string[] $contentTypes */
    private function finalizeDrawings(ZipArchive $zip, array &$contentTypes): void
    {
        if ($this->drawingManager->isEmpty()) {
            return;
        }

        $drawingFileNames = $this->drawingManager->saveDrawings($this->tempDir);
        $relationFilesNames = $this->drawingManager->saveRelations($this->tempDir);

        $zip->addEmptyDir('xl/drawings/');
        $zip->addEmptyDir('xl/drawings/_rels');

        foreach ($drawingFileNames as $index => $fileName) {
            $inZipName = "xl/drawings/drawing$index.xml";
            $contentTypes[] = XLSXContentTypes::forDrawing($inZipName);
            $zip->addFile($fileName, $inZipName);
        }

        foreach ($relationFilesNames as $index => $fileName) {
            $inZipName = "xl/drawings/_rels/drawing$index.xml.rels";
            $zip->addFile($fileName, $inZipName);
        }
    }

    public function temporaryDir(): string
    {
        return $this->tempDir;
    }

    public function styleManager(): XLSXStyleManager
    {
        return $this->styleManager;
    }

    public function newChartSpace(): XLSXChartSpace
    {
        return $this->chartSpaceManager->newChartSpace();
    }

    public function newDrawing(): XLSXDrawing
    {
        return $this->drawingManager->newDrawing();
    }
}
