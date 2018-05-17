<?php

namespace Tests\Unit\Interactions\Records;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Interactions\Records\CreateRecord;
use App\Models\Category;


class CreateRecordTest extends TestCase
{
    /** @test */
    public function create_record_success()
    {
        //Arrange
        $record = [
            'type'      => 'Outcome',
            'date'      => now(),
            'description' => 'Compramos pizza',
            'value'   => 105,
            'category_id' => factory(Category::class)->create()->id
        ];

        //Act
        $outcome = CreateRecord::run($record);

        //Assert
        $this->assertTrue($outcome->valid);
        $this->assertDatabaseHas('records', $outcome->result->toArray());
    }


	/** @test */
    public function it_is_invalid_when_required_fields_are_missing()
    {
        // Act
        $outcome = CreateRecord::run([]);

        $this->assertFalse($outcome->valid);
        $this->assertArraySubset([
            'type' => ['The type field is required.'],
            'description' => ['The description field is required.'],
            'value' => ['The value field is required.'],
            'date' => ['The date field is required.'],
        ], $outcome->errors->toArray());
    }
}
