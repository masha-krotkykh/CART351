// Sound Object for Assignment 2
// ASSIGNMENT 2 BY MASHA KROTKYKH & ALESSIA SIGNORINO

/*** class for sound inspired and copy-lefted from::
https://css-tricks.com/introduction-web-audio-api/*/

// linking context from main program
canvas = document.getElementById("testCanvas");
context = canvas.getContext("2d");
audioContext = new (window.AudioContext || window.webkitAudioContext)();

function Sound () {
  this.context = audioContext;
  this.offsetDuration =0;

  // init oscillator
  this.init =function () {
    this.oscillator = this.context.createOscillator();
    //connecting oscillator to gainNode to speakers
    this.gainNode = this.context.createGain();
    this.oscillator.connect(this.gainNode);
    this.gainNode.connect(this.context.destination);
    this.oscillator.type = 'sawtooth';
  }

  // initialize properties for biquad filter
  this.filterInit = function() {
    this.biquadFilter = this.context.createBiquadFilter();
    this.oscillator.connect(this.biquadFilter);
    this.biquadFilter.connect(this.gainNode);
    this.biquadFilter.type = 'lowpass';
    this.biquadFilter.frequency.setValueAtTime(1000, this.context.currentTime);
    this.biquadFilter.gain.setValueAtTime(25, this.context.currentTime);
  }

  this.play = function (value, time) {
    this.init();
    this.oscillator.frequency.value = value;
    //Set gain
    this.gainNode.gain.setValueAtTime(1, this.context.currentTime);
    this.oscillator.start(time);
    // Duration of note
    this.stop(time+this.offsetDuration);
  }

  this.stop = function(time) {
    // gradual change in value
    this.gainNode.gain.exponentialRampToValueAtTime(0.001, time + 1);
    this.oscillator.stop(time + 0);
  }

  this.filterPlay = function (value,time) {
    this.filterInit();
    this.biquadFilter.frequency.value = value;
    this.biquadFilter.frequency.setValueAtTime(1, this.context.currentTime);
    this.stop(time+this.offsetDuration);
  }

}   //end Sound object