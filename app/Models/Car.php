<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function isAvailable($startDate, $endDate): bool
    {
        return ! $this->bookings()
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
            ->where('status', '!=', 'cancelled')
            ->exists();
    }

    public static function getAvailableCars($startDate, $endDate, $user = null)
    {

        $query = self::whereDoesntHave('bookings', function ($query) use ($startDate, $endDate) {
            $query->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
                ->where('status', '!=', 'cancelled');
        });

        if ($user && $user->position_id >= 0) {
            $query->whereHas('category', function ($q) use ($user) {
                $q->whereHas('jobPositions', function ($q) use ($user) {
                    $q->where('job_positions.id', $user->position_id);
                });
            });
        }

        return $query->with('category');
    }
}
