<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoVersion extends Model
{
    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
