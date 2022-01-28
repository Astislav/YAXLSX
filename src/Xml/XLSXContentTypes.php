<?php
declare(strict_types=1);

namespace YAXLSX\Xml;

final class XLSXContentTypes
{
    private const SHEET_CONTENT_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml';
    private const CHART_SPACE_CONTENT_TYPE = 'application/vnd.openxmlformats-officedocument.drawingml.chart+xml';
    private const STYLES_CONTENT_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml';
    private const DRAWING_CONTENT_TYPE = 'application/vnd.openxmlformats-officedocument.drawing+xml';

    public static function forSheet(string $fileName): string
    {
        return self::asOverrideTag(self::SHEET_CONTENT_TYPE, $fileName);
    }

    public static function forChartSpace(string $fileName): string
    {
        return self::asOverrideTag(self::CHART_SPACE_CONTENT_TYPE, $fileName);
    }

    public static function forStyles(string $fileName): string
    {
        return self::asOverrideTag(self::STYLES_CONTENT_TYPE, $fileName);
    }

    public static function forDrawing(string $fileName): string
    {
        return self::asOverrideTag(self::DRAWING_CONTENT_TYPE, $fileName);
    }

    private static function asOverrideTag(string $contentType, string $fileName): string
    {
        return /** @lang XML */ '<Override PartName="/' . $fileName . '" ContentType="' . $contentType . '"/>';
    }
}
