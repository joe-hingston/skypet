<?php


namespace App\Http\Requests;


class OutputFilters extends QueryFilter
{

    public function journal($journalid) //?journal=1
    {

        return $this->builder->where('journal_id', $journalid);

    }

    public function score($order = 'desc')
    {
        return $this->builder->orderBy('score', $order);
    }

    public function created($order = 'desc')
    {
        return $this->builder->orderBy('created_at', $order);
    }

    public function language($language = 'en')
    {
        return $this->builder->where('language', $language);
    }

    public function publisher($publisher = 'Wiley')
    {
        return $this->builder->where('publisher', $publisher);
    }
    public function title($title = ''){
        return $this->builder->where('title', 'like', "%$title%");
    }
    public function take($count)
    {
        return $this->builder->limit($count);
    }
}
