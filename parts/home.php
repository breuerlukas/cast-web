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
var add_mouse_point = true;
var mouse_in = false;
var mouse_ball = {
    x: 0,
    y: 0,
    vx: 0,
    vy: 0,
    r: 0,
    type: 'mouse'
};

function getRandomSpeed(pos){
    var min = -1;
    var max = 1;
    switch(pos){
        case 'top':
            return [randomNumFrom(min, max), randomNumFrom(0.1, max)];
            break;
        case 'right':
            return [randomNumFrom(min, -0.1), randomNumFrom(min, max)];
            break;
        case 'bottom':
            return [randomNumFrom(min, max), randomNumFrom(min, -0.1)];
            break;
        case 'left':
            return [randomNumFrom(0.1, max), randomNumFrom(min, max)];
            break;
        default:
            return;
            break;
    }
}

function getRandomBall(){
    var pos = randomArrayItem(['top', 'right', 'bottom', 'left']);
    switch(pos){
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
    Array.prototype.forEach.call(balls, function(b){
       if(!b.hasOwnProperty('type')){
           context.fillStyle = 'rgba('+ball_color.r+','+ball_color.g+','+ball_color.b+','+b.alpha+')';
           context.beginPath();
           context.arc(b.x, b.y, R, 0, Math.PI*2, true);
           context.closePath();
           context.fill();
       }
    });
}

function updateBalls(){
    var new_balls = [];
    Array.prototype.forEach.call(balls, function(b){
        b.x += b.vx;
        b.y += b.vy;

        if(b.x > -(50) && b.x < (canvasWidth+50) && b.y > -(50) && b.y < (canvasHeight+50)){
           new_balls.push(b);
        }

        b.phase += alpha_f;
        b.alpha = Math.abs(Math.cos(b.phase));
    });

    balls = new_balls.slice(0);
}

function loopAlphaInf(){

}

function renderLines(){
    var fraction, alpha;
    for (var i = 0; i < balls.length; i++) {
        for (var j = i + 1; j < balls.length; j++) {

           fraction = getDisOf(balls[i], balls[j]) / dis_limit;

           if(fraction < 1){
               alpha = (1 - fraction).toString();

               context.strokeStyle = 'rgba(150,150,150,'+alpha+')';
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

function getDisOf(b1, b2){
    var delta_x = Math.abs(b1.x - b2.x);
    var delta_y = Math.abs(b1.y - b2.y);

    return Math.sqrt(delta_x*delta_x + delta_y*delta_y);
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

canvas.addEventListener('mouseenter', function() {
    mouse_in = true;
    balls.push(mouse_ball);
});

canvas.addEventListener('mouseleave', function() {
    mouse_in = false;
    var new_balls = [];
    Array.prototype.forEach.call(balls, function(b) {
        if(!b.hasOwnProperty('type')){
            new_balls.push(b);
        }
    });
    balls = new_balls.slice(0);
});

canvas.addEventListener('mousemove', function(e){
    var e = e || window.event;
    mouse_ball.x = e.pageX;
    mouse_ball.y = e.pageY;
});

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