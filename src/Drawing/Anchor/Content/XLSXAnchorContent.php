<?php
declare(strict_types=1);

namespace YAXLSX\Drawing\Anchor\Content;

interface XLSXAnchorContent
{
    public function asXml(): string;

    public function relXml(): string;

    public function name(): string;

    public function relationId(): int;
}
