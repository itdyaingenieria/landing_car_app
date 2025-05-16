<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Winner extends Model
{
    use HasFactory;

    protected $fillable = ['participant_id'];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }
}
