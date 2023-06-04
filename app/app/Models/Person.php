<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'person';
    public $timestamps = false;

    protected $fillable = [
        'name', 
        'cpf',
        'address',
    ];

    public function accounts()
    {
        return $this->hasMany(Account::class, 'person_id');
    }

    use HasFactory;
}
