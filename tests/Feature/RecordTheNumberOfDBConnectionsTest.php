<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class RecordTheNumberOfDBConnectionsTest extends TestCase
{
    /**
     * Count and record the number of connections during each test.
     */
    static $numberOfConnectionsAtEachTest = [];

    /**
     * Display the number of connections at the end of the test.
     */
    public static function tearDownAfterClass(): void
    {
        $max = max(...static::$numberOfConnectionsAtEachTest);

        echo PHP_EOL;

        foreach (static::$numberOfConnectionsAtEachTest as $i => $numberOfConnection) {
            echo str_repeat('▒', $numberOfConnection);
            echo str_repeat('░', $max - $numberOfConnection);
            echo PHP_EOL;
        }

        static::$numberOfConnectionsAtEachTest = [];
    }

    public static function repeat()
    {
        return array_fill(0, 200, []);
    }

    #[DataProvider('repeat')]
    public function test()
    {
        $numberOfConnection = DB::selectOne(
            // Obtain the number of connections from the system catalog.
            'select count(*) from pg_stat_activity where datname is not null'
        )->count;

        $this->assertIsInt($numberOfConnection);

        static::$numberOfConnectionsAtEachTest[] = $numberOfConnection;
    }
}
