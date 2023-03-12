<?php

use Illuminate\Support\Collection;

if (! Collection::hasMacro('replaceByKey')) {
    Collection::macro('replaceByKey', function (string $key, callable $fn): Collection {
        /** @var Collection $this */
        $value = $this->get($key);

        return $this->replace([
            $key => $fn($value),
        ]);
    });
}
