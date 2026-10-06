<?php

namespace TerraGaming\Ads\View\Components;

use Illuminate\View\Component;
use TerraGaming\Ads\Tags;

/** `<x-tgm-site-tag />`: the site tag, once in your layout's `<head>`. */
class SiteTag extends Component
{
    public function __construct(public ?string $nonce = null) {}

    public function render(): \Closure
    {
        return fn (): string => app(Tags::class)->siteTag($this->nonce);
    }
}
