function getQuestion(questionNum)
{
    const question = document.getElementById("question");
    question.innerText = questions[questionNum]["question"];

    const choiceA = document.getElementById("choice-A");
    choiceA.innerText = questions[questionNum]["choiceA"];

    const choiceB = document.getElementById("choice-B");
    choiceB.innerText = questions[questionNum]["choiceB"];

    const choiceC = document.getElementById("choice-C");
    choiceC.innerText = questions[questionNum]["choiceC"];

    questionNum += 1;
    const questionN = document.getElementById("question-N");
    questionN.innerText = questionNum.toString();
   
}

function init()
{
    //
    const player = document.getElementById("player-name"); //document=html
    player.innerText = getUrlParam("name"); 
    
    getQuestion(0);

}


init();




