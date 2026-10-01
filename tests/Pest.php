<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

/**
 * Count the queries a callback sends to the database.
 */
function queryCount(Closure $callback): int
{
    DB::flushQueryLog();
    DB::enableQueryLog();

    $callback();

    $count = count(DB::getQueryLog());
    DB::disableQueryLog();

    return $count;
}

/**
 * Read a row as plain columns, without the keys that necessarily differ between two rows.
 *
 * @param  list<string>  $ignoredColumns
 * @return array<string, mixed>
 */
function rowColumns(string $table, int $id, array $ignoredColumns = ['id', 'slug']): array
{
    $row = (array) DB::table($table)->where('id', $id)->first();

    return array_diff_key($row, array_flip($ignoredColumns));
}
