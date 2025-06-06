<style>
    .cardWrapper{
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
        <div class="col-3" style="height: 20vh;"><img src="<?php echo site_url('../assets/img/tres.png') ?>" height="100%" /></div>
        <div class="col-6 text-center d-xl-flex justify-content-center align-items-center"><img src="<?php echo site_url('../assets/img/deck.png') ?>" height="40px" style="margin-right: 2px;margin-bottom: 59px;" />
            <div><img src="<?php echo site_url('../assets/img/user.png') ?>" height="100px" />
                <p id="player3" class="username">Not in Game3</p>
            </div><span id="cardCount3" style="position: absolute;margin-bottom: 64px;margin-right: 108px;font-weight: bold;"></span>
        </div>
    </div>
    <div class="row" style="margin:0%">
        <div class="col-3 d-xl-flex justify-content-center align-items-center" style="height: 35vh;"><span id="cardCount2" style="position: absolute;margin-bottom: 64px;margin-right: 108px;font-weight: bold;"></span><img src="<?php echo site_url('../assets/img/deck.png') ?>" height="40px" style="margin-right: 2px;margin-bottom: 59px;" />
            <div><img src="<?php echo site_url('../assets/img/user.png') ?>" height="100px" />
                <p id="player2" class="username">Not in game2</p>
            </div>
        </div>
        <!-- offener Talon -->
        <div class="col-6" >
            <div class="d-xl-flex justify-content-center align-items-center" style="position: absolute; width: 50%">
                <h1 id="showWin"></h1>
            </div>


        <span id="showColor" style="position:absolute; text-align: center; font-size: 40px; font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif; width:51%" ></span>

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
            <div id="oTalon" >

            </div>
        </div>
        <div class="col-3 d-xl-flex justify-content-center align-items-center"><img src="<?php echo site_url('../assets/img/deck.png') ?>" height="40px" style="margin-right: 2px;margin-bottom: 59px;" />
            <div><img src="<?php echo site_url('../assets/img/user.png') ?>" height="100px" />
                <p id="player4" class="username">Not in Game4</p>
            </div><span id="cardCount4" style="position: absolute;margin-bottom: 64px;margin-right: 108px;font-weight: bold;"></span>
        </div>
    </div>
    <div class="row"style="margin:0%">
        <div class="col-3" style="height: 20vh;"></div>
            <div class="col-6 d-xl-flex justify-content-center align-items-center"><img src="<?php echo site_url('../assets/img/deck.png') ?>" height="40px" style="margin-right: 2px;margin-bottom: 59px;" />
            <div><img src="<?php echo site_url('../assets/img/user.png') ?>" height="100px" />
                <p id="player1" class="username"></p>
            </div><span id="cardCount1" style="position: absolute;margin-bottom: 64px;margin-right: 108px;font-weight: bold;"></span>
        </div>
        <div class="col-3"></div>
    </div>
    <div class="row" style="margin:0%">
        <div class="col-1"></div>
        <div id="hand" class="col-10 d-flex justify-content-center align-items-center"></div>
        <div class="col-1 d-flex justify-content-center align-items-center">
            <button id="endTurn" class="btn btn btn-warning" style="display: none;">End Turn</button>
        </div>

    <img id="talon" src="<?php echo site_url('../assets/img/CardBack.png') ?>" style="position:absolute; left: 0px; width: 200px; bottom: 0px" onclick="drawCard()"/>
</div>

<script>
    const source = new EventSource("<?php echo site_url('/game/update/' . $id . '/' . $_GET['userId']); ?>");

    first = true;
    order = ['host', 0, 1, 2, 'host', 0, 1];
    var pointer = 0;
    var hand;
    var self;
    var prev;
    var next;
    var lastCard;
    var updateTalon = true;
    var current;
    var clientTurn = false;
    var cardDrawn = null;
    var gameRunning = true;



    source.onmessage = async function(event){
        if(gameRunning){

        let data = JSON.parse(event.data);

        pointer = order.indexOf(data.client.number);

        hand = data.players[order[pointer]].hand;

        lastCard = data.oTalon[data.oTalon.length-1];

        //first time only
        if(first){
            for (let i = 0; i < hand.length; i++) {
                $("#hand").append('<div class="cardHand" onclick="playCard($(this))" data-name="'+hand[i].name+'" data-x="'+hand[i].x+'" data-y="'+hand[i].y+'" data-id="'+hand[i].id+'"><img src="<?php echo site_url('../assets/UNO_cards_deck.svg') ?>" height="800%" style="margin-top: -'+(hand[i].y*230)+'px; margin-left: -'+(hand[i].x*153)+'px;"></div>');
            }

            $("#oTalon").append('<div class="cardWarper" data-id="'+lastCard.id+'" data-name="'+lastCard.name+'"> <img src="<?php echo site_url('../assets/UNO_cards_deck.svg') ?>" height="800%" style="margin-top: -'+lastCard.y*180+'px; margin-left: -'+lastCard.x*120+'px"></div>');

            self = order[pointer];
            next = order[(pointer+1) % data.playerCount];
            first = false;
        }

        //check winner
        if(data.winner != null){
            $("#showWin").html(data.players[data.winner].username+" wins the game");
            gameRunning = false;
        }

        //update Turn
        if(order[data.turn] == self){
            clientTurn = true;
        }
        else{
            clientTurn = false;
        }

       //update players
        for(let i = 1; i <= 4; i++){
            if(order[data.turn] == order[pointer])$("#player"+i).prev().css("border", "5px solid #FFC100");
            else $("#player"+i).prev().css("border", "none");
            if(data.players[order[pointer]]){
                $("#player"+i).html(data.players[order[pointer]].username);
                $("#cardCount"+i).html(data.players[order[pointer]].hand.length);
                prev = order[pointer];
            }
            pointer++;
        }


        //update oTalon
        if(updateTalon){
            if(lastCard.id != $("#oTalon").children().last().data("id")){
                $("#oTalon").append('<div class="cardWarper" style="transform:rotate('+(Math.random()*360)+'deg)" data-id="'+lastCard.id+'" data-name="'+lastCard.name+'"> <img src="<?php echo site_url('../assets/UNO_cards_deck.svg') ?>" height="800%" style="margin-top: -'+lastCard.y*180+'px; margin-left: -'+lastCard.x*120+'px"></div>');
            }
            if($("#oTalon").children().length > 4){
                $("#oTalon").children().eq(0).remove();
            }
            if(lastCard.name.substring(1) == "c" || lastCard.name.substring(1) == "plus4"){
                    let colorHex;
                    let colorName;
                    switch(lastCard.name.substring(0,1)){
                        case "r":
                            colorHex = "#ff5555";
                            colorName = "RED";
                            break;
                        case "g":
                            colorHex = "#55aa55";
                            colorName = "GREEN";
                            break;
                        case "b":
                            colorHex = "#5555fd";
                            colorName = "BLUE";
                            break;
                        case "y":
                            colorHex = "#ffaa00";
                            colorName = "YELLOW";
                            break;
                    }
                    $("#showColor").css("color", colorHex);
                    $("#showColor").html(colorName);
                }
            else{
                $("#showColor").html("");
            }
        }

         //execute Card
         if(data.cardDictate.executed == false){//ist die order schon ausgeführt
            if(data.cardDictate.origin == prev && data.clockwise == true || data.cardDictate.origin == next && data.clockwise == false){//betrifft die Order den Spieler
                $.post("<?php echo site_url('/game/orderExecuted/' . $id . '/'); ?>");
                switch(data.cardDictate.order){
                    case "plus2":
                        for(let i = 0; i < 2; i++){
                            drawCard(0);
                            await sleep(300);
                        }
                        break;
                    case "plus4":
                        for(let i = 0; i < 4; i++){
                            drawCard(0);
                            await sleep(300);
                        }
                        break;
                }
            }
        }


        }

    }

    function playCard(pThis){
        let allowed = false;

        if(clientTurn && cardDrawn == null){ // if it is the player's turn and no card has been drawn
            if(pThis.data("name").substring(0, 1) == lastCard.name.substring(0,1) || pThis.data("name").substring(0,1) == "n" || pThis.data("name").substring(1) == lastCard.name.substring(1)) allowed = true;
        }
        else{ // if a card has been drawn, it can only be played if it matches the drawn card
            if(cardDrawn['id'] == pThis.data("id")) allowed = true;
        }

        if(allowed){
            $("#oTalon").append('<div class="cardWarper" style="transform:rotate('+(Math.random()*360)+'deg)" data-id="'+pThis.data("id")+'"> <img src="<?php echo site_url('../assets/UNO_cards_deck.svg') ?>" height="800%" style="margin-top: -'+pThis.data('y')*180+'px; margin-left: -'+pThis.data('x')*120+'px"></div>');

            if(pThis.data("name").substring(0,1) == "n"){
                updateTalon = false;
                current = pThis;
                $("#chooseColor").show();
            }
            else{
                clientTurn = false;

                $.post("<?php echo site_url('/game/playCard/' . $id . '/'); ?>"+self,
                {
                    id: pThis.data('id'),
                },
                function(error){
                    console.log(error);
                });
            }
            pThis.remove();
            cardDrawn = null;
            $("#endTurn").hide();
        }
    }

    function drawCard(endTurn = 1){
        if(clientTurn && cardDrawn == null){
            if(endTurn == 1)clientTurn = false;
            $.post("<?php echo site_url('/game/drawCard/' . $id . '/'); ?>"+self+'/'+endTurn,
            {
            },
            function(JSONReturnVal){
                let returnVal = JSON.parse(JSONReturnVal);

                let card = returnVal.card;
                $("#hand").append('<div class="cardHand" onclick="playCard($(this))" data-name="'+card.name+'" data-x="'+card.x+'" data-y="'+card.y+'" data-id="'+card.id+'"><img src="<?php echo site_url('../assets/UNO_cards_deck.svg') ?>" height="800%" style="margin-top: -'+(card.y*230)+'px; margin-left: -'+(card.x*153)+'px;"></div>');

                if(returnVal.playable){
                    $("#endTurn").show();
                    cardDrawn = card;
                }
            });
        }
    }

    $(document).ready(function(){
        $(".chooseColorField").click(function(){ // a color has been chosen
            if(clientTurn){

                clientTurn = false;

                let color = $(this).data('color');
                $("#chooseColor").hide();
                $.post("<?php echo site_url('/game/playCard/' . $id . '/'); ?>"+self,
                    {
                        id: current.data('id'),
                        color: color,
                    });

                updateTalon = true;
            }

        });

        $("#endTurn").click(function(){
            $("#endTurn").hide();
            cardDrawn = null;
            clientTurn = false;
            $.post("<?php echo site_url('/game/endTurn/' . $id); ?>");
        });
    });

    function sleep(time){
        return new Promise(resolve => setTimeout(resolve, time));
    }


</script>