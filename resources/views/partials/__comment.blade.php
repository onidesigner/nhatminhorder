<?php
        $user = App\User::find(Auth::user()->id);


        $comments_public = App\Comment::where([
            'object_id' => $object_id,
            'object_type' => $object_type,
            'scope' => App\Comment::TYPE_EXTERNAL
        ])
            ->orderBy('created_at', 'desc')
            ->get();

        $comments_private = App\Comment::where([
            'object_id' => $object_id,
            'object_type' => $object_type,
            'scope' => App\Comment::TYPE_INTERNAL
        ])
            ->orderBy('created_at', 'desc')
            ->get();
?>


<div class="card">
    <div class="card-header">
        Trao đổi về đơn hàng
    </div>
    <div class="card-body">
        <input data-scope="{{ App\Comment::TYPE_EXTERNAL  }}" type="text" style="width: 100%; margin-bottom: 20px;" class="form-control1 _input-comment" placeholder="Chat với khách...">

        @if($comments_public)
            <ul style="list-style: none;
            overflow-y: auto;
    margin: 0;
    padding: 0;">
            @foreach($comments_public as $comment_public)
                <li style="margin-bottom: 10px;

                    @if(in_array($comment_public->type_context, [App\Comment::TYPE_CONTEXT_ACTIVITY, App\Comment::TYPE_CONTEXT_LOG]))
                        color:grey;
                    @endif
                    ">
                    @if($comment_public->type_context != App\Comment::TYPE_CONTEXT_LOG)
                        <?php
                        $user_comment = App\User::find($comment_public->user_id);
                        ?>
                        <strong>{{$user_comment->name}}</strong>
                    @endif
                    {{$comment_public->message}}
                    <small>{{$comment_public->created_at}}</small>
                </li>
            @endforeach
            </ul>
        @endif

        <br>

        <input data-scope="{{ App\Comment::TYPE_INTERNAL  }}" type="text" style="width: 100%; margin-bottom: 20px;" class="form-control1 _input-comment" placeholder="Chat nội bộ...">

        @if($comments_private)
            <ul style="list-style: none;
            overflow-y: auto;
    margin: 0;
    padding: 0;">
                @foreach($comments_private as $comment_private)
                    <li style="margin-bottom: 10px;

                    @if(in_array($comment_private->type_context, [App\Comment::TYPE_CONTEXT_ACTIVITY, App\Comment::TYPE_CONTEXT_LOG]))
                            color:grey;
                    @endif
                            ">
                        @if($comment_private->type_context != App\Comment::TYPE_CONTEXT_LOG)
                            <?php
                            $user_comment = App\User::find($comment_private->user_id);
                            ?>
                            <strong>{{$user_comment->name}}</strong>
                        @endif
                        {{$comment_private->message}}
                        <small>{{$comment_private->created_at}}</small>
                    </li>
                @endforeach
            </ul>
        @endif

        <div id="_content"></div>
    </div>
</div>

@section('js_bottom')
@parent
<script>
    $(document).ready(function(){
        var object_id = "{{$object_id}}";
        var object_type = "{{$object_type}}";

        var comment_tpl = _.template($('#_comment-row-template').html());

        {{--$.ajax({--}}
            {{--url: "{{ url('comment')  }}",--}}
            {{--method: 'get',--}}
            {{--data: {--}}
                {{--object_id: object_id,--}}
                {{--object_type: object_type,--}}
                {{--"_token": "{{ csrf_token() }}"--}}
            {{--},--}}
            {{--success:function(response) {--}}
                {{--$('#_content').html( comment_tpl (response) );--}}
            {{--},--}}
            {{--error: function(){--}}

            {{--}--}}
        {{--});--}}

        $(document).on('keypress', '._input-comment', function(event){
            var $that = $(this);
            if(event.keyCode == 13){
                var comment = $(this).val();
                var scope = $(this).data('scope');

                $.ajax({
                  url: "{{ url('comment')  }}",
                  method: 'post',
                  data: {
                      object_id: object_id,
                      object_type: object_type,
                      scope: scope,
                      message: comment,
                      is_public_profile:1,
                      "_token": "{{ csrf_token() }}"
                  },
                  success:function(response) {
                      if(response.success){
                          window.location.reload();
//                          $that.val('').focus();
//
//                          $('#_content').prepend( comment_tpl (response) );
                      }else{
                          bootbox.alert(response.message);
                      }
                  },
                  error: function(){

                  }
                });
            }
        });
    });
</script>

@endsection

@section('css_bottom')
@parent
<style>
    .comment-text-grey{
        color: grey!important;
    }
</style>
@endsection

<script type="text/template" id="_comment-row-template">
    <% _.each(data, function(comment, key){ %>
    <div class="media social-post <% if (comment.type_context != 'CHAT'){ %> comment-text-grey <% } %>">
        <div class="media-body">
            <div class="media-heading">
                <% if(comment.type_context != 'LOG'){ %>
                    <h4><%= comment.name %></h4>
                <% } %>
                <h5 class="timeing"><%= comment.created_at %></h5>
            </div>
            <div class="media-content">
                <%= comment.message %>
            </div>
        </div>
    </div>
    <% }) %>
</script>