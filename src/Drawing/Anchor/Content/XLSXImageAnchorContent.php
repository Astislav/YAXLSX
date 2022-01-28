<?php
declare(strict_types=1);

namespace YAXLSX\Drawing\Anchor\Content;

use YAXLSX\Core\XLSXTools;

final class XLSXImageAnchorContent implements XLSXAnchorContent
{
    private string $fileName;

    private int $relationId;

    private string $name;

    public function __construct(int $relationId, string $name, string $fileName)
    {
        $this->relationId = $relationId;
        $this->name = $name;
        $this->fileName = $fileName;
    }

    public function asXml(): string
    {
        return /** @lang XML */
            '<xdr:pic>' .
            '<xdr:nvPicPr>' .
            '<xdr:cNvPr id="' . $this->relationId() . '" name="' . $this->nameXml() . '"/>' .
            '<xdr:cNvPicPr>' .
            '<a:picLocks noChangeAspect="1"/>' .
            '</xdr:cNvPicPr>' .
            '</xdr:nvPicPr>' .
            '<xdr:blipFill>' .
            '<a:blip xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" ' .
            'r:embed="rId' . $this->relationId() . '"/>' .
            '<a:stretch>' .
            '<a:fillRect/>' .
            '</a:stretch>' .
            '</xdr:blipFill>' .
            '<xdr:spPr>' .
            '<a:xfrm>' .
            '<a:off x="0" y="0"/>' .
            '<a:ext cx="0" cy="0"/>' .
            '</a:xfrm>' .
            '<a:prstGeom prst="rect">' .
            '<a:avLst/>' .
            '</a:prstGeom>' .
            '</xdr:spPr>' .
            '</xdr:pic>';
    }

    public function relationId(): int
    {
        return $this->relationId;
    }

    public function nameXml(): string
    {
        return XLSXTools::filterChars($this->name);
    }

    public function fileName(): string
    {
        return $this->fileName;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function relXml(): string
    {
        return /** @lang XML */
            '<Relationship ' .
            'Id="rId' . $this->relationId . '" ' .
            'Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" ' .
            'Target="../media/' . $this->fileName . '"/>';
    }
}
