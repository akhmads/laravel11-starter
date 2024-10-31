<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait HasFilter
{
    public function scopeKey($query, $column, $keyword, $operator = '=')
    {
        return $query->where($column, $operator, intval($keyword));
    }

    public function scopeFilterWhere($query, $column, $keyword, $operator = '=')
    {
        if ( ! empty($keyword)) {
            return $query->where($column, $operator, $keyword);
        }

        return $query;
    }

    public function scopeOrFilterWhere($query, $column, $keyword, $operator = '=')
    {
        if ( ! empty($keyword)) {
            return $query->orWhere($column, $operator, $keyword);
        }

        return $query;
    }

    public function scopeFilterLike($query, $column, $keyword)
    {
        if ( ! empty($keyword)) {
            $keyword = strtolower($keyword);
            return $query->where(DB::raw("LOWER($column)"), "like", "%".$keyword."%");
        }

        return $query;
    }

    public function scopeOrFilterLike($query, $column, $keyword)
    {
        if ( ! empty($keyword)) {
            $keyword = strtolower($keyword);
            return $query->orWhere(DB::raw("LOWER($column)"), "like", "%".$keyword."%");
        }

        return $query;
    }

    public function scopeFilterWhereHas($query, $relation, $related)
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

    public function scopeOrFilterWhereHas($query, $relation, $related)
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

    public function scopeActive($query)
    {
        return $query->where($this->getTable() . '.status', 'active');
    }

    public function scopeWhereDateBetween($query, $column, $startDate, $endDate)
    {
        return $query->whereBetween(DB::raw($column), [$startDate, $endDate]);
    }

    public function scopeWhereDateIs($query, $date)
    {
        return $query->where($this->getTable() . '.' . $this->getCreatedAtColumn(), $date);
    }
}
