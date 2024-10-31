<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    public function scopePick(Builder $query, ?string $keyword, string $column = 'id', string $operator = '='): void
    {
        $query->where($column, $operator, $keyword);
    }

    public function scopeFilterWhere(Builder $query, string $column, ?string $keyword, string $operator = '='): void
    {
        if ($keyword != "") {
            $query->where($column, $operator, $keyword);
        }
    }

    public function scopeOrFilterWhere(Builder $query, string $column, ?string $keyword, string $operator = '='): void
    {
        if ($keyword != "") {
            $query->orWhere($column, $operator, $keyword);
        }
    }

    public function scopeFilterLike(Builder $query, string $column, ?string $keyword): void
    {
        if ($keyword != "") {
            $query->where(DB::raw("LOWER($column)"), 'like', "%".strtolower($keyword)."%");
        }
    }

    public function scopeOrFilterLike(Builder $query, string $column, ?string $keyword): void
    {
        if ($keyword != "") {
            $query->orWhere(DB::raw("LOWER($column)"), 'like', "%".strtolower($keyword)."%");
        }
    }

    public function scopeActive(Builder $query): void
    {
        $query->where($this->getTable() . '.status', 'active');
    }

    public function scopeFilterBetween(Builder $query, string $column, string $start, string $end): void
    {
        if (($start != "") AND ($end != "")) {
            $query->whereBetween($column, [$start, $end]);
        }
    }

    public function scopeOrFilterBetween(Builder $query, string $column, string $start, string $end): void
    {
        if (($start != "") AND ($end != "")) {
            $query->OrWhereBetween($column, [$start, $end]);
        }
    }

    public function scopeFilterWhereHas($query, $relation, $related): void
    {
        if ( ! empty($related->id) )
        {
            $query->whereHas(
                $relation,
                function ($query) use ($related) {
                    $query->where('id', '=', $related->id);
                }
            );
        }
    }

    public function scopeOrFilterWhereHas($query, $relation, $related): void
    {
        if ( ! empty($related->id) )
        {
            $query->OrWhereHas(
                $relation,
                function ($query) use ($related) {
                    $query->where('id', '=', $related->id);
                }
            );
        }
    }
}
