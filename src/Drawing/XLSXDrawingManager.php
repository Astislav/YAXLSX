<?php
declare(strict_types=1);

namespace YAXLSX\Drawing;

use YAXLSX\Core\XLSXStreamFile;
use function count;

final class XLSXDrawingManager
{
    /** @var XLSXDrawing[] */
    private array $drawings;

    /** @var XLSXStreamFile[] */
    private array $temporaryFiles;

    public function __construct()
    {
        $this->drawings = [];
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

    public function fromDrawing(XLSXDrawing $drawing): XLSXDrawing
    {
        $newIndex = $this->newIndex();
        if ($drawing->index() !== $newIndex) {
            return $drawing->attachToManager($this);
        }

        $this->drawings[ $newIndex ] = $drawing;

        return $drawing;
    }

    private function saveDrawing(string $directory, XLSXDrawing $drawing): XLSXStreamFile
    {
        $file = XLSXStreamFile::fromStringWithTempName(
            $directory,
            'xlsx_drawing_' . $drawing->index() . '_',
            $drawing->asXml()
        );

        $this->temporaryFiles[] = $file;

        return $file;
    }

    private function saveRels(string $directory, XLSXDrawing $drawing): XLSXStreamFile
    {
        $file = XLSXStreamFile::fromStringWithTempName(
            $directory,
            'xlsx_drawings_rel_' . $drawing->index() . '_',
            $drawing->asRelsXml()
        );

        $this->temporaryFiles[] = $file;

        return $file;
    }

    /** @return array<int,string> */
    public function saveDrawings(string $directory): array
    {
        $indexToFileName = [];
        foreach ($this->drawings as $drawing) {
            $indexToFileName[ $drawing->index() ] = $this->saveDrawing($directory, $drawing)->fileName();
        }

        return $indexToFileName;
    }

    /** @return array<int,string> */
    public function saveRelations(string $directory): array
    {
        $indexToFileName = [];
        foreach ($this->drawings as $drawing) {
            $indexToFileName[ $drawing->index() ] = $this->saveRels($directory, $drawing)->fileName();
        }

        return $indexToFileName;
    }

    public function isEmpty(): bool
    {
        return count($this->drawings) === 0;
    }

    public function newIndex(): int
    {
        return count($this->drawings) + 1;
    }

    /** @return XLSXDrawing[] */
    public function drawings(): array
    {
        return $this->drawings;
    }

    public function newDrawing(): XLSXDrawing
    {
        $drawing = new XLSXDrawing();
        $drawing->attachToManager($this);

        return $drawing;
    }
}
