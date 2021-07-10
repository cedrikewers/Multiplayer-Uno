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
        <div class="col-3" style="height: 20vh;"><img src="/assets/img/tres.png" height="100%" /></div>
        <div class="col-6 text-center d-xl-flex justify-content-center align-items-center"><img src="/assets/img/deck.png" height="40px" style="margin-right: 2px;margin-bottom: 59px;" />
            <div><img src="/assets/img/user.png" height="100px" />
                <p id="player3" class="username">Not in Game3</p>
            </div><span id="cardCount3" style="position: absolute;margin-bottom: 64px;margin-right: 108px;font-weight: bold;"></span>
        </div>
    </div>
    <div class="row" style="margin:0%">
        <div class="col-3 d-xl-flex justify-content-center align-items-center" style="height: 35vh;"><span id="cardCount2" style="position: absolute;margin-bottom: 64px;margin-right: 108px;font-weight: bold;"></span><img src="/assets/img/deck.png" height="40px" style="margin-right: 2px;margin-bottom: 59px;" />
            <div><img src="/assets/img/user.png" height="100px" />
                <p id="player2" class="username">Not in game2</p>
            </div>
        </div>
        <!-- offener Talon -->
        <div id="oTalon" class="col-6" >
        <div id="chooseColor" style="position: absolute; margin: 25px 0px 0px 80px; display:none" >
            <div class="row" style="width: 240px;background: rgb(33, 37, 41); padding:20px 20px 0 20px">
                <div class="col chooseColorField" style="height: 90px;background: #ff5555;margin-right: 20px;margin-bottom: 20px;" data-color="r"></div>
                <div class="col chooseColorField" style="height: 90px;background: #5555fd;" data-color="b"></div>
            </div>
            <div class="row" style="width: 240px;background: rgb(33, 37, 41); height:110px; padding: 0px 20px 0px 20px">
                <div class="col chooseColorField" style="background: #ffaa00;height: 90px;margin-right:20px" data-color="y"></div>
                <div class="col chooseColorField" style="background: #55aa55;height: 90px;" data-color="g"></div>
            </div>
        </div>
        </div>
        <div class="col-3 d-xl-flex justify-content-center align-items-center"><img src="/assets/img/deck.png" height="40px" style="margin-right: 2px;margin-bottom: 59px;" />
            <div><img src="/assets/img/user.png" height="100px" />
                <p id="player4" class="username">Not in Game4</p>
            </div><span id="cardCount4" style="position: absolute;margin-bottom: 64px;margin-right: 108px;font-weight: bold;"></span>
        </div>
    </div>
    <div class="row"style="margin:0%">
        <div class="col-3" style="height: 20vh;"></div>
            <div class="col-6 d-xl-flex justify-content-center align-items-center"><img src="/assets/img/deck.png" height="40px" style="margin-right: 2px;margin-bottom: 59px;" />
            <div><img src="/assets/img/user.png" height="100px" />
                <p id="player1" class="username"></p>
            </div><span id="cardCount1" style="position: absolute;margin-bottom: 64px;margin-right: 108px;font-weight: bold;"></span>
        </div>
        <div class="col-3"></div>
    </div>
    <div class="row" style="margin:0%">
    <div class="col-1"></div>
        <div id="hand" class="col-10 d-flex justify-content-center align-items-center">
    </div>

    <img id="talon" src="/assets/img/CardBack.png" style="position:absolute; left: 0px; width: 200px; bottom: 0px" onclick="drawCard()"/>
</div>

<script>

    const source = new EventSource("<?php echo site_url('/game/update/'.$id.'/'.$_GET['userId']);?>");

    first = true;
    order = ['host', 0, 1, 2, 'host', 0, 1];
    var pointer = 0;
    var hand;
    var self;
    var lastCard;
    var updateTalon = true;
    var current;

    source.onmessage = function(event){
        let data = JSON.parse(event.data);

        pointer = order.indexOf(data.client.number);

        hand = data.players[order[pointer]].hand;

        lastCard = data.oTalon[data.oTalon.length-1];

        //first time only
        if(first){
            for (let i = 0; i < hand.length; i++) {
                $("#hand").append('<div class="cardHand" onclick="playCard($(this))" data-name="'+hand[i].name+'" data-x="'+hand[i].x+'" data-y="'+hand[i].y+'" data-id="'+hand[i].id+'"><img src="/assets/UNO_cards_deck.svg" height="800%" style="margin-top: -'+(hand[i].y*230)+'px; margin-left: -'+(hand[i].x*153)+'px;"></div>');
            }

            $("#oTalon").append('<div class="cardWraper" data-id="'+lastCard.id+'" data-name="'+lastCard.name+'"> <img src="/assets/UNO_cards_deck.svg" height="800%" style="margin-top: -'+lastCard.y*180+'px; margin-left: -'+lastCard.x*120+'px"></div>');

            self = order[pointer];
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

        //update oTalon
        if(updateTalon){
            if(lastCard.id != $("#oTalon").children().last().data("id")){
                $("#oTalon").append('<div class="cardWraper" style="transform:rotate('+(Math.random()*360)+'deg)" data-id="'+lastCard.id+'" data-name="'+lastCard.name+'"> <img src="/assets/UNO_cards_deck.svg" height="800%" style="margin-top: -'+lastCard.y*180+'px; margin-left: -'+lastCard.x*120+'px"></div>');
            }
            if($("#oTalon").children().length > 5){
                $("#oTalon").children().eq(1).remove();
            }
        }


    }   

    async function playCard(pThis){
        if(pThis.data("name").substring(0, 1) == lastCard.name.substring(0,1) || pThis.data("name").substring(0,1) == "n" || pThis.data("name").substring(1) == lastCard.name.substring(1)){
            $("#oTalon").append('<div class="cardWraper" style="transform:rotate('+(Math.random()*360)+'deg)" data-id="'+pThis.data("id")+'"> <img src="/assets/UNO_cards_deck.svg" height="800%" style="margin-top: -'+pThis.data('y')*180+'px; margin-left: -'+pThis.data('x')*120+'px"></div>');
            
            if(pThis.data("name").substring(0,1) == "n"){
                updateTalon = false;
                current = pThis;
                $("#chooseColor").show();
            }
            else{

                $.post("<?php echo site_url('/game/playCard/'.$id.'/');?>"+self,
                {
                    id: pThis.data('id'),
                },
                function(error){
                    console.log(error);
                });
            }
            pThis.remove();
        }
    }

    function drawCard(){
        $.post("<?php echo site_url('/game/drawCard/'.$id.'/');?>"+self,
        {
        },
        function(JSONcard){
            let card = JSON.parse(JSONcard);
            $("#hand").append('<div class="cardHand" onclick="playCard($(this))" data-name="'+card.name+'" data-x="'+card.x+'" data-y="'+card.y+'" data-id="'+card.id+'"><img src="/assets/UNO_cards_deck.svg" height="800%" style="margin-top: -'+(card.y*230)+'px; margin-left: -'+(card.x*153)+'px;"></div>');
        });
    }

    $(document).ready(function(){
        $(".chooseColorField").click(function(){//eine Frabe wird gewählt
            $("#chooseColor").hide();
            $.post("<?php echo site_url('/game/playCard/'.$id.'/');?>"+self,
                {
                    id: current.data('id'),
                    color: $(this).data('color'),
                });

            updateTalon = true;

        });
    })


</script>