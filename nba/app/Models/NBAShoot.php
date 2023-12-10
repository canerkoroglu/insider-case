<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NBAShoot extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "shoots";

    protected $fillable = [
        'match_id',
        'team_id',
        'player_id',
        'assisted_by',
        'success',
        'point',
        'quarter'
    ];

    protected $casts = [
        'success' => 'boolean',
        'point' => 'integer',
        'quarter' => 'integer'
    ];

    public function match(): BelongsTo
    {
        return $this->belongsTo(NBAMatch::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(NBATeam::class);
    }

    public function playerId(): HasMany
    {
        return $this->hasMany(NBAShoot::class, 'player_id');
    }

    public function assistedBy(): HasMany
    {
        return $this->hasMany(NBAShoot::class, 'assisted_by');
    }

}
