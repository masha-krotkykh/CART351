// Custom Object for Assignment 2
// ASSIGNMENT 2 BY MASHA KROTKYKH & ALESSIA SIGNORINO

// linking context from main program
canvas = document.getElementById("testCanvas");
context = canvas.getContext("2d");
audioContext = new (window.AudioContext || window.webkitAudioContext)();

function CustomShape(x,y,size,size,r,g,b,a,c,eid,rSpeed){
    //constructor
    this.x = x;
    this.y = y;
    this.r = r;
    this.g = g;
    this.b = b;
    this.a = a;
    this.size = size;
    this.innerH = this.size/2;
    this.innerW = this.size/2;

    this.eID = eid;
    this.isOver = false; // for freqVal
    this.isPressed = false; // for distortion
    this.isChanging = false;
    this.cChange = 10;
    this.cFade = 1;

    this.theta = 0;
    this.rSpeed = 0;
    this.maxrSpeed = .5;

    this.note = new Sound(this.audioContext);
    this.freqVal = 220 + (eid*128);
    this.clampedVal = 0;
    this.tempFreqVal = 0;

    // display (press affects RGB)
    this.display = function(){
      if(this.isChanging === true) {

        if(this.r <= 0) {
          this.r +=this.cChange;
        }
        else if(this.r >= 250) {
          this.r -=this.cChange;
        }
        else if(this.r > 0 && this.r < 250) {
          this.r -= Math.floor((Math.random()*5)-3);
        }
        if(this.g <= 0) {
          this.g +=this.cChange;
        }
        else if(this.g >= 250) {
          this.g -=this.cChange;
        }
        else if(this.g > 0 && this.g < 250) {
          this.g -= Math.floor((Math.random()*5)-3);
        }
        if(this.b <= 0) {
          this.b +=this.cChange;
        }
        else if(this.b >= 250) {
          this.b -=this.cChange;
        }
        else if(this.b > 0 && this.b < 250) {
          this.b -= Math.floor((Math.random()*5)-3);
        }
      }
      else if(this.isChanging ===false) {
        if(this.r > 0) {
          this.r -= this.cFade;
        }
        if(this.g > 0) {
          this.g -= this.cFade;
        }
        if(this.b > 0) {
          this.b -= this.cFade;
        }
      }

  // Fill color and stroke
 this.col = "rgba("+(250-this.r)+","+(250-this.g)+","+(250-this.b)+","+this.a+")";
 context.fillStyle = this.col;
 this.stroke = "rgba("+this.r+","+this.g+","+this.b+","+this.a+")";
 context.strokeStyle = this.stroke;

  // Rotation
  context.save();
  context.translate(this.x, this.y);
  context.rotate(this.theta);

  // Speed dependent on hover
  if(zoneOut === false && this.isOver ===true && this.rSpeed <= this.maxrSpeed) {
    if(this.rSpeed < .1) {
      this.rSpeed = .1;
    }
    else {
      this.rSpeed += 0.001;
    }
  }
  else if((this.isOver ===false || zoneOut === true)&& this.rSpeed > 0){
    this.rSpeed-=0.001;
  }
  this.theta += this.rSpeed;

  // If "1" is pressed, generate CROSSES
  if(cross === true) {
    square = false;
    circle = false;

    context.beginPath();
    context.moveTo(-this.size*3, 0);
    context.lineTo(this.size*3, 0);
    context.lineWidth = this.size/10;
    context.stroke();
    context.closePath();

    context.beginPath();
    context.moveTo(0, -this.size*3);
    context.lineTo(0, this.size*3);

    context.lineWidth = this.size/10;
    context.stroke();
  } // end of CROSSES

  // If "2" is pressed, generate CIRCLES
  else if(circle === true) {
    square = false;
    cross = false;
    context.beginPath();
    context.arc(this.size/3,this.size/3,this.size/2,0,2*Math.PI);
    context.lineWidth = this.size/20;
    context.stroke();
  } // end of CIRCLES

  // If "3" is pressed, generate SQUARES
  else if(square === true) {
    cross = false;
    circle = false;
    let step = this.size/7;
    context.fillRect(-this.size/2-step, -this.size/2-step, this.size+step*2, this.size+step*2);

    //draw spiral inside squares
    context.beginPath();
    context.moveTo(-this.size/2, -this.size/2);
    context.lineTo(this.size/2, -this.size/2);
    context.lineTo(this.size/2, this.size/2);
    context.lineTo(-this.size/2, this.size/2);
    context.lineTo(-this.size/2, -this.size/2+step);
    context.lineTo(this.size/2 - step, -this.size/2+step);
    context.lineTo(this.size/2 - step, this.size/2-step);
    context.lineTo(-this.size/2 + step, this.size/2-step);
    context.lineTo(-this.size/2 + step, -this.size/2+step*2);
    context.lineTo(this.size/2 - step*2, -this.size/2+step*2);
    context.lineTo(this.size/2 - step*2, this.size/2-step*2);
    context.lineTo(-this.size/2 + step*2, this.size/2-step*2);
    context.lineTo(-this.size/2 + step*2, -this.size/2+step*3);
    context.lineTo(this.size/2 - step*3, -this.size/2+step*3);
    context.lineTo(this.size/2 - step*3, this.size/2-step*3);
    context.lineTo(-this.size/2 + step*3, this.size/2-step*3);
    context.lineTo(-this.size/2 + step*3, -this.size/2+step*4);
    context.lineWidth = this.size/40;
    context.stroke();
  } // end of SQUARES

    context.restore();
} // end of display function

// Sound-related callback functions:::
this.playSoundIfOver = function(){
  // Sound will happen if mouse is over
  if(this.isOver === true && this.isPressed === false){
    let now =   audioContext.currentTime;
    this.note.offsetDuration = 3;
    this.clampedVal = valClamp(this.freqVal);
    this.note.play(this.clampedVal, now);
//     console.log("freqVal"+this.clampedVal);
  }
}

this.playDistortionIfPressed = function(){
  if(this.isPressed === true){
    let now =  audioContext.currentTime;
    //store old freqVal,
    this.freqVal = this.tempFreqVal;
    this.clampedVal = valClamp(this.freqVal);
    this.note.filterPlay(this.clampedVal,now); // biquad filter
//     console.log("FILTERED freqVal "+this.clampedVal);
  }
}

// callback functions for mouse click, over and move
this.hitTest = function(event){
  if(Math.sqrt(Math.pow((event.clientX-this.x),2)+Math.pow((event.clientY-this.y),2))<this.size){
    // store freqVal before pressdown
    this.tempFreqVal =this.freqVal;
    this.freqVal = this.freqVal+(event.clientX/100);
    this.isPressed = true;
    this.isChanging = true;
  }
} // end of hitTest

this.hitTestUp = function(event){
//     console.log("mouse up");
    this.isPressed = false;
    // put back old tempFreqVal
    this.freqVal = this.tempFreqVal;
}

// Checking if an object or its area are being hovered over. If so, mouseover event occurs (rotation and acceleration) 
// and zoneOut (mouseout window that stops the rotation) turns false
this.hitTestOver = function(event){
  if(Math.sqrt(Math.pow((event.clientX-this.x),2)+Math.pow((event.clientY-this.y),2))<this.size*2){
    // console.log("EID, rect over::: "+this.eID);
    this.isOver = true;
    zoneOut = false;
    //update the freq val
    this.freqVal = this.freqVal+(event.clientX/146.8); //D3,
  }
} // end of hitTestOver

//SABINE:: ADDED
this.hitTestMove = function(event){
  if(Math.sqrt(Math.pow((event.clientX-this.x),2)+Math.pow((event.clientY-this.y),2))<this.size*2){
    this.isOver = true;
    //update the freq val
    this.freqVal = this.freqVal+(event.clientX/146.8); //C4, aka middle C
  } else {
      this.isOver = false;
  }
} // end of hitTestMove callback
} // end of custom object