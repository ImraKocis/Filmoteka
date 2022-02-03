<?php
    get_header();

    $sImageUrl = get_template_directory_uri().'/img/home-bg.jpg';
?>

<!-- Page Header -->
<header class="masthead" style="background-image: url(<?php echo $sImageUrl; ?>)">
<div class="overlay"></div>
<div class="container">
    <div class="row">
    <div class="col-lg-8 col-md-10 mx-auto">
        <div class="site-heading">
        <h1>Obavijesti</h1>
        <span class="subheading"></span>
        </div>
    </div>
    </div>
</div>
</header>
<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6" style="text-align:;"><?php echo DajObjaveObavijesti(); ?></div>
    <div class="col-md-3"></div>
</div>

<?php
    get_footer();
?>