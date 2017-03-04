<div class="card">
    <div class="card-header">
        {{ __('comment.title')  }}
    </div>
    <div class="card-body">
        <div class="media social-post">
            <div class="media-body">
                <div class="media-comment">
                    <input type="text" class="form-control _input-comment" placeholder="comment...">
                </div>
            </div>
        </div>

        <div id="_content"></div>
    </div>
</div>

@section('js_bottom')
@parent
<script>
    $(document).ready(function(){
        var object_id = "{{$object_id}}";
        var object_type = "{{$object_type}}";
        var scope = "{{$scope}}";

        var comment_tpl = _.template($('#_comment-row-template').html());

        $.ajax({
            url: "{{ url('comment')  }}",
            method: 'get',
            data: {
                object_id: object_id,
                object_type: object_type,
                "_token": "{{ csrf_token() }}"
            },
            success:function(response) {
                $('#_content').html( comment_tpl (response) );
            },
            error: function(){

            }
        });

        $(document).on('keypress', '._input-comment', function(event){
            var $that = $(this);
            if(event.keyCode == 13){
                var comment = $(this).val();

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
                        console.log(response);
                        console.log(response.data);
                      if(response.success){
                          $that.val('').focus();

                          $('#_content').prepend( comment_tpl (response) );
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