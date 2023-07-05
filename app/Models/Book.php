<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
        'reviews_avg_rating' => 'float',
        'reviews_count' => 'integer',
    ];

    /**
     * The artists that belong to the book.
     */
    public function artists(): BelongsToMany
    {
        return $this
            ->belongsToMany(Artist::class, ArtistBook::class)
            ->withPivot('role', 'notes')
            ->orderByPivot('role')
            ->as('project');
    }

    /**
     * The authors that belong to the book.
     */
    public function authors(): BelongsToMany
    {
        return $this
            ->belongsToMany(Artist::class, ArtistBook::class)
            ->wherePivot('role', 'author')
            ->as('authors');
    }

    /**
     * The reviews that belong to the book.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * The chapters that belong to the book.
     */
    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    /**
     * Scope a query to only include published books.
     */
    public function scopeOnlyPublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }
}
