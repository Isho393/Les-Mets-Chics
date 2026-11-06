<?php

namespace Tests\Unit;

use App\Services\ContentModerationService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ContentModerationServiceTest extends TestCase
{
    public static function offensiveTexts(): array
    {
        return [
            'mot interdit simple' => ['Quel idiot'],
            'majuscules' => ['QUEL IDIOT'],
            'leetspeak' => ['quel 1d10t'],
        ];
    }

    #[DataProvider('offensiveTexts')]
    public function test_detects_banned_words(string $text): void
    {
        $this->assertTrue((new ContentModerationService())->containsBannedWords($text));
    }

    public function test_accepts_a_respectful_comment(): void
    {
        $this->assertFalse((new ContentModerationService())->containsBannedWords('Recette délicieuse, merci beaucoup !'));
    }
}
