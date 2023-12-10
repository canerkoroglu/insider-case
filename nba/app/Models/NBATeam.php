<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NBATeam extends Model
{
    use HasFactory;

    protected $table = "teams";
    protected $fillable = [
        'name',
        'short_name',
        'conference',
        'team_group',
    ];

    public function matches(): HasMany
    {
        return $this->hasMany(NBAMatch::class, 'home_team_id')
            ->orWhere('away_team_id', $this->id);
    }

    public function players(): HasMany
    {
        return $this->hasMany(NBAPlayer::class);
    }

    public function shoots(): HasMany
    {
        return $this->hasMany(NBAShoot::class);
    }
}
