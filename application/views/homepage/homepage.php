<div style="background-color: rgb(211,58,73);height:100%">
    <div class="row justify-content-center" style="margin: 0%;">
        <div class="col-md-8 col-lg-6 col-xl-4"><img src="<?php echo site_url('../assets/img/tres.png')?>" style="width: 100%" /></div>
    </div>
    <div class="row justify-content-center" style="margin: 0%;">
    <div class="col-10 col-md-9 col-lg-8 col-xl-5 col-xxl-5">
        <input id="username" type="text" class="bg-white border rounded-pill shadow-lg form-control" name="username" maxlength="12" placeholder="Username" <?php if(isset($_COOKIE['username']))echo 'value="'.$_COOKIE['username'];?>" />
    </div>
    <div class="row justify-content-center" style="margin: 0%;">
    <div class="col-10 col-md-9 col-lg-8 col-xl-5">
        <div class="row" style="margin-top: 20px;">
            <div class="col text-center joinRemove"><button id="create" class="btn btn-primary btn-lg" type="button" style="background: rgb(68,177,103);">Create a Room</button></div>
            <div class="col text-center"><button id="join" class="btn btn-primary btn-lg" type="button" style="background: rgb(248,218,39);">Join a Game</button></div>
        </div>
    </div>
    </div>  
    </div>
</div>

<script>
    $(document).ready(function(e){
        $("#create").click(function(){
            document.cookie = "username="+$("#username").val();
            window.location.replace("<?php echo site_url();?>lobby/create?username="+$('#username').val());
        });

        $("#join").click(function(){
            document.cookie = "username="+$("#username").val();
            let username = $('#username').val();

            $('.joinRemove').remove();
            $('#join').html('Join game');
            $('#username').attr('placeholder', 'Game ID');
            $('#username').val('');
            $('#join').unbind();
            $('#join').click(function(){
                window.location.replace("<?php echo site_url();?>"+"lobby/join/"+$('#username').val()+'?username='+username);
            });
        });
    });

</script>