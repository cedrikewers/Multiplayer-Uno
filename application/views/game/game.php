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
</style>
<div style="background: rgb(211,58,73);min-height:100%;">
    <div class="row"style="margin:0%">
        <div class="col-lg-3 col-xl-3" style="height: 20vh;"><img src="/assets/img/tres.png" height="100%" /></div>
        <div class="col-lg-8 col-xl-6 text-center d-xl-flex justify-content-xl-center align-items-xl-center"><img src="/assets/img/deck.png" height="40px" style="margin-right: 2px;margin-bottom: 59px;" />
            <div><img src="/assets/img/user.png" height="100px" />
                <p style="color: rgb(255,255,255);text-align: center;">Username</p>
            </div><span style="position: absolute;margin-bottom: 64px;margin-right: 108px;font-weight: bold;">10</span>
        </div>
    </div>
    <div class="row" style="margin:0%">
        <div class="col-xl-3 d-xl-flex justify-content-xl-center align-items-xl-center" style="height: 35vh;"><span style="position: absolute;margin-bottom: 64px;margin-right: 108px;font-weight: bold;">10</span><img src="/assets/img/deck.png" height="40px" style="margin-right: 2px;margin-bottom: 59px;" />
            <div><img src="/assets/img/user.png" height="100px" />
                <p style="color: rgb(255,255,255);text-align: center;">Username</p>
            </div>
        </div>
        <!-- offener Talon -->
        <div class="col-xl-6" >
       <div class="cardWraper" style="transform:rotate(0deg)"> 
            <img src="/assets/UNO_cards_deck.svg" height="800%">
        </div>
            
        </div>
        <div class="col-xl-3 d-xl-flex justify-content-xl-center align-items-xl-center"><img src="/assets/img/deck.png" height="40px" style="margin-right: 2px;margin-bottom: 59px;" />
            <div><img src="/assets/img/user.png" height="100px" />
                <p style="color: rgb(255,255,255);text-align: center;">Username</p>
            </div><span style="position: absolute;margin-bottom: 64px;margin-right: 108px;font-weight: bold;">10</span>
        </div>
    </div>
    <div class="row"style="margin:0%">
        <div class="col-xl-3" style="height: 20vh;"></div>
        <div class="col-xl-6 d-xl-flex justify-content-xl-center align-items-xl-center"><img src="/assets/img/deck.png" height="40px" style="margin-right: 2px;margin-bottom: 59px;" />
            <div><img src="/assets/img/user.png" height="100px" />
                <p style="color: rgb(255,255,255);text-align: center;"><?php echo $_COOKIE['username'] ?></p>
            </div><span style="position: absolute;margin-bottom: 64px;margin-right: 108px;font-weight: bold;">10</span>
        </div>
        <div class="col-xl-3"></div>
    </div>
    <div class="row" style="margin:0%">
    <div class="col-2"></div>
    <div class="col-8 d-flex justify-content-center align-items-center">
        <div class="cardHand" style="transform:rotate(0deg)" onclick="alert('0')"> 
                <img src="/assets/UNO_cards_deck.svg" height="800%" style="margin-top: -0px; margin-left: 0px;">
        </div>
        <div class="cardHand" style="transform:rotate(0deg)"onclick="alert('2')"> 
                <img src="/assets/UNO_cards_deck.svg" height="800%" style="margin-top: -230px; margin-left: -308px;">
        </div>
        <div class="cardHand" style="transform:rotate(0deg)"onclick="alert('1')"> 
                <img src="/assets/UNO_cards_deck.svg" height="800%" style="margin-top: -460px; margin-left: -154px;">
        </div>
        <div class="cardHand" style="transform:rotate(0deg)"onclick="alert('3')"> 
                <img src="/assets/UNO_cards_deck.svg" height="800%" style="margin-top: -690px; margin-left: -462px;">
        </div>
    </div>
        
    <div class="col-2"></div>
    </div>
</div>