<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    const UPLOAD_PATH = 'uploads';

    public function getPercentAttribute()
    {
        if ($this->size > 0) {
            return (int) ($this->progress / $this->size * 100);
        } else {
            return 0;
        }
    }

    public function getDownloadLinkAttribute()
    {
      return static::UPLOAD_PATH.DIRECTORY_SEPARATOR.$this->filename;
    }

    public function getDownloadFileAttribute()
    {
      return public_path(static::UPLOAD_PATH.DIRECTORY_SEPARATOR.$this->filename);
    }

    public function getDownloadDirectoryAttribute()
    {
      return public_path(static::UPLOAD_PATH);
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

    public function version()
    {
        return $this->hasOne(VideoVersion::class);
    }
}
