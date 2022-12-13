<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'is_active',
        'popularity_count',
    ];

    protected $appends = [
        'is_active_text'
    ];

    public function getIsActiveTextAttribute(): string{
        return $this->is_active == 1 ? 'Approved' : 'Not approved';
    }

    public function clientContacts() :HasMany{
        return $this->hasMany(ClientContact::class);
    }

    public function clientContact() :HasOne{
        return $this->hasOne(ClientContact::class);
    }

    public function clientSearchRecords() :hasMany{
        return $this->hasMany(ClientSearchRecord::class);
    }
}
