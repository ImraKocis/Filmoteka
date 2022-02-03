<?php
    get_header();
    $sImageUrl = get_template_directory_uri().'/img/serija-cover_2.jpg';
?>
    <header class="masthead" style="background-image: url(<?php echo $sImageUrl; ?>)">
        <div class="overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-md-10 mx-auto">
                        <div class="site-heading">
                        <h1>SERIJE</h1>
                        </div>
                    </div>
                </div>
            </div>
    </header>
 <main>
<?php
    echo daj_serije();
?>
 </main>
<?php
    get_footer();
?>
