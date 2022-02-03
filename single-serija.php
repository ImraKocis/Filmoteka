<?php 
    get_header();

    $x=0;
    $y=0;
    $user_id = $current_user->ID;
    $username =  $current_user->display_name;
    $serija_kolicina = get_post_meta($post->ID, 'kolicina_primjeraka_serija', true);
    $glavni_glumci_list = wp_get_post_terms( $post->ID, 'glavni_glumci', array( 'fields' => 'all' ) );
    $broj_sezona = wp_get_post_terms( $post->ID, 'broj_sezona', array( 'fields' => 'all' ) );
    $ocjena = wp_get_post_terms( $post->ID, 'ocjena', array( 'fields' => 'all' ) );
    $epizode_list = wp_get_post_terms( $post->ID, 'epizoda', array( 'fields' => 'all' ) );
    $zanr = wp_get_post_terms( $post->ID, 'zanr', array( 'fields' => 'all' ) );
    print_r($broj_sezona);
    $sNazivSerije=$post->post_title;
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
            
            
            if(is_user_logged_in()){
                require_once (get_template_directory(). '/tamplates/tamplate-single-serija-loged.php');
            }else{
                require_once (get_template_directory(). '/tamplates/tamplate-single-serija-logedout.php');
            } 
        }
    }
    
?>
<script language="JavaScript">
$(document).ready(function() {
    var dostupna_kolicina = "<?php echo $serija_kolicina ?>";
            $('.buttonPosudi').click(function () {
                var broj_artikala=1;
                var potvrda = false;
                var dostupnost=(dostupna_kolicina-broj_artikala);
                var slika="<?php echo $sIstaknutaSlika ?>";
                var userPreference;
 
                if(dostupna_kolicina>0 && dostupnost>=0 ){
                    if (confirm("Potvrdite akciju posuđivanja") == true) {
                        userPreference = "Serija uspješno posuđen!";
                        potvrda = true;
                    }else userPreference = "Posuđivanje otkazano";

                    alert(userPreference);
                    if(potvrda){
                        $.ajax({
                            type: 'POST',
                            url: ajaxurl,
                            data: {
                                action: 'custom_update_post_serija',
                                post_id: <?php echo $post->ID?>,
                                naziv_serije : "<?php echo $post->post_title?>",
                                slika_serije : slika,
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