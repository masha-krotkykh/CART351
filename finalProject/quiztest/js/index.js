

// json array sequence variable
var i = 0;
var logicCount = 0 ;
var abstractCount = 0;
var correctCount = 0;
//initialize the first question
generate(0);
// generate from json array data with index
function generate(index) {
    document.getElementById("question").innerHTML = jsonData[index].q;
    document.getElementById("optt1").innerHTML = jsonData[index].opt1;
    document.getElementById("optt2").innerHTML = jsonData[index].opt2;
    document.getElementById("optt3").innerHTML = jsonData[index].opt3;
    document.getElementById("optt4").innerHTML = jsonData[index].opt4;
    document.getElementById("optt5").innerHTML = jsonData[index].opt5;
}

function checkAnswer() {
    if (document.getElementById("opt1").checked && jsonData[i].opt1 == jsonData[i].answer) {
      if (jsonData[i].type == "logic"){
        logicCount++;
      }
      else if (jsonData[i].type == "abstract"){
        abstractCount++;
      }
       correctCount++;
    }
    if (document.getElementById("opt2").checked && jsonData[i].opt2 == jsonData[i].answer) {
      if (jsonData[i].type == "logic"){
        logicCount++;
      }
      else if (jsonData[i].type == "abstract"){
        abstractCount++;
      }
        correctCount++;
    }
    if (document.getElementById("opt3").checked && jsonData[i].opt3 == jsonData[i].answer) {
      if (jsonData[i].type == "logic"){
        logicCount++;
      }
      else if (jsonData[i].type == "abstract"){
        abstractCount++;
      }
        correctCount++;
    }
    if (document.getElementById("opt4").checked && jsonData[i].opt4 == jsonData[i].answer) {
      if (jsonData[i].type == "logic"){
        logicCount++;
      }
      else if (jsonData[i].type == "abstract"){
        abstractCount++;
      }
        correctCount++;
    }
    if (document.getElementById("opt5").checked && jsonData[i].opt5 == jsonData[i].answer) {
      if (jsonData[i].type == "logic"){
        logicCount++;
      }
      else if (jsonData[i].type == "abstract"){
        abstractCount++;
      }
        correctCount++;
    }
    // increment i for next question
    i++;
    if(jsonData.length-1 < i){
        // document.write("<body>");
        // document.write("<div>Your logic score is : "+logicCount+"</div>");
        // document.write("<div>Your abstract score is : "+abstractCount+"</div>");
        // document.write("<div>Your total score is : "+correctCount+"</div>");
        document.getElementById('buttonS').disabled = false;
        document.getElementById("logic").innerHTML = logicCount;
        document.getElementById("abstract").innerHTML = abstractCount;
        document.getElementById("total").innerHTML = correctCount;
        console.log(logicCount);
        console.log(abstractCount);
        console.log(correctCount);
        // document.write("</body>");
    }
    // callback to generate
    generate(i);
}
