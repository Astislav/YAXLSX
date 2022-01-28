<?php
declare(strict_types=1);

namespace YAXLSX\HashCollection;

interface XLSXHashable
{
    public function asHash(): string;

    public function asXml(int $index): string;
}
