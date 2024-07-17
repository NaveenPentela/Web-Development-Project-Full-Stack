function init()
{
    // <h1>Hello, <b id="player-name">Player</b>. Let's begin.</h1> 
    const player = document.getElementById("player-name");
    player.innerText = getUrlParam("name"); 
}

init();
