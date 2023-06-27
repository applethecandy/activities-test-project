<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class Activity extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['meta'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'deleted_at',
    ];

    /**
     * Get the user associated with the activity.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the meta attribute.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function meta(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => (Auth::id() == $this->user_id ? Crypt::decryptString($value) : $value),
            set: fn (string $value) => Crypt::encryptString($value),
        );
    }
}