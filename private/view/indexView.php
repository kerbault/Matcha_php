<?php $title = 'Matcha'; ?>

<?php ob_start(); ?>

<div class="jumbotron text-center">
    <h1 class="display-3">Matcha</h1>
    <hr class="my-4">
    <p class="lead">This is Matcha, a well designed dating app</p>
    <p>Jump ahead and find your soul mate</p>
    <p class="lead">
        <a class="btn btn-primary btn-lx" href="/signup" role="button">Sign up</a>
    <a>or</a>
        <a class="btn btn-primary btn-lx" href="/login" role="button">Login</a>
    </p>
</div>
<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img class="img-fluid" src="https://picsum.photos/3001/450/?random" alt="First slide">
        </div>
        <div class="carousel-item">
            <img class="img-fluid" src="https://picsum.photos/3000/450/?random" alt="Second slide">
        </div>
        <div class="carousel-item">
            <img class="img-fluid" src="https://picsum.photos/2999/450/?random" alt="Third slide">
        </div>
    </div>
    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
<div class="container mt-3">
    <h2>We have about</h2>
    <dl class="ml-4">
        <dd>- 6521 users registered</dd>
        <dd>- 1252 of them have found their soul mate</dd>
        <dd>- 862 are online</dd>
        <dd>- 65 are chatting right now</dd>
    </dl>
</div>
<div class="container mt-3">
    <h2 class="pt-4">Our users</h2>
    <p class="section-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugit, error amet
        numquam iure provident voluptate esse quasi, veritatis totam voluptas nostrum quisquam eum porro a pariatur
        accusamus veniam.</p>
    <div class="row">
        <div class="col-lg-4 col-md-12 mb-4">
            <div class="card testimonial-card">
                <div class="card-up teal lighten-2">
                </div>
                <div class="avatar mx-auto white"><img
                            src="https://www.eatingdisorderhope.com/wp-content/uploads/2015/04/Fotolia_81104552_Subscription_Monthly_XL-250x250.jpg"
                            alt="avatar mx-auto white" class="img-fluid mt-3">
                </div>
                <div class="card-body">
                    <h4 class="card-title mt-1">John Doe - 22</h4>
                    <hr>
                    <p><i class="fas fa-quote-left"></i> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eos,
                        adipisci.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12 mb-4">
            <div class="card testimonial-card">
                <div class="card-up blue lighten-2">
                </div>
                <div class="avatar mx-auto white"><img src="https://mdbootstrap.com/img/Photos/Avatars/img%20(20).jpg"
                                                       alt="avatar mx-auto white" class="img-fluid mt-3">
                </div>
                <div class="card-body">
                    <h4 class="card-title mt-1">Anna Aston - 26</h4>
                    <hr>
                    <p><i class="fas fa-quote-left"></i> Neque cupiditate assumenda in maiores repudiandae mollitia
                        architecto.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12 mb-4">
            <div class="card testimonial-card">
                <div class="card-up deep-purple lighten-2"></div>
                <div class="avatar mx-auto white"><img src="https://mdbootstrap.com/img/Photos/Avatars/img%20(10).jpg"
                                                       alt="avatar mx-auto white" class="img-fluid mt-3">
                </div>
                <div class="card-body">
                    <h4 class="card-title mt-1">Maria Kate - 21</h4>
                    <hr>
                    <p><i class="fas fa-quote-left"></i> Delectus impedit saepe officiis ab aliquam repellat, rem totam
                        unde ducimus.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>
