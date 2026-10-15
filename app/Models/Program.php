<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Database\Factories\ProgramFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['slug', 'icon', 'title', 'summary', 'description', 'overview', 'image', 'focus_areas', 'wings', 'sort_order', 'is_published'])]
class Program extends Model
{
    /** @use HasFactory<ProgramFactory> */
    use HasFactory, HasTranslations;

    /**
     * Icon keys available for programs, mapped to their admin labels.
     *
     * @var array<string, string>
     */
    public const array ICONS = [
        'education' => 'Education (book)',
        'rehabilitation' => 'Rehabilitation (hands)',
        'women' => 'Women (flower)',
        'agriculture' => 'Agriculture (tree)',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'title' => 'array',
            'summary' => 'array',
            'description' => 'array',
            'overview' => 'array',
            'focus_areas' => 'array',
            'wings' => 'array',
            'is_published' => 'boolean',
        ];
    }

    /**
     * @param  Builder<self>  $query
     */
    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true)->orderBy('sort_order')->orderBy('id');
    }
}
