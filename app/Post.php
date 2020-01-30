<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['contenido'];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function comentarios(){
        return $this->hasMany('App\Comentario');
    }
}
