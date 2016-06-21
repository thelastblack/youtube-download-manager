<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    public function getPercentAttribute()
    {
      return (int) ($this->progress / $this->size * 100);
    }

    public function getBootstrapClassAttribute()
    {
      switch($this->status) {
        case 'finished':
          return 'success';
        case 'downloading':
          return 'active';
        case 'failed':
          return 'danger';
        default:
          return null;
      }
    }
}
