<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    const TYPE_EXTERNAL = 'EXTERNAL';
    const TYPE_INTERNAL = 'INTERNAL';
    const TYPE_NONE = 'NONE';

    const TYPE_CONTEXT_CHAT = 'CHAT';
    const TYPE_CONTEXT_ACTIVITY = 'ACTIVITY';
    const TYPE_CONTEXT_LOG = 'LOG';


    const TYPE_OBJECT_ORDER = 'ORDER';
    const TYPE_OBJECT_ORDER_ITEM = 'ORDER_ITEM';

    public function addNewComment($data_insert){
        return $this->newQuery()->insert($data_insert);
    }

    public function getComments($data_where){
        return $this->newQuery()->where($data_where)->orderBy('created_at', 'desc')->get();
    }
}
