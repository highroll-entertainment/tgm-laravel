<?php

/**
 * The site tag from config:
 * - `<x-tgm-site-tag />` / `@tgmSiteTag`: one async script on the tag host; the ids and page
 *   settings only when configured (the shared host needs them); escaped;
 * - the CSP nonce: the component's `nonce` attribute, else Laravel's Vite nonce;
 * - nothing at all when disabled or without a valid host.
 */

namespace TerraGaming\Ads\Tests;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Vite;

final class SiteTagTest extends TestCase
{
    public function test_one_line_site_tag_on_the_verified_host(): void
    {
        config(['tgm.host' => 'tgmads.example.com']);
        $this->assertSame(
            '<script async src="https://tgmads.example.com/tag.js"></script>',
            trim(Blade::render('<x-tgm-site-tag />'))
        );
        $this->assertSame(
            '<script async src="https://tgmads.example.com/tag.js"></script>',
            trim(Blade::render('@tgmSiteTag'))
        );
    }

    public function test_ids_and_settings_on_the_fallback_host(): void
    {
        config([
            'tgm.host' => 'https://cdn.terramedia-sandbox.com/',
            'tgm.property' => 'PROP-1-1',
            'tgm.publisher' => 'PUB-1',
            'tgm.in_article_unit' => 'TGM-HPO-INART',
            'tgm.article_selector' => 'article .body',
            'tgm.spa' => 'manual',
        ]);
        $this->assertSame(
            '<script async src="https://cdn.terramedia-sandbox.com/tag.js" data-tgm-property="PROP-1-1" data-tgm-publisher="PUB-1" data-tgm-in-article="TGM-HPO-INART" data-tgm-article-selector="article .body" data-tgm-spa="manual"></script>',
            trim(Blade::render('<x-tgm-site-tag />'))
        );
    }

    public function test_the_csp_nonce(): void
    {
        config(['tgm.host' => 'tgmads.example.com']);
        $this->assertStringContainsString(
            'nonce="n-attr"',
            Blade::render('<x-tgm-site-tag nonce="n-attr" />')
        );
        Vite::useCspNonce('n-vite');
        $this->assertStringContainsString('nonce="n-vite"', Blade::render('<x-tgm-site-tag />'));
    }

    public function test_nothing_when_disabled_or_without_a_valid_host(): void
    {
        config(['tgm.host' => 'tgmads.example.com', 'tgm.enabled' => false]);
        $this->assertSame('', trim(Blade::render('<x-tgm-site-tag />')));
        config(['tgm.enabled' => true, 'tgm.host' => '']);
        $this->assertSame('', trim(Blade::render('<x-tgm-site-tag />')));
        config(['tgm.host' => 'evil.com"><script>']);
        $this->assertSame('', trim(Blade::render('<x-tgm-site-tag />')));
    }

    public function test_invalid_ids_are_left_out(): void
    {
        config(['tgm.host' => 'tgmads.example.com', 'tgm.property' => '"><b>', 'tgm.spa' => 'often']);
        $this->assertSame(
            '<script async src="https://tgmads.example.com/tag.js"></script>',
            trim(Blade::render('<x-tgm-site-tag />'))
        );
    }
}
