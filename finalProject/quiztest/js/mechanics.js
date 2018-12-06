// js array sequence variable
var i = 0;
// current session score
var logicCount = 0;
var abstractCount = 0;
var correctCount = 0;
// random  index to pull from the question database
var randomIndex = 0;
// default RGB values
// for Hero graphics

var r = 255;
var g = 255;
var b = 255;
var a = 1;
// and for current session avatar
var ar = 0;
var ag = 0;
var ab = 0;
var aa = 1;

// establishing currents time
var d = new Date();
var time = d.getHours();
// "morning" will be between 2am and 2pm
var morning;
if(time < 14 && time > 2) {
  morning = 1;
}
// otherwise it's "not morning"
else {
  morning = 0;
}
console.log(morning);

//initialize the first question
console.log("randomIndex::"+Math.floor(Math.random() * jsonDataLogic.length));
randomIndex = Math.floor(Math.random() * jsonDataLogic.length);
generateLogic(randomIndex);
// generate from js array data with index
// first for the logic section
function generateLogic(index) {
  document.getElementById("question").innerHTML = jsonDataLogic[index].q;
  document.getElementById("optt1").innerHTML = jsonDataLogic[index].opt1;
  document.getElementById("optt2").innerHTML = jsonDataLogic[index].opt2;
  document.getElementById("optt3").innerHTML = jsonDataLogic[index].opt3;
  document.getElementById("optt4").innerHTML = jsonDataLogic[index].opt4;
  document.getElementById("optt5").innerHTML = jsonDataLogic[index].opt5;
}
// then for the abstract section
function generateAbstract(index) {
  document.getElementById("question").innerHTML = jsonDataAbstract[index].q;
  document.getElementById("optt1").innerHTML = jsonDataAbstract[index].opt1;
  document.getElementById("optt2").innerHTML = jsonDataAbstract[index].opt2;
  document.getElementById("optt3").innerHTML = jsonDataAbstract[index].opt3;
  document.getElementById("optt4").innerHTML = jsonDataAbstract[index].opt4;
  document.getElementById("optt5").innerHTML = jsonDataAbstract[index].opt5;
}
// Checking what option was selected by the user and compare it with the right checkAnswer
// if the answer is corret, increment score of corresponding section by one
function checkAnswers() {
  if (document.getElementById("opt1").checked) {
    if(jsonDataLogic[randomIndex].opt1 == jsonDataLogic[randomIndex].answer) {
      logicCount+=1.0;
      correctCount+=1.0;
    }
    else if(jsonDataAbstract[randomIndex].opt1 == jsonDataAbstract[randomIndex].answer) {
      abstractCount+=1.0;
      correctCount+=1.0;
    }
  }
  if (document.getElementById("opt2").checked) {
    if(jsonDataLogic[randomIndex].opt2 == jsonDataLogic[randomIndex].answer) {
      logicCount+=1.0;
      correctCount+=1.0;
    }
    else if(jsonDataAbstract[randomIndex].opt2 == jsonDataAbstract[randomIndex].answer) {
      abstractCount+=1.0;
      correctCount+=1.0;
    }
  }
  if (document.getElementById("opt3").checked) {
    if(jsonDataLogic[randomIndex].opt3 == jsonDataLogic[randomIndex].answer) {
      logicCount+=1.0;
      correctCount+=1.0;
    }
    else if(jsonDataAbstract[randomIndex].opt3 == jsonDataAbstract[randomIndex].answer) {
      abstractCount+=1.0;
      correctCount+=1.0;
    }
  }
  if (document.getElementById("opt4").checked) {
    if(jsonDataLogic[randomIndex].opt4 == jsonDataLogic[randomIndex].answer) {
      logicCount+=1.0;
      correctCount+=1.0;
    }
    else if(jsonDataAbstract[randomIndex].opt4 == jsonDataAbstract[randomIndex].answer) {
      abstractCount+=1.0;
      correctCount+=1.0;
    }
  }
  if (document.getElementById("opt5").checked) {
    if(jsonDataLogic[randomIndex].opt5 == jsonDataLogic[randomIndex].answer) {
      logicCount++;
      correctCount++;
    }
    else if(jsonDataAbstract[randomIndex].opt5 == jsonDataAbstract[randomIndex].answer) {
      abstractCount++;
      correctCount++;
    }
  }

  // increment i for next question
  i++;

// generating first three questions from logic questions
  if(i <= 2) {
    randomIndex = Math.floor(Math.random() * jsonDataLogic.length);
    generateLogic(randomIndex);
  }
  // next three questions from the abstract section
  else if(i < 6) {
    randomIndex = Math.floor(Math.random() * jsonDataAbstract.length);
    generateAbstract(randomIndex);
  }
  // after six questions are answered, enabling the "submit" button
  // generating the current session score
  else {
      document.getElementById('buttonS').disabled = false;
      document.getElementById("logic").innerHTML = "Logic: "+logicCount;
      document.getElementById("abstract").innerHTML = "Abstract: "+abstractCount;
      document.getElementById("total").innerHTML = "Total: "+correctCount;

      // console logging to see if works
      // console.log(logicCount);
      // console.log(abstractCount);
      // console.log(correctCount);

      // hiding questions and displaying current session results
      document.getElementById("reply").style.display = "none";
      document.getElementById("yourScore").style.display = "block";
      changeColor();
  }
}
$("#insertResults").submit(function(event) {
  //stop submit the form, we will post it manually. PREVENT THE DEFAULT behaviour ...
  event.preventDefault();
  console.log("button clicked");
  // When the "submit" button is pressed, send the session results to the database to the corresponding columns
  let data = new FormData();
  data.append('logicCount', logicCount);
  data.append('abstractCount', abstractCount);
  data.append('correctCount', correctCount);
  data.append('morning', morning);

  // Change the position of the questionForm div when all questions are answered
  // Reset the submit button and start populating quesions anew
  i = 0;

  // hiding previous score and disabling the "submit" button
  document.getElementById('buttonS').disabled = true;
  document.getElementById("yourScore").style.display = "none";

  // And then this...
  $.ajax({
      type: "POST",
      enctype: 'multipart/form-data',
      url: "index1.php",
      data: data,
      processData: false,//prevents from converting into a query string
      contentType: false,
      cache: false,
      timeout: 600000,
      success: function (response) {
        console.log("Yoohoo!"+response);

        // Parse average values of coulumns
        let parsedJSON = JSON.parse(response);
        console.log(parsedJSON);
        updateValues(parsedJSON);
      },
      error:function(){
       console.log("error occurred");
      }
   });
});

