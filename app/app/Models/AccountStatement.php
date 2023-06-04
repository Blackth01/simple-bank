<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountStatement extends Model
{
    protected $table = 'transaction';
    public $timestamps = false;

    protected $fillable = [
        'value',
        'account_id',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    use HasFactory;
}
