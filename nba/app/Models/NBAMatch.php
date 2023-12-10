<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class NBAMatch extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "matches";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'started_at',
        'home_team_id',
        'away_team_id',
        'home_team_score',
        'away_team_score',
        'status',
    ];


    protected $casts = [
        'started_at' => 'datetime',
    ];

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(NBATeam::class, 'home_team_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(NBATeam::class, 'away_team_id');
    }
}
