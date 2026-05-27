<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToHospital
{
    /**
     * Boot the trait and add global scope to filter by hospital_id
     * from the authenticated user (if user has hospital_id).
     */
    protected static function bootBelongsToHospital()
    {
        static::addGlobalScope('hospital', function (Builder $builder) {
            $user = auth()->user();
            if ($user && $user->hospital_id) {
                // For models that have direct hospital_id column (like Patient)
                if (method_exists($builder->getModel(), 'getHospitalIdColumn')) {
                    $builder->where($builder->getModel()->getHospitalIdColumn(), $user->hospital_id);
                } 
                // For models that relate to User (Doctor, Staff, Appointment via doctor, etc.)
                // We'll handle differently in each model's scope.
                // But this trait will be used mainly for models that have a direct hospital_id column.
                // For others, we will override the scope in the model itself.
            }
        });
    }
}