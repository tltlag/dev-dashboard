<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;

trait OrderingRows
{
    public function canReOrder(): bool
    {
        return (bool) Schema::hasColumn($this->getTable(), 'order');
    }

    public function setOrder(): self
    {
        if (! $this->canReOrder()) {
            return $this;
        }

        if ($this->order) {
            return $this;
        }

        $this->order = $this->order ? $this->order : $this->id;
        $this->save();

        return $this;
    }
}
