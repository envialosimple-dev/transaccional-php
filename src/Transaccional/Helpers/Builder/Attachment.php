<?php

namespace EnvialoSimple\Transaccional\Helpers\Builder;

use JsonSerializable;

class Attachment implements JsonSerializable
{
    public const DISPOSITION_INLINE = 'inline';
    public const DISPOSITION_ATTACHMENT = 'attachment';

    protected string $content;
    protected string $filename;
    protected string $disposition;
    protected ?string $id = null;

    public function __construct(
        string $content = null,
        string $filename = null,
        string $disposition = self::DISPOSITION_ATTACHMENT,
        string $id = null
    ) {
        if ($content) {
            $this->setContent($content);
        }

        if ($filename) {
            $this->setFilename($filename);
        }

        if ($disposition) {
            $this->setDisposition($disposition);
        }

        if ($id) {
            $this->setId($id);
        }
    }

    public function setContent(string $content): void
    {
        if (!$this->isBase64($content)) {
            $this->content = base64_encode($content);
        } else {
            $this->content = $content;
        }
    }

    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    public function setDisposition(?string $disposition): void
    {
        $this->disposition = $disposition;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'filename' => $this->filename,
            'disposition' => $this->disposition,
            'content' => $this->content,
        ];
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    protected function isBase64($string): bool
    {
        $decoded_data = base64_decode($string, true);
        $encoded_data = base64_encode($decoded_data);

        if ($encoded_data !== $string) {
            return false;
        }

        return true;
    }
}
