<?php

namespace Tests\Feature;

use App\Helpers\DateTimeHelper;
use Carbon\Carbon;
use Tests\TestCase;

class DateTimeHelperTest extends TestCase
{
    /**
     * Test willExpireAt function.
     */
    public function testWillExpireAt()
    {
        $due_time = '2023-03-08 12:00:00';
        $created_at = '2023-03-07 12:00:00';

        $expected = '2023-03-08 12:00:00';
        $actual = DateTimeHelper::willExpireAt($due_time, $created_at);
        $this->assertEquals($expected, $actual);

        $due_time = '2023-03-08 12:00:00';
        $created_at = '2023-03-07 12:30:00';

        $expected = '2023-03-07 13:20:00';
        $actual = DateTimeHelper::willExpireAt($due_time, $created_at);
        $this->assertEquals($expected, $actual);

        $due_time = '2023-03-08 12:00:00';
        $created_at = '2023-03-07 16:00:00';

        $expected = '2023-03-07 22:00:00';
        $actual = DateTimeHelper::willExpireAt($due_time, $created_at);
        $this->assertEquals($expected, $actual);
    }
}
