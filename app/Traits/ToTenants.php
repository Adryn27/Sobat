<?php

namespace App\Traits;

use App\Models\Tenants;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

trait ToTenants
{
    protected static function bootToTenants(): void
    {
        static::addGlobalScope('tenant_id', function (Builder $builder) {
            if (Auth::check() && Auth::user()->tenant_id) {
                $builder->where(
                    $builder->getModel()->getTable() . '.tenant_id',
                    Auth::user()->tenant_id
                );
            }
        });

        static::creating(function (Model $model) {
            if (Auth::check() && Auth::user()->tenant_id && !$model->tenant_id) {
                $model->tenant_id = Auth::user()->tenant_id;
            }
        });
    }

    public static function uniqueRule(string $column, $ignoreId = null)
    {
        $user = Auth::user();
        
        $rule = Rule::unique((new static())->getTable(), $column)
            ->where(function ($query) use ($user) {
                return $query->where('tenant_id', $user->tenant_id);
            });

        if ($ignoreId) {
            $rule->ignore($ignoreId);
        }

        return $rule;
    }

    public function tenant()
    {
        return $this->belongsTo(Tenants::class, 'tenant_id');
    }
}
