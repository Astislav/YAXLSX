<?php
declare(strict_types=1);

namespace YAXLSX\tests\Core;


use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use YAXLSX\Border\XLSXBorder;
use YAXLSX\Border\XLSXBorderLineStyle;
use YAXLSX\Chart\BarChart\XLSXBarChart;
use YAXLSX\Chart\XLSXDataLabelPosition;
use YAXLSX\Core\XLSXColor;
use YAXLSX\Core\XLSXDefaults;
use YAXLSX\Core\XLSXWriter;
use YAXLSX\Fill\XLSXFill;
use YAXLSX\Font\XLSXFont;
use YAXLSX\Format\XLSXFormat;
use YAXLSX\Sheet\XLSXRectangle;
use YAXLSX\Style\XLSXStyle;
use function file_exists;
use function random_int;
use function sys_get_temp_dir;
use function unlink;

class XLSXWriterTest extends TestCase
{
    /**
     * @test
     * @group write
     */
    public function it_creates_document(): void
    {
        $temp = sys_get_temp_dir() . '/';
        $testFile = $temp . 'test.xlsx';
        if (file_exists($testFile)) {
            unlink($testFile);
        }

        $writer = new XLSXWriter($temp);
        $writer->company = '<Рога и Копыта>';
        $writer->title = '<Трактат по велосипедостроению>';
        $writer->subject = '<Сомнительная тема>';
        $writer->author = '<Автотест>';

        /** Генерация шрифтов и форматов */
        $redBoldArial = new XLSXFont('Comic Sans MS', 8, XLSXColor::newRed(), true);
        $greenBoldArial = new XLSXFont('Comic Sans MS', 8, XLSXColor::fromColorInt(0x006400), true);
        $roubleFormat = new XLSXFormat('# ##0.00\ _$₽;-# ##0.00\ _$₽');
        $mainTitleFont = new XLSXFont('Arial', 12, new XLSXColor(200, 0, 0), true);
        $titleFont = new XLSXFont('Comic Sans MS', 12, XLSXColor::newRed(), true);

        /** Генерация и привязка стилей, как совокупностей шрифта и формата */
        $styleManager = $writer->styleManager();
        $roublePosStyle = $styleManager->fromFontAndFormat($greenBoldArial, $roubleFormat);
        $roubleNegStyle = $styleManager->fromFontAndFormat($redBoldArial, $roubleFormat);
        $floatStyle = $styleManager->fromFormatParams('#,##0.00');
        $euroAutoColorStyle = $styleManager->fromFormatParams('#,##0.00 [$€-407];[RED]-#,##0.00 [$€-407]');
        $dateStyle = $styleManager->fromFormatParams('День:dd Месяц:mm Год:yyyy Часы:hh Секунды:ss');

        $borderLeft = new XLSXBorder();
        $borderLeft->left
            ->setLineStyle(XLSXBorderLineStyle::medium())
            ->setColor(XLSXColor::newRed());
        $borderLeft->top
            ->setLineStyle(XLSXBorderLineStyle::medium())
            ->setColor(XLSXColor::newRed());
        $borderLeft->bottom
            ->setLineStyle(XLSXBorderLineStyle::medium())
            ->setColor(XLSXColor::newRed());

        $borderMiddle = new XLSXBorder();
        $borderMiddle->top
            ->setLineStyle(XLSXBorderLineStyle::medium())
            ->setColor(XLSXColor::newRed());
        $borderMiddle->bottom
            ->setLineStyle(XLSXBorderLineStyle::medium())
            ->setColor(XLSXColor::newRed());

        $borderRight = new XLSXBorder();
        $borderRight->right
            ->setLineStyle(XLSXBorderLineStyle::medium())
            ->setColor(XLSXColor::newRed());
        $borderRight->top
            ->setLineStyle(XLSXBorderLineStyle::medium())
            ->setColor(XLSXColor::newRed());
        $borderRight->bottom
            ->setLineStyle(XLSXBorderLineStyle::medium())
            ->setColor(XLSXColor::newRed());

        $mainTitleLeftStyle = $styleManager->fromStyle(
            new XLSXStyle(
                $mainTitleFont,
                XLSXDefaults::stringFormat(),
                $borderLeft,
                false,
                true
            )
        );

        $mainTitleMiddleStyle = $styleManager->fromStyle(
            new XLSXStyle(
                $mainTitleFont,
                XLSXDefaults::stringFormat(),
                $borderMiddle,
                false,
                true
            )
        );

        $mainTitleRightStyle = $styleManager->fromStyle(
            new XLSXStyle(
                $mainTitleFont,
                XLSXDefaults::stringFormat(),
                $borderRight,
                false,
                true
            )
        );

        $titleStyle = $styleManager->fromStyle(
            new XLSXStyle(
                $titleFont,
                XLSXDefaults::stringFormat(),
                XLSXDefaults::defaultBorder(),
                false,
                true,
                XLSXFill::solid()->withForegroundColor(XLSXColor::newRtkLightGray())
            )
        );

        /** Создание нового листа с указанием ширин колонок */
        $dataSheet = $writer->newSheet('Первый лист', [ 10, 14, 50, 10, 50 ]);
        $dataSheet->addColumnGroup(1, 5);
        $dataSheet->addColumnGroup(2, 4);

        $titleRow = $dataSheet->newRow();
        $titleRow->addInlineString('Главный заголовок', $mainTitleLeftStyle);
        $titleRow->addEmptyCells(3, $mainTitleMiddleStyle);
        $titleRow->addEmptyCells(1, $mainTitleRightStyle);
        $dataSheet->addMergedCellRectangle(XLSXRectangle::fromTopLeftIdAndWidthHeight(0, 0, 5, 1));

        $subTitleRow = $dataSheet->newRow();
        $start = $subTitleRow->addInlineString('Сгруппированные данные', $titleStyle);
        $emptyRect = $subTitleRow->addEmptyCells(2);
        $b = $subTitleRow->addInlineString('Подзаголовок', $titleStyle);

        $dataSheet->addMergedCellRectangle(new XLSXRectangle($start, $emptyRect->rightBottom()));
        $dataSheet->addMergedCellRectangle(XLSXRectangle::fromTopLeftIdAndWidthHeight($b->columnId, $b->rowId, 2, 1));

        $max = 10;
        for ($i = 1; $i <= $max; $i++) {
            $row = $dataSheet->newRow();
            $a = $row->addNumber($i, $floatStyle)->asExcelCell();
            $someValue = random_int(-1000, 1000);
            $b = $row->addNumber($someValue, $someValue >= 0 ? $roublePosStyle : $roubleNegStyle)->asExcelCell();
            $row->addInlineString("Координаты ячейки слева - $b");
            $row->addFormula("($a+$b/2)", $euroAutoColorStyle);
            $row->addDate(new DateTimeImmutable(), $dateStyle);
            if ($i === 2) {
                $dataSheet->openRowGroup();
            }

            if ($i === 4) {
                $dataSheet->openRowGroup();
            }

            if ($i === 6) {
                $dataSheet->closeRowGroup();
            }

            if ($i === 8) {
                $dataSheet->closeRowGroup();
            }
        }

        $this->addChartsSheet($writer);
        $dictionaries = $writer->newSheet('Справочники');

        $dictionaryRegion = $dictionaries->newRow()->addStringsArray([ 'Вариант А', 'Вариант Б', 'Вариант В' ]);
        $dataSheet->markRectangleAsDictionary($dictionaryRegion, XLSXRectangle::fromRowAndColIds(6, 1, 6, 2));

        $writer->saveToFile($testFile);
        $this->assertFileExists($testFile);
    }

