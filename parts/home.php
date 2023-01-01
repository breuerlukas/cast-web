<div id="home-wrapper">
  <div id="home-headline-wrapper">
    <b id="home-headline">CAST</b></br>
    <a id="home-description">Clever Autonomous Stock Trading</a>
  </div>
  <canvas id="home-dots"></canvas>
</div>

<script>
var canvas = document.getElementById('home-dots');
var canvasWidth = parseInt(canvas.getAttribute('width'));
var canvasHeight = parseInt(canvas.getAttribute('height'));
var context = canvas.getContext('2d');

const BALL_AMOUNT = 100;

var ball = {
  x: 0,
  y: 0,
  vx: 0,
  vy: 0,
  r: 0,
  alpha: 1,
  phase: 0
};
var ball_color = {
  r: 255,
  g: 255,
  b: 255
};
var R = 2;
var balls = [];
var alpha_f = 0.03;
var alpha_phase = 0;
var link_line_width = 0.8;
var dis_limit = 260;

function getRandomSpeed(pos){
  var minimum = -1;
  var maximum = 1;
  switch(pos){
    case 'top':
      return [randomNumFrom(minimum, maximum), randomNumFrom(0.1, maximum)];
      break;
    case 'right':
      return [randomNumFrom(minimum, -0.1), randomNumFrom(minimum, maximum)];
      break;
    case 'bottom':
      return [randomNumFrom(minimum, maximum), randomNumFrom(minimum, -0.1)];
      break;
    case 'left':
      return [randomNumFrom(0.1, maximum), randomNumFrom(minimum, maximum)];
      break;
    default:
      return;
      break;
  }
}

function getRandomBall(){
  var position = randomArrayItem(['top', 'right', 'bottom', 'left']);
  switch(position){
    case 'top':
      return {
        x: randomSidePos(canvasWidth),
        y: -R,
        vx: getRandomSpeed('top')[0],
        vy: getRandomSpeed('top')[1],
        r: R,
        alpha: 1,
        phase: randomNumFrom(0, 10)
      }
      break;
    case 'right':
      return {
        x: canvasWidth + R,
        y: randomSidePos(canvasHeight),
        vx: getRandomSpeed('right')[0],
        vy: getRandomSpeed('right')[1],
        r: R,
        alpha: 1,
        phase: randomNumFrom(0, 10)
      }
      break;
    case 'bottom':
      return {
        x: randomSidePos(canvasWidth),
        y: canvasHeight + R,
        vx: getRandomSpeed('bottom')[0],
        vy: getRandomSpeed('bottom')[1],
        r: R,
        alpha: 1,
        phase: randomNumFrom(0, 10)
      }
      break;
    case 'left':
      return {
        x: -R,
        y: randomSidePos(canvasHeight),
        vx: getRandomSpeed('left')[0],
        vy: getRandomSpeed('left')[1],
        r: R,
        alpha: 1,
        phase: randomNumFrom(0, 10)
      }
      break;
  }
}

function renderBalls(){
  Array.prototype.forEach.call(balls, function(ball){
    if(!ball.hasOwnProperty('type')){
      context.fillStyle = 'rgba(' + ball_color.r + ',' + ball_color.g + ',' + ball_color.b + ',' + ball.alpha + ')';
      context.beginPath();
      context.arc(ball.x, ball.y, R, 0, Math.PI*2, true);
      context.closePath();
      context.fill();
    }
  });
}

function updateBalls(){
  var new_balls = [];
  Array.prototype.forEach.call(balls, function(ball) {
    ball.x += ball.vx;
    ball.y += ball.vy;
    if(ball.x > -(50) && ball.x < (canvasWidth + 50) && ball.y > -(50) && ball.y < (canvasHeight + 50)){
       new_balls.push(ball);
    }
    ball.phase += alpha_f;
    ball.alpha = Math.abs(Math.cos(ball.phase));
  });
  balls = new_balls.slice(0);
}

function renderLines(){
  var fraction, alpha;
  for (var i = 0; i < balls.length; i++) {
    for (var j = i + 1; j < balls.length; j++) {
      fraction = getDisOf(balls[i], balls[j]) / dis_limit;
      if(fraction < 1){
        alpha = (1 - fraction).toString();
        context.strokeStyle = 'rgba(150,150,150,' + alpha + ')';
        context.lineWidth = link_line_width;
        context.beginPath();
        context.moveTo(balls[i].x, balls[i].y);
        context.lineTo(balls[j].x, balls[j].y);
        context.stroke();
        context.closePath();
      }
    }
  }
}

function getDisOf(firstBall, secondBall){
  var delta_x = Math.abs(firstBall.x - secondBall.x);
  var delta_y = Math.abs(firstBall.y - secondBall.y);
  return Math.sqrt(delta_x * delta_x + delta_y * delta_y);
}

function addBallIfy(){
  if(balls.length < BALL_AMOUNT){
    balls.push(getRandomBall());
  }
}

function render(){
  context.clearRect(0, 0, canvasWidth, canvasHeight);
  renderBalls();
  renderLines();
  updateBalls();
  addBallIfy();
  window.requestAnimationFrame(render);
}

function initBalls(num){
  for(var i = 1; i <= num; i++){
    balls.push({
      x: randomSidePos(canvasWidth),
      y: randomSidePos(canvasHeight),
      vx: getRandomSpeed('top')[0],
      vy: getRandomSpeed('top')[1],
      r: R,
      alpha: 1,
      phase: randomNumFrom(0, 10)
    });
  }
}

function initCanvas() {
  if (window.innerWidth <= 400) {
    return;
  }
  canvas.setAttribute('width', window.innerWidth);
  canvas.setAttribute('height', window.innerHeight - document.getElementById('navbar').clientHeight);
  canvasWidth = parseInt(canvas.getAttribute('width'));
  canvasHeight = parseInt(canvas.getAttribute('height'));
}

window.addEventListener('resize', function(e){
  initCanvas();
});

function goMovie(){
  if (window.innerWidth <= 400) {
    return;
  }
  initCanvas();
  initBalls(BALL_AMOUNT);
  window.requestAnimationFrame(render);
}

goMovie();

function randomArrayItem(array){
  return array[Math.floor(Math.random() * array.length)];
}

function randomNumFrom(minimum, maximum){
  return Math.random() * (maximum - minimum) + minimum;
}

function randomSidePos(length){
  return Math.ceil(Math.random() * length);
}
</script>