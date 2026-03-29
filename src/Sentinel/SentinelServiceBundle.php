<?php

declare(strict_types=1);

namespace Phalanx\Sentinel;

use Phalanx\Ai\Provider\ProviderConfig;
use Phalanx\Sentinel\Render\ConsoleRenderer;
use Phalanx\Service\ServiceBundle;
use Phalanx\Service\Services;

final class SentinelServiceBundle implements ServiceBundle
{
    public function services(Services $services, array $context): void
    {
        $services->singleton(ProviderConfig::class)
            ->factory(static function () use ($context): ProviderConfig {
                $config = ProviderConfig::create();

                $anthropicKey = $context['ANTHROPIC_API_KEY'] ?? null;
                if ($anthropicKey !== null) {
                    $model = $context['ANTHROPIC_MODEL'] ?? 'claude-haiku-4-5-20251001';
                    $config->anthropic(apiKey: $anthropicKey, model: $model);
                }

                $openaiKey = $context['OPENAI_API_KEY'] ?? null;
                if ($openaiKey !== null) {
                    $config->openai(apiKey: $openaiKey);
                }

                return $config;
            });

        $services->config(SentinelConfig::class, static function (array $ctx): SentinelConfig {
            return new SentinelConfig(
                projectRoot: rtrim($ctx['SENTINEL_PROJECT_ROOT'] ?? getcwd(), '/'),
                dossierDir: rtrim($ctx['SENTINEL_DOSSIER_DIR'] ?? dirname(__DIR__, 2) . '/personas', '/'),
                debounce: (float) ($ctx['SENTINEL_DEBOUNCE'] ?? 0.5),
            );
        });

        $services->singleton(ConsoleRenderer::class);
    }
}
