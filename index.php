<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style.css" type="text/css">
        <title>Bing Shapiro</title> 
        <link rel="icon" type="image/x-icon" href="/images/ben.ico">
    </head>
    <body>
        <video autoplay muted loop id="myVideo">
            <source src="images/shapirovhs.mp4" type="video/mp4">
        </video>

        <div>
            <div class="logo">
                <img src="images/logo.png" alt="logo">
            </div>
            <form class="search-bar" action="search.php" method="get">
                <input id="input" name="q" type="search" placeholder="search a ben shapiro quote" pattern=".{1,}" required title="1 character minimum">
                <button id="btn" type="search" onclick="search()"><img src="images/search.png"></button>
            </form>
            <div class="volume-ctrl">
                <button id="btn" onclick="mute()"><img id="volume" src="images/unmute.png"></button>
			</div>
            <form class="donate" action="https://www.paypal.com/donate" method="post" target="_top">
                <input type="hidden" name="hosted_button_id" value="FADBPTLREHMHJ" />
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
                <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
            </form>
        </div>
        <script src="index.js"></script>
    </body>
</html>


