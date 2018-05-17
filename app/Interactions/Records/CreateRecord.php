<?php

namespace App\Interactions\Records;

use ZachFlower\EloquentInteractions\Interaction;
use App\Models\Record;

class CreateRecord extends Interaction
{
    public $validations = [
        'type' => 'required',
        'description' => 'required',
        'value' => 'required',
        'date' => 'required',
    ];

    public function execute()
    {
        return Record::create($this->params);
    }
}
