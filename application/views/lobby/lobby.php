<div style="background-color: rgb(211,58,73);min-height:100%">
    <div class="row justify-content-center" style="margin: 0%;">
        <div class="col-md-8 col-lg-6 col-xl-4"><img src="<?php echo site_url('../assets/img/tres.png') ?>")" style="width: 100%" /></div>
    </div>
    <div class="row justify-content-center" style="margin: 0%;">
        <div class="col-10 col-md-9 col-lg-8 col-xl-5 col-xxl-6">
            <div id="playerDisplay" class="row">
                <div class="col-4 ">
                    <figure class="figure" style="max-width:185.5px"><img class="img-fluid figure-img" src="<?php echo site_url('../assets/img/host.png') ?>" />
                        <figcaption id="hostIcon" class="figure-caption text-center" style="color: rgb(255,255,255); overflow: hidden"></figcaption>
                    </figure>
                </div>
            </div>
        </div>
    </div>
    <?php if ($host) {
            echo '<div class="row justify-content-center" style="margin:0%">
            <div class="col-4 text-center">
            <button id="start" class="btn btn-lg" type="button" style="background: rgb(68,177,103);">Start Game</button>
            </div>
            </div>
            <div class="row justify-content-center" style="margin:0%">
            <div class="col-4 text-center">
                <p class="m-3">' . $id . '</p>
            </div>
            </div>';
    }?>

</div>

<script>
    var source = new EventSource("<?php echo site_url('lobby/update/' . $id) ?>");
    var playerCount = 1;

    source.onmessage = function(event){
        let lobbyData = JSON.parse(event.data);

        if(lobbyData.state == 'game'){
            window.location.replace("<?php echo site_url('g/' . $id . '?userId=' . $_COOKIE['id']) ?>");
        }

        $('#hostIcon').html(lobbyData.players.host.username);

        while(playerCount < lobbyData.playerCount){
            $('#playerDisplay').append('<div class="col-4 "><figure class="figure" style="max-width:185.5px"><img class="img-fluid figure-img" src="<?php echo site_url('../assets/img/user.png') ?>"/><figcaption id="player'+(playerCount-1)+'" class="figure-caption text-center" style="color: rgb(255,255,255);overflow: hidden">'+lobbyData["players"][playerCount-1].username+'</figcaption></figure>');
            playerCount++;
        }
    }

    $(document).ready(function(e){
        $('#start').click(function(){
            $.post("<?php echo site_url('lobby/start/' . $id) ?>");
            window.location.replace("<?php echo site_url('g/' . $id . '?userId=' . $_COOKIE['id']) ?>");
        });
    });
</script>