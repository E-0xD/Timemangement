<?php

namespace App\Enums;

enum FileType: string
{
    case Pdf   = 'pdf';
    case Docx  = 'docx';
    case Image = 'image';
    case Pptx  = 'pptx';
    case Txt   = 'txt';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Pdf   => 'PDF',
            self::Docx  => 'Word Document',
            self::Image => 'Image',
            self::Pptx  => 'PowerPoint',
            self::Txt   => 'Text File',
            self::Other => 'Other',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Pdf   => 'document',
            self::Docx  => 'document-text',
            self::Image => 'photo',
            self::Pptx  => 'presentation-chart-bar',
            self::Txt   => 'document-text',
            self::Other => 'paper-clip',
        };
    }

    public static function fromMime(string $mimeType): self
    {
        return match (true) {
            str_contains($mimeType, 'pdf')        => self::Pdf,
            str_contains($mimeType, 'word')       => self::Docx,
            str_contains($mimeType, 'image')      => self::Image,
            str_contains($mimeType, 'powerpoint') => self::Pptx,
            str_contains($mimeType, 'text/plain') => self::Txt,
            default                                => self::Other,
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
