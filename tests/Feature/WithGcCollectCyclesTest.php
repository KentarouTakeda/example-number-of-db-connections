<?php

namespace Tests\Feature;

class WithGcCollectCyclesTest extends RecordTheNumberOfDBConnectionsTest
{
    public function tearDown(): void
    {
        parent::tearDown();

        gc_collect_cycles();
    }
}

