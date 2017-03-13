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

}
