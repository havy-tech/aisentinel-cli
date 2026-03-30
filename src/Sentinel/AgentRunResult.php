<?php

declare(strict_types=1);

namespace Phalanx\Sentinel;

final readonly class AgentRunResult
{
    public function __construct(
        public string $name,
        public string $glyph,
        public string $color,
        public string $text,
        public ?\Phalanx\Ai\Message\Conversation $conversation = null,
        public \Phalanx\Ai\Event\TokenUsage $usage = new \Phalanx\Ai\Event\TokenUsage(),
    ) {}
}