// Assigning values to JS variables to change the appearance of the graphics.
// taking these variables from the array passed from the batabase
function updateValues(globalValues) {
  var globalLogic = globalValues[0];
  var globalAbstract = globalValues[1];
  var globalTotal = globalValues[2];
  // just checking if works
  // console.log(globalLogic);
  // console.log(globalAbstract);
  // console.log(globalTotal);
  // Append info into paragraphs below the graphics
  document.getElementById("heroLogic").innerHTML = "Logic Thinking: "+ globalLogic;
  document.getElementById("heroAbstract").innerHTML = "Abstract Thinking: "+ globalAbstract;
  document.getElementById("heroTotal").innerHTML = "General Productivity: "+ globalTotal;
  // Define colour for hero graphics
  // multiplying by 85, to nicely map to RGB values (0-0, 1-85, 2-170, 3-255)
  r = globalAbstract * 85;
  g = Math.abs((globalLogic * 85) - (globalAbstract * 85));
  b = globalLogic * 85;
  // Using total score to define opacity
  // max. value for globalTotal is 6, since the value of alpha cannot exceed 1,
  // let's divide the globalTotal value by 6 and by doing so map alpha to it
  a = globalTotal / 6;

  var color = "rgba("+ r +","+ g +", "+ b +", "+ a +")";

// Now colour all the elements of Hero SVG graphics (which consists of polygons and circles) with current shade
  var heroPolygon = document.querySelectorAll("#hero polygon");
  var p;
  for (p = 0; p < heroPolygon.length; p++) {
   heroPolygon[p].style.fill = color;
  }
  var heroCircle = document.querySelectorAll("#hero circle");
  var c;
  for (c = 0; c < heroCircle.length; c++) {
   heroCircle[c].style.fill = color;
  }
}

// Function to change colour of the current session avatarCircle
// same way as Hero graphics, only using current session results instead of global average results
function changeColor() {
  // Define colour for current session avatar
  // multiplying by 85 to map to RGB values
    ar = abstractCount * 85;
    ag = Math.abs((abstractCount - logicCount) * 85);
    ab = logicCount * 85;
    if(correctCount===0){
      aa = 1;
    }
    else {
      aa = correctCount / 6;
    }

    var aColor = "rgba("+ ar +","+ ag +", "+ ab +", "+ aa +")";

    var avatarPolygon = document.querySelectorAll("#avatar polygon");
    var ap;
    for (ap = 0; ap < avatarPolygon.length; ap++) {
     avatarPolygon[ap].style.fill = aColor;
    }
    var avatarCircle = document.querySelectorAll("#avatar circle");
    var ac;
    for (ac = 0; ac < avatarCircle.length; ac++) {
     avatarCircle[ac].style.fill = aColor;
    }
}

// Function to open and close questionaire
function toggle() {
  // if the questionaire is clossed, "toggle" opens it, rotates the arrows, and hides the avatar
  var margin = document.getElementById("questionForm");
  if(margin.style.display === "none") {
    margin.style.display = "block";
    document.getElementById("buttonH").style.transform = "rotate(-90deg)";
    document.getElementById("reply").style.display = "block";
    document.getElementById('avatar').style.display = "none";
    // this function sets current session scores to zero
    zeroOut();
  }
  else {
    margin.style.display = "none";
    document.getElementById("buttonH").style.transform = "rotate(90deg)";
    document.getElementById('avatar').style.display = "block";
  }
}
// this function sets current session scores to zero
function zeroOut() {
  correctCount = 0;
  logicCount = 0;
  abstractCount = 0;
}

// Function to enable SVG image dynamic properties change
// Borrowed from StackOverflow
// https://stackoverflow.com/questions/24933430/img-src-svg-changing-the-fill-color?answertab=votes#tab-top
// http://jsfiddle.net/wuSF7/462/
$(function(){
jQuery('img.svg').each(function(){
  var $img = jQuery(this);
  var imgID = $img.attr('id');
  var imgClass = $img.attr('class');
  var imgURL = $img.attr('src');

  jQuery.get(imgURL, function(data) {
      // Get the SVG tag, ignore the rest
      var $svg = jQuery(data).find('svg');
      // Add replaced image's ID to the new SVG
      if(typeof imgID !== 'undefined') {
          $svg = $svg.attr('id', imgID);
      }
      // Add replaced image's classes to the new SVG
      if(typeof imgClass !== 'undefined') {
          $svg = $svg.attr('class', imgClass+' replaced-svg');
      }
      // Remove any invalid XML tags as per http://validator.w3.org
      $svg = $svg.removeAttr('xmlns:a');
      // Check if the viewport is set, else we gonna set it if we can.
      if(!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
          $svg.attr('viewBox', '0 0 ' + $svg.attr('height') + ' ' + $svg.attr('width'))
      }
      // Replace image with new SVG
      $img.replaceWith($svg);
  }, 'xml');
});
});
