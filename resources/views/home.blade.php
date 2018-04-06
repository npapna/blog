@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif


                    <div class="alert alert-danger print-error-msg" style="display:none">
                        <ul></ul>
                    </div>

                   <form id="frmName">
                       @csrf
                       <div class="form-group">
                           <label for="firstname">First Name</label>
                           <input type="text" class="form-control firstname" id="firstname"  placeholder="First Name">
                       </div>
                       <div class="form-group">
                           <label for="lastname">Last Name</label>
                           <input type="text" class="form-control lastname" id="lastname" placeholder="Last Name">
                       </div>
                       <button type="button" class="btn btn-info" id="click">Submit</button>


                   </form>
                </div>
            </div>
        </div>
    </div>
</div>

    <table id="tblInfo" class="table table-dark" style="max-width: 800px;margin: 20px auto;">
        <thead>
            <tr>
                <td><b>ID</b></td>
                <td><b>First Name</b></td>
                <td><b>Last Name</b></td>
            </tr>
        </thead>

        <tbody>
            @foreach($users as $allUser)
                <tr>
                    <td>{{ $allUser->id }}</td>
                    <td>{{ $allUser->first_name }}</td>
                    <td>{{ $allUser->last_name }}</td>
                    <td>
                        <button class="delete btn btn-danger" data-id="{{ $allUser->id }}">Delete</button>
                    </td>
                </tr>

            @endforeach
        </tbody>

    </table>
    {{ $users->links() }}

    <!----blockchain---->


@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="//js.pusher.com/3.1/pusher.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">



<script>

    var pusher = new Pusher('8d58f12ccdbe64beb132', {
        cluster: 'ap1',
        encrypted: true
    });
    var channel = pusher.subscribe('add-user');

    channel.bind('create-user', function(ev) {
        // do something with the event data
        toastr.warning(ev.textdata);
    });

</script>

<script>

    $( document ).ready(function() {



        $("#tblInfo").on('click', '.delete', function () {
            $(this).closest('tr').remove();
            var id = $(this).data("id");
            var _token = $("input[name='_token']").val();
            $.ajax({
                type: 'POST',
                url: 'deleteInfo',
                data: {
                    id:id,
                    _token:_token,
                },
                success: function(data) {
                    if(data.response == "success"){
                        console.log("success");
                    }else{
                        console.log("error");
                    }
                },
            });


        });


        $("#click").on("click",function(){



            var _token = $("input[name='_token']").val();

           var fname =  $(".firstname").val();
           var lname  =  $(".lastname").val();

            $.ajax({
                type: 'POST',
                url: 'checkUser',
                data: {
                    _token:_token,
                    fname:fname,
                    lname:lname,
                },
                success: function(data) {
                   if(data.response == "success"){
                       console.log("success");
                        $(".print-error-msg").hide(500);
                        $('#frmName')[0].reset();

                       $('#tblInfo tr:last').after('<tr>' +
                           '<td>'+data.id+'</td>' +
                           '<td>'+fname+'</td>' +
                           '<td>'+lname+'</td>' +
                           '<td><button class="delete btn btn-danger" data-id="'+data.id+'">Delete</button></td>' +
                           '</tr>');
                   }else{
                       printErrorMsg(data.error);
                       console.log("error");
                   }
                },
            });
        });
        function printErrorMsg (msg) {

            $(".print-error-msg").find("ul").html('');
            $(".print-error-msg").css('display','block');
            $.each( msg, function( key, value ) {
                $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
            });
        }


    });



</script>
