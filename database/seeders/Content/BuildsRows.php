<?php

declare(strict_types=1);

namespace Database\Seeders\Content;

use Illuminate\Database\Eloquent\Model;

trait BuildsRows
{
    /**
     * Turn attribute sets into storable rows by passing each through an unsaved model, so that
     * casts apply exactly as they would on an ordinary save.
     *
     * @param  class-string<Model>  $model
     * @param  list<array<string, mixed>>  $attributeSets
     * @return list<array<string, mixed>>
     */
    private function rowsFor(string $model, array $attributeSets): array
    {
        return array_map(
            fn (array $attributes): array => (new $model)->forceFill($attributes)->getAttributes(),
            $attributeSets,
        );
    }
}
