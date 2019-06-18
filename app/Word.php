<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    //
  protected $fillable = ['word', 'longdate', 'word_meta', 'lexi_stat_meta'];
  protected $casts = [
    'word_meta' => 'array',
    'lexi_stat_meta' => 'array'
  ];

  public function unserialize() {
    return unserialize($this->lexi_stat_meta);
  }

}
