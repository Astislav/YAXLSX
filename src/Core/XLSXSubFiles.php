<?php
declare(strict_types=1);

namespace YAXLSX\Core;

use YAXLSX\Sheet\XLSXSheet;


final class XLSXSubFiles
{
    public static function appXml(string $company = ''): string
    {
        return /** @lang XML */
            '<?xml version="1.0" encoding="UTF-8"?>' .
            '<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties" ' .
            'xmlns:vt="http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes">' .
            '<Company>' . XLSXTools::filterChars($company) . '</Company>' .
            '</Properties>';
    }

    public static function coreXml(string $title = '', string $subject = '', string $author = ''): string
    {
        $date = date('Y-m-d\TH:i:s.00\Z');

        return /** @lang XML */
            '<?xml version="1.0" encoding="UTF-8"?>' .
            '<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties" ' .
            'xmlns:dc="http://purl.org/dc/elements/1.1/" ' .
            'xmlns:dcmitype="http://purl.org/dc/dcmitype/" ' .
            'xmlns:dcterms="http://purl.org/dc/terms/" ' .
            'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">' .
            '<dc:title>' . XLSXTools::filterChars($title) . '</dc:title>' .
            '<dc:subject>' . XLSXTools::filterChars($subject) . '</dc:subject>' .
            '<dc:creator>' . XLSXTools::filterChars($author) . '</dc:creator>' .
            '<dcterms:created xsi:type="dcterms:W3CDTF">' . $date . '</dcterms:created>' .
            '</cp:coreProperties>';
    }

    public static function relsXml(): string
    {
        return /** @lang XML */
            '<?xml version="1.0" encoding="UTF-8"?>' .
            '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' .
            '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml" />' .
            '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties" Target="docProps/core.xml" />' .
            '<Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties" Target="docProps/app.xml" />' .
            '</Relationships>';
    }

    /** @param string[] $contentTypes */
    public static function contentTypesXml(array $contentTypes): string
    {
        $contentTypesXml = implode($contentTypes);

        return /** @lang XML */
            '<?xml version="1.0" encoding="UTF-8"?>' .
            '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">' .
            '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml" />' .
            '<Default Extension="xml" ContentType="application/xml" />' .
            '<Override PartName="/docProps/app.xml" ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml" />' .
            '<Override PartName="/docProps/core.xml" ContentType="application/vnd.openxmlformats-package.core-properties+xml" />' .
            '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml" />' .
            $contentTypesXml .
            '</Types>';
    }

    /** @param XLSXSheet[] $sheets */
    public static function workbookXml(array $sheets): string
    {
        $sheetsXml = '';
        foreach ($sheets as $i => $sheet) {
            /** @var XLSXSheet $sheet */
            $sheetsXml .= /** @lang XML */
                '<sheet name="' . XLSXTools::filterChars($sheet->sheetName()) . '" ' .
                'sheetId="' . ($i + 1) . '" ' .
                'r:id="rId' . ($i + 1) . '" />';
        }

        return /** @lang XML */
            '<?xml version="1.0" encoding="UTF-8"?>' .
            '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" ' .
            'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">' .
            '<fileVersion appName="xl" lastEdited="4" lowestEdited="4" rupBuild="4507" />' .
            '<bookViews>' .
            '<workbookView xWindow="0" yWindow="0" windowWidth="1920" windowHeight="1080"/>' .
            '</bookViews>' .
            '<sheets>' .
            $sheetsXml .
            '</sheets>' .
            '</workbook>';
    }

    /** @param XLSXSheet[] $sheets */
    public static function workbookRelsXml(array $sheets): string
    {
        $sheetsXml = '';
        foreach (array_keys($sheets) as $i) {
            $sheetsXml .= /** @lang XML */
                '<Relationship Id="rId' . ($i + 1) . '" ' .
                'Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" ' .
                'Target="worksheets/sheet' . ($i + 1) . '.xml" />';
        }

        return /** @lang XML */
            '<?xml version="1.0" encoding="UTF-8"?>' .
            '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' .
            $sheetsXml .
            '<Relationship Id="rId' . (count($sheets) + 1) . '" ' .
            'Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml" />' .
            '</Relationships>';
    }
}
