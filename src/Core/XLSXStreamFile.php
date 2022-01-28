<?php
declare(strict_types=1);

namespace YAXLSX\Core;


final class XLSXStreamFile
{
    /** @var resource */
    private $fileHandle;

    private string $fileName;

    private bool $opened;

    private string $buffer;

    private int $bufferThreshold;

    public function __construct(string $fileName, int $bufferThreshold = 4096)
    {
        $this->fileName = $fileName;
        $this->bufferThreshold = $bufferThreshold;
        $this->buffer = '';
        $this->opened = false;
    }

    public static function tempFile(string $directory, string $prefix): self
    {
        $temporaryFile = tempnam($directory, $prefix);
        if (!$temporaryFile) {
            throw new LogicException('Failed to create temporary file');
        }

        return new self($temporaryFile);
    }

    public static function fromStringWithTempName(string $directory, string $prefix, string $fileContent): self
    {
        $file = self::tempFile($directory, $prefix);
        $file->open();
        $file->writeString($fileContent);
        $file->close();

        return $file;
    }

    public function __destruct()
    {
        $this->close();
    }

    public function open(): void
    {
        $this->close();

        $this->buffer = '';

        $fileHandle = fopen($this->fileName, 'wb+');

        if (!$fileHandle) {
            throw new LogicException("File $this->fileName was not opened");
        }

        $this->fileHandle = $fileHandle;

        $this->opened = true;
    }

    public function writeString(string $buffer): void
    {
        if (!$this->opened()) {
            $this->open();
        }

        $this->buffer .= $buffer;

        if (mb_strlen($this->buffer) <= $this->bufferThreshold) {
            return;
        }

        $this->writeBuffer();
    }

    private function writeBuffer(): void
    {
        fwrite($this->fileHandle, $this->buffer);
        $this->buffer = '';
    }

    public function close(): void
    {
        if (!$this->opened) {
            return;
        }

        $this->writeBuffer();

        fclose($this->fileHandle);
        $this->opened = false;
    }

    public function delete(): void
    {
        $this->close();
        if (file_exists($this->fileName)) {
            unlink($this->fileName);
        }
    }

    public function fileName(): string
    {
        return $this->fileName;
    }

    public function opened(): bool
    {
        return $this->opened;
    }
}
