<?php

/**
 * Ad unit placements: `<x-tgm-ad unit="…" />` and
 * `@tgmAd('…')` render one placement div (no loader, no id, so a unit can repeat), the slot type
 * optional; an invalid unit id renders nothing.
 */

namespace TerraGaming\Ads\Tests;

use Illuminate\Support\Facades\Blade;

final class AdTest extends TestCase
{
    public function test_placement(): void
    {
        $this->assertSame(
            '<div class="tgm-ad" data-tgm-unit="TGM-HPO-SBR01"></div>',
            trim(Blade::render('<x-tgm-ad unit="TGM-HPO-SBR01" />'))
        );
        $this->assertSame(
            '<div class="tgm-ad" data-tgm-unit="TGM-HPO-SBR01"></div>',
            trim(Blade::render("@tgmAd('TGM-HPO-SBR01')"))
        );
    }

    public function test_floating_and_classes(): void
    {
        $this->assertSame(
            '<div class="tgm-ad sticky" data-tgm-unit="TGM-HPO-FLT01" data-tgm-slot="floating"></div>',
            trim(Blade::render('<x-tgm-ad unit="tgm-hpo-flt01" slot="floating" class="sticky" />'))
        );
    }

    public function test_invalid_unit_renders_nothing(): void
    {
        $this->assertSame('', trim(Blade::render('<x-tgm-ad unit="&quot;&gt;&lt;script&gt;" />')));
        $this->assertSame('', trim(Blade::render("@tgmAd('nope')")));
    }

    public function test_a_unit_from_a_variable(): void
    {
        $this->assertSame(
            '<div class="tgm-ad" data-tgm-unit="TGM-HPO-LDR01"></div>',
            trim(Blade::render('<x-tgm-ad :unit="$id" />', ['id' => 'TGM-HPO-LDR01']))
        );
    }
}
