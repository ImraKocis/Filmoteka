<?php
    get_header();
    global $wp;
    $url = home_url( $wp->request );
    $url_arr = explode('/',$url);
    $terms = get_terms( array(
        'taxonomy' => 'zanr',
        'hide_empty' => false,
    ) );
    foreach($url_arr as $s){
        $zanr_slug = $s;   
    }
    foreach($terms as $term){
        if($zanr_slug == $term->slug){
            $zanr_name = $term->name;
        }
    }
    //img name in dir ${taxonomy-slug}-cover.jpg
    //best resolution is 1600x900
    //if img url name is different than upper, you need to change path manually!!
    $sImageUrl = get_template_directory_uri().'/img/'.$zanr_slug.'-cover.jpg';
?>
    <header class="masthead" style="background-image: url(<?php echo $sImageUrl; ?>)">
        <div class="overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-md-10 mx-auto">
                        <div class="site-heading">
                        <h1><?php echo $zanr_name ?></h1>
                        </div>
                    </div>
                </div>
            </div>
    </header>
 <main>
 <?php
 echo daj_filmove($zanr_slug); ?>
 </main>
<?php
    get_footer();
?>
