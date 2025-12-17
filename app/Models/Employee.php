<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Employee extends Model
{
    //
    protected $connection = 'mongodb';
    protected $fillable = [
        'fname',
        'lname',
        'position',
        'dob',
        'emp_created',
        'file_id'

    ];
}
