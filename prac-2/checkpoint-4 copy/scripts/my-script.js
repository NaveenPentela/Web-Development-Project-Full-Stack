var questionNum = 0;
var correct = 0;

function getQuestion()
{
    const question = document.getElementById("question");
    question.innerText = questions[questionNum]["question"];

    const choiceA = document.getElementById("choice-A");
    choiceA.innerText = questions[questionNum]["choiceA"];

    const choiceB = document.getElementById("choice-B");
    choiceB.innerText = questions[questionNum]["choiceB"];

    const choiceC = document.getElementById("choice-C");
    choiceC.innerText = questions[questionNum]["choiceC"];

    const questionN = document.getElementById("question-N");
    questionN.innerText = (questionNum+1).toString();
   
}

function checkAnswer(choice){
    if (questions[questionNum]["answer"] === choice){
        correct += 1;
        console.log(choice, ":", correct);
    }
}

function showResult(){
    const resultsComment = document.getElementById("results-comment");
    var result = 0;
    result = (correct/3)* 100;
    result = Math.round(result*10)/10; 
    const comment = " Your final score was " + result.toFixed(1) + "% (" + correct + "/3).";

    if (result < 30){
        resultsComment.innerText = "Bad luck." + comment;
    }else if(30 <= result && result <= 75){
        resultsComment.innerText = "Not bad." + comment;
    }else if (75 < result){
        resultsComment.innerText = "Impressive." + comment;
    }
}

function next(){
    
    const choices = getSelection("choices") 
    if (choices !== ""){
        checkAnswer(choices);
        clearSelection("choices")
    
        questionNum += 1;

        
        if (questions.length === questionNum){
            document.getElementById("quiz").style.visibility="hidden"; 
            document.getElementById("btn-n").style.visibility="hidden";
            document.getElementById("results").style.display="inline";

            showResult()
        } else{
            getQuestion();
        }
    }

}





function init()
{
    //
    const player = document.getElementById("player-name"); 
    player.innerText = getUrlParam("name"); 
    
    getQuestion();

    
    const btnN = document.getElementById("btn-n");
    btnN.addEventListener('click', next); 

 
    document.getElementById("results").style.display="none"
    
}
init();
