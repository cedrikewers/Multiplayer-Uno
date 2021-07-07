<style>
    .cardWraper{
        overflow: hidden;
        height: 180px;
        width: 120px;
        position: absolute;
        margin-left: 22%;
        margin-top: 3.5%;
    }
    .cardHand{
        overflow: hidden;
        height: 230px;
        width: 153px;
    }
    p.username{
        color: rgb(255,255,255);
        text-align: center;
        overflow:hidden;
        width:100px;
    }
</style>
<div style="background: rgb(211,58,73);min-height:100%;">
    <div class="row"style="margin:0%">
        <div class="col-lg-3 col-xl-3" style="height: 20vh;"><img src="/assets/img/tres.png" height="100%" /></div>
        <div class="col-lg-8 col-xl-6 text-center d-xl-flex justify-content-xl-center align-items-xl-center"><img src="/assets/img/deck.png" height="40px" style="margin-right: 2px;margin-bottom: 59px;" />
            <div><img src="/assets/img/user.png" height="100px" />
                <p id="player3" class="username">Not in Game3</p>
            </div><span id="cardCount3" style="position: absolute;margin-bottom: 64px;margin-right: 108px;font-weight: bold;"></span>
        </div>
    </div>
    <div class="row" style="margin:0%">
        <div class="col-xl-3 d-xl-flex justify-content-xl-center align-items-xl-center" style="height: 35vh;"><span id="cardCount2" style="position: absolute;margin-bottom: 64px;margin-right: 108px;font-weight: bold;"></span><img src="/assets/img/deck.png" height="40px" style="margin-right: 2px;margin-bottom: 59px;" />
            <div><img src="/assets/img/user.png" height="100px" />
                <p id="player2" class="username">Not in game2</p>
            </div>
        </div>
        <!-- offener Talon -->
        <div id="oTalon" class="col-xl-6" >
        <div class="cardWraper" style="transform:rotate(60deg)"> 
            <img src="/assets/UNO_cards_deck.svg" height="800%">
        </div>

        </div>
        <div class="col-xl-3 d-xl-flex justify-content-xl-center align-items-xl-center"><img src="/assets/img/deck.png" height="40px" style="margin-right: 2px;margin-bottom: 59px;" />
            <div><img src="/assets/img/user.png" height="100px" />
                <p id="player4" class="username">Not in Game4</p>
            </div><span id="cardCount4" style="position: absolute;margin-bottom: 64px;margin-right: 108px;font-weight: bold;"></span>
        </div>
    </div>
    <div class="row"style="margin:0%">
        <div class="col-xl-3" style="height: 20vh;"></div>
            <div class="col-xl-6 d-xl-flex justify-content-xl-center align-items-xl-center"><img src="/assets/img/deck.png" height="40px" style="margin-right: 2px;margin-bottom: 59px;" />
            <div><img src="/assets/img/user.png" height="100px" />
                <p id="player1" class="username"></p>
            </div><span id="cardCount1" style="position: absolute;margin-bottom: 64px;margin-right: 108px;font-weight: bold;"></span>
        </div>
        <div class="col-xl-3"></div>
    </div>
    <div class="row" style="margin:0%">
    <div class="col-2"></div>
    <div id="hand" class="col-8 d-flex justify-content-center align-items-center">
    </div>
        
    <div class="col-2"></div>
    </div>
</div>

<script>

    const source = new EventSource("<?php echo site_url('/game/update/'.$id.'/'.$_GET['userId']);?>");

    first = true;
    order = ['host', 0, 1, 2, 'host', 0, 1];

    source.onmessage = function(event){
        let data = JSON.parse(event.data);

        let pointer = order.indexOf(data.client.number);

        var hand = data.players[order[pointer]].hand;

        //first time only
        if(first){
            for (let i = 0; i < hand.length; i++) {
                $("#hand").append('<div class="cardHand" onclick="playCard($(this))" data-name="'+hand[i].name+'" data-x="'+hand[i].x+'" data-y="'+hand[i].y+'" data><img src="/assets/UNO_cards_deck.svg" height="800%" style="margin-top: -'+(hand[i].y*230)+'px; margin-left: -'+(hand[i].x*153)+'px;"></div>');
            }

            first = false;
        }

       //update players
        for(let i = 1; i <= 4; i++){
            if(data.players[order[pointer]]){
                $("#player"+i).html(data.players[order[pointer]].username);
                $("#cardCount"+i).html(data.players[order[pointer]].hand.length);
            }
            pointer++;
        }
        pointer -= 3;

        //update oTalon
        if($("#oTalon").children().length > 4){
            $("#oTalon").children(":first").remove();
        }


    }   

    function playCard(pThis){
        $("#oTalon").append('<div class="cardWraper" style="transform:rotate('+(Math.random()*360)+'deg)"> <img src="/assets/UNO_cards_deck.svg" height="800%" style="margin-top: -'+pThis.data('y')*180+'px; margin-left: -'+pThis.data('x')*120+'px"></div>')
        pThis.remove();
    }


</script>