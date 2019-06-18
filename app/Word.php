<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    //
  protected $fillable = ['word', 'longdate', 'word_meta', 'lexi_stat_meta', 'update_interval', 'update_iso'];
  protected $casts = [
    'word_meta' => 'array',
    'lexi_stat_meta' => 'array'
  ];

  public function unserializeEntry() {
    return unserialize($this->word_meta);
  }

  public function unserializeLexi() {
    return unserialize($this->lexi_stat_meta);
  }

}
