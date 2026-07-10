<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Contest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'starts_at',
        'ends_at',
        'is_active',
        'created_by',
        'is_approved',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_approved' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    /**
     * Get the user who created the contest (Judge/ProblemSetter).
     */
    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the problems associated with the contest, ordered by their pivot label (A, B, C...).
     */
    public function problems(): BelongsToMany
    {
        return $this->belongsToMany(Problem::class, 'contest_problems')
            ->withPivot('label')
            ->orderByPivot('label');
    }

    /**
     * Get the users participating in the contest as participants.
     */
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'contest_participants')
            ->withPivot('joined_at')
            ->as('participant');
    }
}
