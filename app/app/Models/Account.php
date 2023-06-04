<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'account';
    public $timestamps = false;

    protected $fillable = [
        'number', 
        'balance',
        'person_id',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function statements()
    {
        return $this->hasMany(AccountStatement::class, 'account_id');
    }

    use HasFactory;
}
