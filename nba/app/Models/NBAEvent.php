<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NBAEvent extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "events";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'action',
        'description',
        'match_id',
        'team_id',
        'player_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