    private function addChartsSheet(XLSXWriter $writer): void
    {
        $sheet = $writer->newSheet('Графики');

        $chartSpace = $sheet->newChartSpace()
                            ->setLeftTopColRow(1, 1)
                            ->setRightBottomColRow(8, 20)
                            ->setTitle('Выпито пива, л.')
                            ->setMajorGridlinesVisibility(false);
        $chartSpace->newBarChart()
                   ->setOrientationVertical()
                   ->setGroupingStandard()
                   ->setOverlapPercent(33)
                   ->addCategories([ 'Июнь', 'Июль', 'Август' ])
                   ->addValues(
                       'Федей',
                       [ 20, 30, 0.33 ],
                       XLSXColor::newRtkOrange(),
                       XLSXColor::newRtkOrange(),
                       XLSXColor::newWhite(),
                       XLSXColor::newRtkOrange(),
                       XLSXDataLabelPosition::outsideEnd()
                   )
                   ->addValues(
                       'Васей',
                       [ 10, 20, 3.2 ],
                       XLSXColor::newRtkLightGray(),
                       XLSXColor::newRtkLightGray(),
                       XLSXColor::newWhite(),
                       XLSXColor::newRtkLightGray(),
                       XLSXDataLabelPosition::insideBase()
                   );

        $chartSpace->newLineChart()
                   ->setShowMarker(false)
                   ->setSmooth(true)
                   ->addCategories([ 'Июнь', 'Июль', 'Август' ])
                   ->addValues(
                       'Лешей',
                       [ 4, 0.5, 2 ],
                       XLSXColor::newRtkGray(),
                       XLSXColor::newRtkGray(),
                       XLSXColor::newWhite(),
                       XLSXColor::newRtkGray(),
                       XLSXDataLabelPosition::top()
                   );

        /** Явное создание всего */
        $barChart = new XLSXBarChart();
        $barChart = $barChart->setGroupingPercentStacked()
                             ->setOrientationHorizontal();
        $barChart->addCategories([ 'Июнь', 'Июль', 'Август' ])
                 ->addValues('Васей', [ 3, 6, 3 ])
                 ->addValues('Федей', [ 8, 2, 3 ])
                 ->addValues('Лешей', [ 21, 23, 10 ]);

        $chartSpace = $writer->newChartSpace()
                             ->addChart($barChart)
                             ->setTitle('Потрачено денег, тыс. р.');

        $chartSpace->setBounds(XLSXRectangle::fromTopLeftIdAndWidthHeight(10, 1, 8, 20));
        $writer->newDrawing()->addChartSpace($chartSpace);

        $sheet->currentDrawing()->addChartSpace($chartSpace);
    }
}
