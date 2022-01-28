<?php
declare(strict_types=1);

namespace YAXLSX\Chart;

use YAXLSX\Core\XLSXStreamFile;
use function count;

final class XLSXChartSpaceManager
{
    /** @var array<int, XLSXChartSpace> */
    public array $chartSpaces;

    /** @var array<int,XLSXStreamFile> */
    private array $temporaryFiles;

    public function __construct()
    {
        $this->chartSpaces = [];
        $this->temporaryFiles = [];
    }

    public function __destruct()
    {
        $this->deleteFiles();
    }

    private function deleteFiles(): void
    {
        foreach ($this->temporaryFiles as $file) {
            $file->delete();
        }

        $this->temporaryFiles = [];
    }

    public function fromChartSpace(XLSXChartSpace $chartSpace): XLSXChartSpace
    {
        $newIndex = $this->newIndex();
        if ($chartSpace->externalIndex() !== $newIndex) {
            return $chartSpace->attachToManager($this);
        }

        $this->chartSpaces[ $newIndex ] = $chartSpace;

        return $chartSpace;
    }

    public function newIndex(): int
    {
        return count($this->chartSpaces) + 1;
    }

    /** @return array<int,string> */
    public function save(string $directory): array
    {
        $indexToFileName = [];
        foreach ($this->chartSpaces as $chartSpace) {
            $indexToFileName[ $chartSpace->externalIndex() ] = $this->saveChartSpace($directory, $chartSpace)
                                                                    ->fileName();
        }

        return $indexToFileName;
    }

    private function saveChartSpace(string $directory, XLSXChartSpace $chartSpace): XLSXStreamFile
    {
        $file = XLSXStreamFile::fromStringWithTempName(
            $directory,
            'xlsx_chart_' . $chartSpace->externalIndex() . '_',
            $chartSpace->asXml()
        );

        $this->temporaryFiles[] = $file;

        return $file;
    }

    public function isEmpty(): bool
    {
        return count($this->chartSpaces) === 0;
    }

    public function newChartSpace(): XLSXChartSpace
    {
        $chartSpace = new XLSXChartSpace();
        $chartSpace->attachToManager($this);

        return $chartSpace;
    }
}
