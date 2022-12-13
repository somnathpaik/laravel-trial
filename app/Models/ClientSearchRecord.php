<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientSearchRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'keyword',
        'client_id',
        'popularity_count',
    ];

    public function client() :BelongsTo{
        return $this->belongsTo(Client::class);
    }
}
