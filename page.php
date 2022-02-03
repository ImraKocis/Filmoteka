<?php
    get_header();

    $sImageUrl = get_template_directory_uri().'/img/img_3.jpg';
    echo '
    <!-- Page Header -->
    <header class="masthead" style="background-image: url('.$sImageUrl.')">
    <div class="overlay"></div>
    <div class="container">
        <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <div class="site-heading">
            <h1>'; echo the_title(); echo '</h1>
            <span class="subheading"></span>
            </div>
        </div>
        </div>
    </div>
    </header>';

    if ( have_posts() )
    {
        while ( have_posts() )
        {
            the_post();
            $sIstaknutaSlika = "";
            if( get_the_post_thumbnail_url($post->ID) )
            {
                $sIstaknutaSlika = get_the_post_thumbnail_url($post->ID);
            }
            else
            {
                $sIstaknutaSlika = get_template_directory_uri(). '/img/home-bg.jpg';
            }
            echo '<div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6" style="text-align:;">'.nl2br(the_content()).'</div>
                    <div class="col-md-3"></div>
                </div>';
        }
    }

    get_footer();
?>