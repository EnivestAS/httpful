<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector;
use Rector\Config\RectorConfig;
use Rector\Php84\Rector\Param\ExplicitNullableParamTypeRector;
use Rector\Removing\Rector\FuncCall\RemoveFuncCallRector;
use Rector\ValueObject\PhpVersion;

/**
 * Single-rule mode: one active rule in `withRules([...])`. Comment finished passes in place (FQCN) for history.
 *
 * Run:
 *   ./vendor/bin/rector --dry-run
 *   ./vendor/bin/rector
 */
return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withPhpVersion(PhpVersion::PHP_85)
    ->withRules([
        ExplicitNullableParamTypeRector::class,
        CompleteDynamicPropertiesRector::class,
    ])
    ->withConfiguredRule(RemoveFuncCallRector::class, [
        'curl_close',
        'curl_share_close',
        'finfo_close',
        'imagedestroy',
        'xml_parser_free',
    ])
    ->withParallel(300, 8);
