<?php
    get_header();

    $sImageUrl = get_template_directory_uri().'/img/img_3.jpg';

?>
    <!-- Page Header -->
    <header class="masthead" style="background-image: url(<?php echo $sImageUrl; ?>)">
    <div class="overlay"></div>
    <div class="container">
        <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <div class="site-heading">
            <h1><?php echo the_title(); ?></h1>
            <span class="subheading"></span>
            </div>
        </div>
        </div>
    </div>
    </header>

    <?php
    $sPostContent = "";
    if ( have_posts() )
    {
        while ( have_posts() )
        {
            the_post();
              
            $sPostContent = nl2br($post->post_content);        
        }
    }
?>
    <div class="row">
        <div class="col" style="text-align:center;">
            <div class="col">
                <div class="col">
                    <div class="col">
                        <?php echo $sPostContent; ?>
                    </div>
                </div>
            </div>
        </div>    
    </div>

<?php
    get_footer();
?>