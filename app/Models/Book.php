<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'language',
        'genre',
        'pages',
        'release_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'release_date' => 'date',
    ];

    /**
     * The artists that belong to the book.
     */
    public function artists(): BelongsToMany
    {
        return $this
            ->belongsToMany(Artist::class, ArtistBook::class)
            ->withPivot('role', 'notes')
            ->withTimestamps()
            ->as('project');
    }

    /**
     * The reviews that belong to the book.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
