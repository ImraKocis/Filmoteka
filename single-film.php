<?php 
    get_header();

    $x=0;
    $user_id = $current_user->ID;
    $username =  $current_user->display_name;
    $film_kolicina = get_post_meta($post->ID, 'kolicina_primjeraka_film', true);
    $redatelj_list = wp_get_post_terms( $post->ID, 'redatelj', array( 'fields' => 'all' ) );
    $glavni_glumci_list = wp_get_post_terms( $post->ID, 'glavni_glumci', array( 'fields' => 'all' ) );
    $ocjena_list = wp_get_post_terms( $post->ID, 'ocjena', array( 'fields' => 'all' ) );
    $godina_list = wp_get_post_terms( $post->ID, 'godina', array( 'fields' => 'all' ) );
    $nagrade_list = wp_get_post_terms( $post->ID, 'nagrada', array( 'fields' => 'all' ) );
    //print_r( $glavni_glumci_list );
    
    
    $sNazivFilma=$post->post_title;
    $sImageUrl = get_template_directory_uri().'/img/home-bg.jpg';
    $functionsUrl = get_template_directory_uri().'/functions.php';
    echo '
    <!-- Page Header -->
    <header class="masthead">
     <div class="overlay"></div>
     <div class="container">
         <div class="row">
         <div class="col-lg-8 col-md-10 mx-auto">
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
                $sIstaknutaSlika = get_template_directory_uri(). '/img/no_image.jpg';
            }
            $PostContent=get_the_content();
            //$arr_of_data = daj_podatke_admin();
            
            if(is_user_logged_in()){
                require_once(get_template_directory().'/tamplates/tamplate-single-movie-loged.php');
            }else{
                require_once(get_template_directory().'/tamplates/tamplate-single-movie-logedout.php');
            }  
        }
    }
    
?>
<script language="JavaScript">
$(document).ready(function() {
    var dostupna_kolicina = "<?php echo $film_kolicina ?>";
            $('.buttonPosudi').click(function () {
                var broj_artikala=1;
                var potvrda = false;
                var dostupnost=(dostupna_kolicina-broj_artikala);
                var test =document.getElementById("nedovoljnofilmova").style.display="block";
                var test2 =document.getElementById("nedovoljnofilmova").style.display="none";
                var slika="<?php echo $sIstaknutaSlika ?>";
                var userPreference;
		        
                if(dostupna_kolicina>0 && dostupnost>=0 ){
                    if (confirm("Potvrdite akciju posuđivanja") == true) {
                        userPreference = "Film uspješno posuđen!";
                        potvrda = true;
                    }else userPreference = "Posuđivanje otkazano";
                    alert(userPreference);

                    if(potvrda){
                            $.ajax({
                            type: 'POST',
                            url: ajaxurl,
                            data: {
                                action: 'custom_update_post',
                                post_id: <?php echo $post->ID?>,
                                naziv_filma : "<?php echo $post->post_title?>",
                                slika_filma : slika,
                                nova_kolicina : dostupnost,
                                odabrana_kolicina : broj_artikala,
                                user_id : "<?php echo $current_user->ID ?>",
                                username : "<?php echo $current_user->display_name ?>",
                            }
                        });  
                    }
                }
                else{
                    document.getElementById("nedovoljnofilmova").style.display="block";
                    setTimeout(function () {
                    window.location = window.location;
                    }, 3000);  
                }
            return false;
            });
    });
</script>

<?php
get_footer(); 
?>