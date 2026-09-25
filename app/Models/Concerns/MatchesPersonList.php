<?php

namespace App\Models\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait MatchesPersonList
{
    public function scopeForDosen(Builder $query, User $user): Builder
    {
        $column = $this->personListColumn;
        $name = trim(preg_replace('/\s+/u', ' ', $user->fullname));

        return $query->where(function (Builder $query) use ($user, $column, $name) {
            $query->where('user_id', $user->id);

            if ($name === '') {
                return;
            }

            $query->orWhere($column, $name)
                ->orWhere($column, 'like', $name.',%')
                ->orWhere($column, 'like', '%,'.$name)
                ->orWhere($column, 'like', '%, '.$name)
                ->orWhere($column, 'like', '%,'.$name.',%')
                ->orWhere($column, 'like', '%, '.$name.',%');
        });
    }

    public function isOwnedBy(User $user): bool
    {
        return (int) $this->user_id === (int) $user->id;
    }
}