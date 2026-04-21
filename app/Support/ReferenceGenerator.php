<?php

namespace App\Support;

use Illuminate\Support\Str;

class ReferenceGenerator
{
    public function booking(): string
    {
        return $this->generate('BKG');
    }

    public function payment(): string
    {
        return $this->generate('PAY');
    }

    private function generate(string $prefix): string
    {
        return sprintf(
            '%s-%s-%s',
            $prefix,
            now()->format('Ymd'),
            Str::upper(Str::random(10)),
        );
    }
}
