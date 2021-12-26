<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Investor extends Model
{
    use HasFactory;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($investor) {
            if (Auth::user()->id != 3) {
                $investor->user_id = Auth::user()->id;
            }
        });
    }

    public function scopeCurrentUser($query)
    {
        if (Auth::user()->id != 3) {
            return $query->where('user_id', Auth::user()->id);
        } else {
            return $query;
        }

    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function summary()
    {
        return $this->hasOne(InvestorSummary::class)->where('active', 1);
    }
}
