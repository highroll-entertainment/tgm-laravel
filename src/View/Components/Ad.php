<?php

namespace TerraGaming\Ads\View\Components;

use Illuminate\View\Component;
use TerraGaming\Ads\Tags;

/** `<x-tgm-ad unit="TGM-…" />`: one ad unit placement (a unit may repeat on a page). */
class Ad extends Component
{
    public function __construct(public ?string $unit = null, public ?string $slot = null) {}

    public function render(): \Closure
    {
        return function (array $data): string {
            $attributes = $data['attributes'];

            return app(Tags::class)->ad($this->unit, $this->slot, $attributes->get('class'));
        };
    }
}
