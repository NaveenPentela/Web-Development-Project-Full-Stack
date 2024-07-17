var questionNum = 0;

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

    //questionNum += 1;
    const questionN = document.getElementById("question-N");
    questionN.innerText = (questionNum+1).toString();
   
}

function init()
{
    //
    const player = document.getElementById("player-name"); //document=html
    player.innerText = getUrlParam("name"); 
    
    getQuestion();

    //eventLisner
    const btnN = document.getElementById("btn-n");
    btnN.addEventListener('click', next); //call 'next' function

    //hide resuls 
    // document.getElementById("results").style.visibility="hidden"
    document.getElementById("results").style.display="none"
    
}


// function showEventType(event){
//     console.log(event.type);
// }

function next(){
    let arrayC=[];

    //get answer from radio button
    // const choices = document.getElementsByName("choices");
    // for(var i = 0; i < choices.length; i++){
    //     if(choices[i].checked) {
    //         array[questionNum]=choices[i].value
    //         console.log("選択された値：", choices[i].value);}}

    const choices = getSelection("choices") //⇐radio button will be coose only one
        if (choices){ //provided-script
        arrayC.push(choices.value);
        clearSelection("choices")
        // for(const element of document.getElementsByName("choices")){
        //     element.checked = false;
        // }
    
        questionNum += 1;

        //when it is a last question...
        if (questions.length === questionNum){
            const cbElem = document.getElementById("btn-n");
            document.getElementById("btn-n").style.visibility="hidden"
            //document.getElementById("results").style.display="inline"
        } else{
            getQuestion();
        }
    }

}

init();
