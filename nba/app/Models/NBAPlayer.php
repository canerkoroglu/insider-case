<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NBAPlayer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "players";

    protected $fillable = [
        'name',
        'position',
        'jersey_number',
        'team_id',
    ];

    protected $casts = [
        'position' => 'integer',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(NBATeam::class);
    }
}
