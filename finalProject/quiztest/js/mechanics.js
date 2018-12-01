// js array sequence variable
var i = 0;
var logicCount = 0 ;
var abstractCount = 0;
var correctCount = 0;
var randomIndex = 0;
var r = 255;
var g = 255;
var b = 255;
var a = 1;

var ar = 0;
var ag = 0;
var ab = 0;
var aa = 1;

//initialize the first question
// var rand = jsonDataLogic[Math.floor(Math.random() * jsonDataLogic.length)];
console.log("randomIndex::"+Math.floor(Math.random() * jsonDataLogic.length));
randomIndex = Math.floor(Math.random() * jsonDataLogic.length);
generateLogic(randomIndex);
// generate from js array data with index
// first for the logic section
function generateLogic(index) {
//  console.log("inside:: "+index);
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
      logicCount++;
    }
    else if(jsonDataAbstract[randomIndex].opt1 == jsonDataAbstract[randomIndex].answer) {
      abstractCount++;
    }
    correctCount++;
  }
  if (document.getElementById("opt2").checked) {
    if(jsonDataLogic[randomIndex].opt2 == jsonDataLogic[randomIndex].answer) {
      logicCount++;
    }
    else if(jsonDataAbstract[randomIndex].opt2 == jsonDataAbstract[randomIndex].answer) {
      abstractCount++;
    }
    correctCount++;
  }
  if (document.getElementById("opt3").checked) {
    if(jsonDataLogic[randomIndex].opt3 == jsonDataLogic[randomIndex].answer) {
      logicCount++;
    }
    else if(jsonDataAbstract[randomIndex].opt3 == jsonDataAbstract[randomIndex].answer) {
      abstractCount++;
    }
    correctCount++;
  }
  if (document.getElementById("opt4").checked) {
    if(jsonDataLogic[randomIndex].opt4 == jsonDataLogic[randomIndex].answer) {
      logicCount++;
    }
    else if(jsonDataAbstract[randomIndex].opt4 == jsonDataAbstract[randomIndex].answer) {
      abstractCount++;
    }
    correctCount++;
  }
  if (document.getElementById("opt5").checked) {
    if(jsonDataLogic[randomIndex].opt5 == jsonDataLogic[randomIndex].answer) {
      logicCount++;
    }
    else if(jsonDataAbstract[randomIndex].opt5 == jsonDataAbstract[randomIndex].answer) {
      abstractCount++;
    }
    correctCount++;
  }

  // increment i for next question
  i++;

  if(i < 3) {
    randomIndex = Math.floor(Math.random() * jsonDataLogic.length);
    generateLogic(randomIndex);
  }
  else if(i >= 3 && i < 6) {
    randomIndex = Math.floor(Math.random() * jsonDataAbstract.length);
    generateAbstract(randomIndex);
  }
  else {
      document.getElementById('buttonS').disabled = false;
      document.getElementById("logic").innerHTML = "Logic: "+logicCount;
      document.getElementById("abstract").innerHTML = "Abstract: "+abstractCount;
      document.getElementById("total").innerHTML = "Total: "+correctCount;

      // console logging to see if works
      console.log(logicCount);
      console.log(abstractCount);
      console.log(correctCount);
      document.getElementById("reply").style.display = "none";
      document.getElementById("yourScore").style.display = "block";
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

  // Change the position of the questionForm div when all questions are answered
  // Reset the submit button and start populating quesions anew
  i = 0;
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
// Define colour for hero graphics
function updateValues(globalValues) {
  var globalLogic = globalValues[0];
  var globalAbstract = globalValues[1];
  var globalTotal = globalValues[2];
  console.log(globalLogic);
  console.log(globalAbstract);
  console.log(globalTotal);

  // since we multiplied global values by 85, they must now nicely map to RGB values (0-0, 1-85, 2-170, 3-255)
  r = globalAbstract;
  g = Math.abs(globalLogic - globalAbstract);
  b = globalLogic;
  // max. value for globalTotal is 85 * 6 = 510, since the value of alpha cannot exceed 1,
  // let's divide the globalTotal value by 510 and by doing so map alpha to it
  a = globalTotal / 510;

  var color = "rgba("+ r +","+ g +", "+ b +", "+ a +")";

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

// Define colour for current session avatar
  ar = abstractCount * 85;
  ag = Math.abs((abstractCount - logicCount) * 85);
  ab = logicCount * 85;
  aa = (abstractCount + logicCount) / 6;
  console.log(aColor);

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

function toggle() {
var margin = document.getElementById("questionForm");
if(margin.style.display === "none") {
  margin.style.display = "block";
  document.getElementById("buttonH").style.transform = "rotate(-90deg)";
  document.getElementById("reply").style.display = "block";
}
else {
  margin.style.display = "none";
  document.getElementById("buttonH").style.transform = "rotate(90deg)";
}
zeroOut();
console.log(abstractCount);
}

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
