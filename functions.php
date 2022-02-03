<?php
// if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
// 	require_once dirname( __FILE__ ) . '/cmb2/init.php';
// } elseif ( file_exists( dirname( __FILE__ ) . '/CMB2/init.php' ) ) {
// 	require_once dirname( __FILE__ ) . '/CMB2/init.php';
// }
//INIT TEME

require get_template_directory() . '/inc/function-admin.php';
if ( ! function_exists( 'inicijaliziraj_temu' ) )
{
	function inicijaliziraj_temu()
	{
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'title-tag' );
		register_nav_menus( array(
			'glavni-menu' => "Glavni navigacijski izbornik",
			'sporedni-menu' => "Izbornik u podnožju",
		) );
		add_theme_support( 'custom-background', apply_filters
			(
				'test_custom_background_args', array
				(
					'default-color' => 'ffffff',
					'default-image' => '',
				) 	
			) 
		);
		add_theme_support( 'customize-selective-refresh-widgets' );
	}
}
add_action( 'after_setup_theme', 'inicijaliziraj_temu' );

//Admin-ajax
add_action('wp_head','my_ajaxurl');
function my_ajaxurl() {
	$html = '<script type="text/javascript">';
	$html .= 'var ajaxurl = "' . admin_url( 'admin-ajax.php' ) . '"';
	$html .= '</script>';
	echo $html;
}

function add_login_logout_menu($items, $args){
    ob_start();
    global $wp;
        $current_url = home_url( add_query_arg( array(), $wp->request ) );
        if ( is_user_logged_in() ) {
            // Logout.
            $items .= sprintf( '<li class="menu-item"><a href="%s">Odjava</a></li>', esc_url( wp_logout_url( $current_url ) ) );
        } else {
            // Login.
            $items .= sprintf( '<li class="menu-item"><a href="%s">Prijava</a></li>', esc_url( wp_login_url( $current_url ) ) );
        }
    return $items;
}
add_filter('wp_nav_menu_items','add_login_logout_menu',10,2);
	

//Azuriranje metaboxa za kolicinu filma
function custom_update_post() {
	global $wpdb;
    $post_id = $_POST['post_id'];
    $item_name = $_POST['naziv_filma'];
    $item_picture = $_POST['slika_filma'];
    $new_meta_value = $_POST['nova_kolicina'];
	$user_id = $_POST['user_id'];
	$username = $_POST['username'];
	$d=strtotime("now");
	$d1=strtotime('+5 Days');
	$datumPosudivanja=date("d.m.Y.", $d);
	$rokVracanja = date("d.m.Y.", $d1);
	$sql = $wpdb->prepare("INSERT INTO `admin` (`userID`,`username`,`nazivPosudenogFilma`,`filmID`,`datumPosudbe`,`rokVracanja`) values (%s,%s,%s,%s,%s,%s)",$user_id,$username,$item_name,$post_id,$datumPosudivanja,$rokVracanja);
	$wpdb->query($sql);
    update_post_meta( $post_id, 'kolicina_primjeraka_film', $new_meta_value );
	
}
add_action( 'wp_ajax_custom_update_post', 'custom_update_post' );
function custom_update_post_serija(){
	global $wpdb;
    $post_id = $_POST['post_id'];
    $item_name = $_POST['naziv_serije'];
    $item_picture = $_POST['slika_serije'];
    $new_meta_value = $_POST['nova_kolicina'];
	$user_id = $_POST['user_id'];
	$username = $_POST['username'];
	$d=strtotime("now");
	$d1=strtotime('+20 Days');
	$datumPosudivanja=date("d.m.Y.", $d);
	$rokVracanja = date("d.m.Y.", $d1);
	$sql = $wpdb->prepare("INSERT INTO `admin` (`userID`,`username`,`nazivPosudenogFilma`,`filmID`,`datumPosudbe`,`rokVracanja`) values (%s,%s,%s,%s,%s,%s)",$user_id,$username,$item_name,$post_id,$datumPosudivanja,$rokVracanja);
	$wpdb->query($sql);
    update_post_meta( $post_id, 'kolicina_primjeraka_serija', $new_meta_value );
}
add_action( 'wp_ajax_custom_update_post_serija', 'custom_update_post_serija' );

function custom_update_post_emisija(){
	global $wpdb;
    $post_id = $_POST['post_id'];
    $item_name = $_POST['naziv_emisije'];
    $item_picture = $_POST['slika_emisije'];
    $new_meta_value = $_POST['nova_kolicina'];
	$user_id = $_POST['user_id'];
	$username = $_POST['username'];
	$d=strtotime("now");
	$d1=strtotime('+5 Days');
	$datumPosudivanja=date("d.m.Y.", $d);
	$rokVracanja = date("d.m.Y.", $d1);
	$sql = $wpdb->prepare("INSERT INTO `admin` (`userID`,`username`,`nazivPosudenogFilma`,`filmID`,`datumPosudbe`,`rokVracanja`) values (%s,%s,%s,%s,%s,%s)",$user_id,$username,$item_name,$post_id,$datumPosudivanja,$rokVracanja);
	$wpdb->query($sql);
    update_post_meta( $post_id, 'kolicina_primjeraka_emisije', $new_meta_value );
}
add_action( 'wp_ajax_custom_update_post_emisija', 'custom_update_post_emisija' );
function admin_vrati_film(){
	global $wpdb;
	$id = $_POST['id_admin_table'];
	$wpdb->query('DELETE  FROM '.$wpdb->prefix.'admin WHERE id = "'.$id.'"');
}

add_action( 'wp_ajax_admin_vrati_film', 'admin_vrati_film' );

function daj_podatke_admin(){
	global $wpdb;
	$dataArray = $wpdb->get_results('SELECT nazivPosudenogFilma, COUNT(*) as brojPosudenih from admin GROUP BY nazivPosudenogFilma');
	return $dataArray;
}

function daj_podatke_admin_sve(){
	global $wpdb;
	$dataArrayAll = $wpdb->get_results('SELECT * FROM admin');
	return $dataArrayAll;
}


// Register Custom Post Type
function registriraj_film_cpt() {

    $labels = array(
        'name'                  => _x( 'Filmovi', 'Post Type General Name', 'vuv' ),
        'singular_name'         => _x( 'Film', 'Post Type Singular Name', 'vuv' ),
        'menu_name'             => __( 'Filmovi', 'vuv' ),
        'name_admin_bar'        => __( 'Filmovi', 'vuv' ),
        'archives'              => __( 'Filmovi arhiva', 'vuv' ),
        'attributes'            => __( 'Atributi', 'vuv' ),
        'parent_item_colon'     => __( 'Roditeljski element', 'vuv' ),
        'all_items'             => __( 'Svi filmovi', 'vuv' ),
        'add_new_item'          => __( 'Dodaj novi film', 'vuv' ),
        'add_new'               => __( 'Dodaj novi', 'vuv' ),
        'new_item'              => __( 'Novi film', 'vuv' ),
        'edit_item'             => __( 'Uredi film', 'vuv' ),
        'update_item'           => __( 'Ažuriraj film', 'vuv' ),
        'view_item'             => __( 'Pregledaj film', 'vuv' ),
        'view_items'            => __( 'Pregledaj filmove', 'vuv' ),
        'search_items'          => __( 'Pretraži film', 'vuv' ),
        'not_found'             => __( 'Nije pronađeno', 'vuv' ),
        'not_found_in_trash'    => __( 'Nije pronađeo u smeću', 'vuv' ),
        'featured_image'        => __( 'Glavna slika', 'vuv' ),
        'set_featured_image'    => __( 'Postavi glavnu sliku', 'vuv' ),
        'remove_featured_image' => __( 'Ukloni glavnu sliku', 'vuv' ),
        'use_featured_image'    => __( 'Postai za glavnu sliku', 'vuv' ),
        'insert_into_item'      => __( 'Umetni', 'vuv' ),
        'uploaded_to_this_item' => __( 'Preneseno', 'vuv' ),
        'items_list'            => __( 'Lista', 'vuv' ),
        'items_list_navigation' => __( 'Navigacija među filmovima', 'vuv' ),
        'filter_items_list'     => __( 'Filtriranje filmova', 'vuv' ),
    );
    $args = array(
        'label'                 => __( 'Film', 'vuv' ),
        'description'           => __( 'Film post type', 'vuv' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor','thumbnail' ),
        'taxonomies'            => array( 'zanr', 'godina', 'ocjena','redatelj', 'glavni_glumci' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => true,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
    );
    register_post_type( 'film', $args );

}
add_action( 'init', 'registriraj_film_cpt', 0 );

// Register Custom Post Type
function registriraj_cpt_serija() {

	$labels = array(
		'name'                  => _x( 'Serije', 'Post Type General Name', 'vuv' ),
		'singular_name'         => _x( 'Serija', 'Post Type Singular Name', 'vuv' ),
		'menu_name'             => __( 'Serije', 'vuv' ),
		'name_admin_bar'        => __( 'Serija', 'vuv' ),
		'archives'              => __( 'Serija arhiva', 'vuv' ),
		'attributes'            => __( 'Atributi', 'vuv' ),
		'parent_item_colon'     => __( 'Parent Item:', 'vuv' ),
		'all_items'             => __( 'Sve serije', 'vuv' ),
		'add_new_item'          => __( 'Dodaj novu seriju', 'vuv' ),
		'add_new'               => __( 'Dodaj novu', 'vuv' ),
		'new_item'              => __( 'Nova serija', 'vuv' ),
		'edit_item'             => __( 'Uredi seriju', 'vuv' ),
		'update_item'           => __( 'Azuriraj seriju', 'vuv' ),
		'view_item'             => __( 'Pregledaj seriju', 'vuv' ),
		'view_items'            => __( 'Pregledaj serije', 'vuv' ),
		'search_items'          => __( 'Pretrazi seriju', 'vuv' ),
		'not_found'             => __( 'Nije poranađeno', 'vuv' ),
		'not_found_in_trash'    => __( 'Nije pronađeno u smeću', 'vuv' ),
		'featured_image'        => __( 'Glavna slika', 'vuv' ),
		'set_featured_image'    => __( 'Postavi glavnu sliku', 'vuv' ),
		'remove_featured_image' => __( 'Ukloni glavnu sliku', 'vuv' ),
		'use_featured_image'    => __( 'Koristi kao glavnu sliku', 'vuv' ),
		'insert_into_item'      => __( 'Umeti', 'vuv' ),
		'uploaded_to_this_item' => __( 'Preneseno', 'vuv' ),
		'items_list'            => __( 'Lista', 'vuv' ),
		'items_list_navigation' => __( 'Navigacija među serijama', 'vuv' ),
		'filter_items_list'     => __( 'Filtriraj serije', 'vuv' ),
	);
	$args = array(
		'label'                 => __( 'Serija', 'vuv' ),
		'description'           => __( 'Post Type Description', 'vuv' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor','thumbnail' ),
		'taxonomies'            => array( 'glavni_glumci', 'zanr','sezona','epizoda', 'broj_sezona', 'ocjena' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'serija', $args );

}
add_action( 'init', 'registriraj_cpt_serija', 0 );

// Register Custom Post Type
function registriraj_cpt_emisija() {

	$labels = array(
		'name'                  => _x( 'Emisije', 'Post Type General Name', 'vuv' ),
		'singular_name'         => _x( 'Emisija', 'Post Type Singular Name', 'vuv' ),
		'menu_name'             => __( 'Emisije', 'vuv' ),
		'name_admin_bar'        => __( 'Emisija', 'vuv' ),
		'archives'              => __( 'Emisija arhiva', 'vuv' ),
		'attributes'            => __( 'Atributi', 'vuv' ),
		'parent_item_colon'     => __( 'Parent Item:', 'vuv' ),
		'all_items'             => __( 'Sve emisije', 'vuv' ),
		'add_new_item'          => __( 'Dodaj novu emsiju', 'vuv' ),
		'add_new'               => __( 'Dodaj novu', 'vuv' ),
		'new_item'              => __( 'Nova emisija', 'vuv' ),
		'edit_item'             => __( 'Uredi emisiju', 'vuv' ),
		'update_item'           => __( 'Azuriraj emisiju', 'vuv' ),
		'view_item'             => __( 'Pregledaj emisiju', 'vuv' ),
		'view_items'            => __( 'Pregledaj emisije', 'vuv' ),
		'search_items'          => __( 'Pretrazi emisiju', 'vuv' ),
		'not_found'             => __( 'Nije poranađeno', 'vuv' ),
		'not_found_in_trash'    => __( 'Nije pronađeno u smeću', 'vuv' ),
		'featured_image'        => __( 'Glavna slika', 'vuv' ),
		'set_featured_image'    => __( 'Postavi glavnu sliku', 'vuv' ),
		'remove_featured_image' => __( 'Ukloni glavnu sliku', 'vuv' ),
		'use_featured_image'    => __( 'Koristi kao glavnu sliku', 'vuv' ),
		'insert_into_item'      => __( 'Umeti', 'vuv' ),
		'uploaded_to_this_item' => __( 'Preneseno', 'vuv' ),
		'items_list'            => __( 'Lista', 'vuv' ),
		'items_list_navigation' => __( 'Navigacija među emisijama', 'vuv' ),
		'filter_items_list'     => __( 'Filtriraj emisije', 'vuv' ),
	);
	$args = array(
		'label'                 => __( 'Emisija', 'vuv' ),
		'description'           => __( 'Post Type Description', 'vuv' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor','thumbnail' ),
		'taxonomies'            => array( 'vrsta_emisije', 'voditelji', 'gosti', 'natjecatelj', 'ziri' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'emisija', $args );

}
add_action( 'init', 'registriraj_cpt_emisija', 0 );

// Register Custom Taxonomy
function registriraj_taksonomiju_redatelj() {

    $labels = array(
        'name'                       => _x( 'Redatelji', 'Taxonomy General Name', 'vuv' ),
        'singular_name'              => _x( 'Redatelj', 'Taxonomy Singular Name', 'vuv' ),
        'menu_name'                  => __( 'Redatelj', 'vuv' ),
        'all_items'                  => __( 'Redatelji', 'vuv' ),
        'parent_item'                => __( '', 'vuv' ),
        'parent_item_colon'          => __( '', 'vuv' ),
        'new_item_name'              => __( 'Novi redatelj', 'vuv' ),
        'add_new_item'               => __( 'Dodaj redatelja', 'vuv' ),
        'edit_item'                  => __( 'Uredi redatelja', 'vuv' ),
        'update_item'                => __( 'Ažuriraj redatelja', 'vuv' ),
        'view_item'                  => __( 'Pregledaj redatelja', 'vuv' ),
        'separate_items_with_commas' => __( 'Odvojite redatelje zarezom', 'vuv' ),
        'add_or_remove_items'        => __( 'Dodaj ili ukloni redatelja', 'vuv' ),
        'choose_from_most_used'      => __( 'Odaberi iz najčešće korišteima', 'vuv' ),
        'popular_items'              => __( 'Populatni redatelji', 'vuv' ),
        'search_items'               => __( 'Pretraži redatelje', 'vuv' ),
        'not_found'                  => __( 'Nije pronađeno', 'vuv' ),
        'no_terms'                   => __( 'Nema rezultata', 'vuv' ),
        'items_list'                 => __( 'Lista redatelja', 'vuv' ),
        'items_list_navigation'      => __( 'Navigacija', 'vuv' ),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
    );
    register_taxonomy( 'redatelj', array( 'film' ), $args );

}
add_action( 'init', 'registriraj_taksonomiju_redatelj', 0 );

// Register Custom Taxonomy
function registriraj_taksonomiju_zanr() {

    $labels = array(
        'name'                       => _x( 'Žanr', 'Taxonomy General Name', 'vuv' ),
        'singular_name'              => _x( 'Žanr', 'Taxonomy Singular Name', 'vuv' ),
        'menu_name'                  => __( 'Žanrovi', 'vuv' ),
        'all_items'                  => __( 'Svi žanrovi', 'vuv' ),
        'parent_item'                => __( 'Roditeljski žanr', 'vuv' ),
        'parent_item_colon'          => __( 'Roditeljski žanr', 'vuv' ),
        'new_item_name'              => __( 'Novi žanr', 'vuv' ),
        'add_new_item'               => __( 'Dodaj novi žanr', 'vuv' ),
        'edit_item'                  => __( 'Uredi žanr', 'vuv' ),
        'update_item'                => __( 'Ažuriraj žanr', 'vuv' ),
        'view_item'                  => __( 'Pregledaj žanr', 'vuv' ),
        'separate_items_with_commas' => __( 'Odvojite žanrove s zarezom', 'vuv' ),
        'add_or_remove_items'        => __( 'Dodaj ili ukloni žanr', 'vuv' ),
        'choose_from_most_used'      => __( 'Odaberi među najčesće korištenima', 'vuv' ),
        'popular_items'              => __( 'Popularni žanrovi', 'vuv' ),
        'search_items'               => __( 'Pretraži žanr', 'vuv' ),
        'not_found'                  => __( 'Nema rezultata', 'vuv' ),
        'no_terms'                   => __( 'Nema žanrova', 'vuv' ),
        'items_list'                 => __( 'Lista žanrova', 'vuv' ),
        'items_list_navigation'      => __( 'Navigacija', 'vuv' ),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
    );
    register_taxonomy( 'zanr', array( 'film', 'serija' ), $args );

}
add_action( 'init', 'registriraj_taksonomiju_zanr', 0 );

// Register Custom Taxonomy
function registriraj_taksonomiju_godine() {

    $labels = array(
        'name'                       => _x( 'Godine', 'Taxonomy General Name', 'vuv' ),
        'singular_name'              => _x( 'Godina', 'Taxonomy Singular Name', 'vuv' ),
        'menu_name'                  => __( 'Godina snimanja', 'vuv' ),
        'all_items'                  => __( 'Sve godine', 'vuv' ),
        'parent_item'                => __( '', 'vuv' ),
        'parent_item_colon'          => __( '', 'vuv' ),
        'new_item_name'              => __( 'Nova godina', 'vuv' ),
        'add_new_item'               => __( 'Dodaj novu godinu', 'vuv' ),
        'edit_item'                  => __( 'Uredi godnu', 'vuv' ),
        'update_item'                => __( 'Ažuriraj godinu', 'vuv' ),
        'view_item'                  => __( 'Pregledaj godinu', 'vuv' ),
        'separate_items_with_commas' => __( 'Odvojite godine s zarezom', 'vuv' ),
        'add_or_remove_items'        => __( 'Dodaj ili ukloni godinu', 'vuv' ),
        'choose_from_most_used'      => __( 'Odaberi među najčesće korištenima', 'vuv' ),
        'popular_items'              => __( 'Popularne godine', 'vuv' ),
        'search_items'               => __( 'Pretraži godinu', 'vuv' ),
        'not_found'                  => __( 'Nema rezultata', 'vuv' ),
        'no_terms'                   => __( 'Nema godine', 'vuv' ),
        'items_list'                 => __( 'Lista godina', 'vuv' ),
        'items_list_navigation'      => __( 'Navigacija', 'vuv' ),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
    );
    register_taxonomy( 'godina', array( 'film' ), $args );

}
add_action( 'init', 'registriraj_taksonomiju_godine', 0 );

// Register Custom Taxonomy
function registriraj_taksonomiju_ocjena() {

    $labels = array(
        'name'                       => _x( 'Ocjene', 'Taxonomy General Name', 'vuv' ),
        'singular_name'              => _x( 'Ocjena', 'Taxonomy Singular Name', 'vuv' ),
        'menu_name'                  => __( 'Ocjena', 'vuv' ),
        'all_items'                  => __( 'Ocjene', 'vuv' ),
        'parent_item'                => __( '', 'vuv' ),
        'parent_item_colon'          => __( '', 'vuv' ),
        'new_item_name'              => __( 'Nova ocjena', 'vuv' ),
        'add_new_item'               => __( 'Dodaj ocjenu', 'vuv' ),
        'edit_item'                  => __( 'Uredi ocjenu', 'vuv' ),
        'update_item'                => __( 'Ažuriraj ocjenu', 'vuv' ),
        'view_item'                  => __( 'Pregledaj ocjenu', 'vuv' ),
        'separate_items_with_commas' => __( 'Odvojite ocjene zarezom', 'vuv' ),
        'add_or_remove_items'        => __( 'Dodaj ili ukloni ocjenu', 'vuv' ),
        'choose_from_most_used'      => __( 'Odaberi iz najčešće korišteima', 'vuv' ),
        'popular_items'              => __( 'Populatne ocjenje', 'vuv' ),
        'search_items'               => __( 'Pretraži ocjene', 'vuv' ),
        'not_found'                  => __( 'Nije pronađeno', 'vuv' ),
        'no_terms'                   => __( 'Nema rezultata', 'vuv' ),
        'items_list'                 => __( 'Lista ocjena', 'vuv' ),
        'items_list_navigation'      => __( 'Navigacija', 'vuv' ),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
    );
    register_taxonomy( 'ocjena', array( 'film','serija' ), $args );

}
add_action( 'init', 'registriraj_taksonomiju_ocjena', 0 );

// Register Custom Taxonomy
function registriraj_taksonomiju_nagrade() {

    $labels = array(
        'name'                       => _x( 'Nagrade', 'Taxonomy General Name', 'vuv' ),
        'singular_name'              => _x( 'Nagrada', 'Taxonomy Singular Name', 'vuv' ),
        'menu_name'                  => __( 'Nagrade', 'vuv' ),
        'all_items'                  => __( 'Sve nagrade', 'vuv' ),
        'parent_item'                => __( '', 'vuv' ),
        'parent_item_colon'          => __( '', 'vuv' ),
        'new_item_name'              => __( 'Nova nagrada', 'vuv' ),
        'add_new_item'               => __( 'Dodaj novu nagradu', 'vuv' ),
        'edit_item'                  => __( 'Uredi nagradu', 'vuv' ),
        'update_item'                => __( 'Ažuriraj nagradu', 'vuv' ),
        'view_item'                  => __( 'Pregledaj nagradu', 'vuv' ),
        'separate_items_with_commas' => __( 'Odvojite nagrade s zarezom', 'vuv' ),
        'add_or_remove_items'        => __( 'Dodaj ili ukloni nagradu', 'vuv' ),
        'choose_from_most_used'      => __( 'Odaberi među najčesće korištenima', 'vuv' ),
        'popular_items'              => __( 'Popularne nagrade', 'vuv' ),
        'search_items'               => __( 'Pretraži nagradu', 'vuv' ),
        'not_found'                  => __( 'Nema rezultata', 'vuv' ),
        'no_terms'                   => __( 'Nema nagrade', 'vuv' ),
        'items_list'                 => __( 'Lista nagrada', 'vuv' ),
        'items_list_navigation'      => __( 'Navigacija', 'vuv' ),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
    );
    register_taxonomy( 'nagrada', array( 'film' ), $args );

}
add_action( 'init', 'registriraj_taksonomiju_nagrade', 0 );
// Register Custom Taxonomy
function registriraj_taksonomiju_glavni_glumci() {

	$labels = array(
		'name'                       => _x( 'Glavni glumci', 'Taxonomy General Name', 'vuv' ),
		'singular_name'              => _x( 'Glavni glumci', 'Taxonomy Singular Name', 'vuv' ),
		'menu_name'                  => __( 'Glavni glumci', 'vuv' ),
		'all_items'                  => __( 'Glavni glumci', 'vuv' ),
		'parent_item'                => __( 'Parent Item', 'vuv' ),
		'parent_item_colon'          => __( 'Parent Item:', 'vuv' ),
		'new_item_name'              => __( 'Novi glumac', 'vuv' ),
		'add_new_item'               => __( 'Dodaj novog glumca', 'vuv' ),
		'edit_item'                  => __( 'Uredi glavne glumce', 'vuv' ),
		'update_item'                => __( 'Azuriraj glavne glumce', 'vuv' ),
		'view_item'                  => __( 'Pregledaj', 'vuv' ),
		'separate_items_with_commas' => __( 'Odvoji s zarezom', 'vuv' ),
		'add_or_remove_items'        => __( 'Ddoaj ili ukloni', 'vuv' ),
		'choose_from_most_used'      => __( 'Odaberi iz najcesce koristenih', 'vuv' ),
		'popular_items'              => __( 'Populani glumci', 'vuv' ),
		'search_items'               => __( 'Pretrazi', 'vuv' ),
		'not_found'                  => __( 'Nije pronađeno', 'vuv' ),
		'no_terms'                   => __( 'Nije pronađeno', 'vuv' ),
		'items_list'                 => __( 'Lista glumaca', 'vuv' ),
		'items_list_navigation'      => __( 'Navigacija', 'vuv' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'glavni_glumci', array( 'film', 'serija' ), $args );

}
add_action( 'init', 'registriraj_taksonomiju_glavni_glumci', 0 );
// Register Custom Taxonomy
function registriraj_taksonomiju_broj_sezona() {

	$labels = array(
		'name'                       => _x( 'Broj sezona', 'Taxonomy General Name', 'vuv' ),
		'singular_name'              => _x( 'Broj sezona', 'Taxonomy Singular Name', 'vuv' ),
		'menu_name'                  => __( 'Broj sezona', 'vuv' ),
		'all_items'                  => __( 'Broj sezona', 'vuv' ),
		'parent_item'                => __( 'Parent Item', 'vuv' ),
		'parent_item_colon'          => __( 'Parent Item:', 'vuv' ),
		'new_item_name'              => __( 'Novi broj sezona', 'vuv' ),
		'add_new_item'               => __( 'Dodaj novi broj sezona', 'vuv' ),
		'edit_item'                  => __( 'Uredi broj sezona', 'vuv' ),
		'update_item'                => __( 'Azuriraj broj sezona', 'vuv' ),
		'view_item'                  => __( 'Pregledaj', 'vuv' ),
		'separate_items_with_commas' => __( 'Odvoji s zarezom', 'vuv' ),
		'add_or_remove_items'        => __( 'Ddoaj ili ukloni', 'vuv' ),
		'choose_from_most_used'      => __( 'Odaberi iz najcesce koristenih', 'vuv' ),
		'popular_items'              => __( 'Populani broj sezona', 'vuv' ),
		'search_items'               => __( 'Pretrazi', 'vuv' ),
		'not_found'                  => __( 'Nije pronađeno', 'vuv' ),
		'no_terms'                   => __( 'Nije pronađeno', 'vuv' ),
		'items_list'                 => __( '', 'vuv' ),
		'items_list_navigation'      => __( 'Navigacija', 'vuv' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'broj_sezona', array( 'serija' ), $args );

}
add_action( 'init', 'registriraj_taksonomiju_broj_sezona', 0 );

// Register Custom Taxonomy
function registriraj_taksonomiju_epizoda() {

	$labels = array(
		'name'                       => _x( 'Epizode', 'Taxonomy General Name', 'vuv' ),
		'singular_name'              => _x( 'Epizoda', 'Taxonomy Singular Name', 'vuv' ),
		'menu_name'                  => __( 'Epizoda', 'vuv' ),
		'all_items'                  => __( 'Epizode', 'vuv' ),
		'parent_item'                => __( 'Sezona', 'vuv' ),
		'parent_item_colon'          => __( 'Sezona:', 'vuv' ),
		'new_item_name'              => __( 'Nova epizoda', 'vuv' ),
		'add_new_item'               => __( 'Dodaj novu epizodu', 'vuv' ),
		'edit_item'                  => __( 'Uredi epizodu', 'vuv' ),
		'update_item'                => __( 'Azuriraj epizodu', 'vuv' ),
		'view_item'                  => __( 'Pregledaj epizodu', 'vuv' ),
		'separate_items_with_commas' => __( 'Odvoji s zarezome', 'vuv' ),
		'add_or_remove_items'        => __( 'Dodaj ili ukloni', 'vuv' ),
		'choose_from_most_used'      => __( 'Odaberi iz najcesce koristenih', 'vuv' ),
		'popular_items'              => __( 'Poplarne epizode', 'vuv' ),
		'search_items'               => __( 'Pretrazi', 'vuv' ),
		'not_found'                  => __( 'Nije porandeno', 'vuv' ),
		'no_terms'                   => __( 'Nije pronadeno', 'vuv' ),
		'items_list'                 => __( 'Lista', 'vuv' ),
		'items_list_navigation'      => __( 'Navigacija', 'vuv' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'epizoda', array( 'serija' ), $args );

}
add_action( 'init', 'registriraj_taksonomiju_epizoda', 0 );
// Register Custom Taxonomy
function registriraj_taksonomiju_vrsta_emisije() {

	$labels = array(
		'name'                       => _x( 'Vrsta emisije', 'Taxonomy General Name', 'vuv' ),
		'singular_name'              => _x( 'Vrsta emisije', 'Taxonomy Singular Name', 'vuv' ),
		'menu_name'                  => __( 'Vrsta emisije', 'vuv' ),
		'all_items'                  => __( 'Vrsta emisije', 'vuv' ),
		'parent_item'                => __( '', 'vuv' ),
		'parent_item_colon'          => __( '', 'vuv' ),
		'new_item_name'              => __( 'Nova vrsta emisije', 'vuv' ),
		'add_new_item'               => __( 'Dodaj novu vrstu emisije', 'vuv' ),
		'edit_item'                  => __( 'Uredi vrstu emisije', 'vuv' ),
		'update_item'                => __( 'Azuriraj vrstu emisije', 'vuv' ),
		'view_item'                  => __( 'Pregledaj vrstu emisije', 'vuv' ),
		'separate_items_with_commas' => __( 'Odvoji s zarezome', 'vuv' ),
		'add_or_remove_items'        => __( 'Dodaj ili ukloni', 'vuv' ),
		'choose_from_most_used'      => __( 'Odaberi iz najcesce koristenih', 'vuv' ),
		'popular_items'              => __( 'Poplarne vrstu emisije', 'vuv' ),
		'search_items'               => __( 'Pretrazi', 'vuv' ),
		'not_found'                  => __( 'Nije porandeno', 'vuv' ),
		'no_terms'                   => __( 'Nije pronadeno', 'vuv' ),
		'items_list'                 => __( 'Lista', 'vuv' ),
		'items_list_navigation'      => __( 'Navigacija', 'vuv' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'vrsta_emisije', array( 'emisija' ), $args );

}
add_action( 'init', 'registriraj_taksonomiju_vrsta_emisije', 0 );
// Register Custom Taxonomy
function registriraj_taksonomiju_voditelji() {

	$labels = array(
		'name'                       => _x( 'Voditelji', 'Taxonomy General Name', 'vuv' ),
		'singular_name'              => _x( 'Voditelj', 'Taxonomy Singular Name', 'vuv' ),
		'menu_name'                  => __( 'Voditelji', 'vuv' ),
		'all_items'                  => __( 'Voditelji', 'vuv' ),
		'parent_item'                => __( '', 'vuv' ),
		'parent_item_colon'          => __( '', 'vuv' ),
		'new_item_name'              => __( 'Novi voditelj', 'vuv' ),
		'add_new_item'               => __( 'Dodaj novog voditelja', 'vuv' ),
		'edit_item'                  => __( 'Uredi voditelja', 'vuv' ),
		'update_item'                => __( 'Azuriraj voditelja', 'vuv' ),
		'view_item'                  => __( 'Pregledaj voditelja', 'vuv' ),
		'separate_items_with_commas' => __( 'Odvoji s zarezome', 'vuv' ),
		'add_or_remove_items'        => __( 'Dodaj ili ukloni', 'vuv' ),
		'choose_from_most_used'      => __( 'Odaberi iz najcesce koristenih', 'vuv' ),
		'popular_items'              => __( 'Poplarni voditelji', 'vuv' ),
		'search_items'               => __( 'Pretrazi', 'vuv' ),
		'not_found'                  => __( 'Nije porandeno', 'vuv' ),
		'no_terms'                   => __( 'Nije pronadeno', 'vuv' ),
		'items_list'                 => __( 'Lista', 'vuv' ),
		'items_list_navigation'      => __( 'Navigacija', 'vuv' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'voditelji', array( 'emisija' ), $args );

}
add_action( 'init', 'registriraj_taksonomiju_voditelji', 0 );
// Register Custom Taxonomy
function registriraj_taksonomiju_gosti() {

	$labels = array(
		'name'                       => _x( 'Gosti', 'Taxonomy General Name', 'vuv' ),
		'singular_name'              => _x( 'Gosti', 'Taxonomy Singular Name', 'vuv' ),
		'menu_name'                  => __( 'Gosti', 'vuv' ),
		'all_items'                  => __( 'Gosti', 'vuv' ),
		'parent_item'                => __( '', 'vuv' ),
		'parent_item_colon'          => __( '', 'vuv' ),
		'new_item_name'              => __( 'Novi gost', 'vuv' ),
		'add_new_item'               => __( 'Dodaj novog gosta', 'vuv' ),
		'edit_item'                  => __( 'Uredi gosta', 'vuv' ),
		'update_item'                => __( 'Azuriraj gosta', 'vuv' ),
		'view_item'                  => __( 'Pregledaj gosta', 'vuv' ),
		'separate_items_with_commas' => __( 'Odvoji s zarezome', 'vuv' ),
		'add_or_remove_items'        => __( 'Dodaj ili ukloni', 'vuv' ),
		'choose_from_most_used'      => __( 'Odaberi iz najcesce koristenih', 'vuv' ),
		'popular_items'              => __( 'Poplarni gosti', 'vuv' ),
		'search_items'               => __( 'Pretrazi', 'vuv' ),
		'not_found'                  => __( 'Nije porandeno', 'vuv' ),
		'no_terms'                   => __( 'Nije pronadeno', 'vuv' ),
		'items_list'                 => __( 'Lista', 'vuv' ),
		'items_list_navigation'      => __( 'Navigacija', 'vuv' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'gosti', array( 'emisija' ), $args );

}
add_action( 'init', 'registriraj_taksonomiju_gosti', 0 );
// Register Custom Taxonomy
function registriraj_taksonomiju_natjecatelj() {

	$labels = array(
		'name'                       => _x( 'Natjecatelji', 'Taxonomy General Name', 'vuv' ),
		'singular_name'              => _x( 'Natjecatelj', 'Taxonomy Singular Name', 'vuv' ),
		'menu_name'                  => __( 'Natjecatelji', 'vuv' ),
		'all_items'                  => __( 'Natjecatelji', 'vuv' ),
		'parent_item'                => __( '', 'vuv' ),
		'parent_item_colon'          => __( '', 'vuv' ),
		'new_item_name'              => __( 'Novi natjecatelj', 'vuv' ),
		'add_new_item'               => __( 'Dodaj novog natjecatelja', 'vuv' ),
		'edit_item'                  => __( 'Uredi natjecatelja', 'vuv' ),
		'update_item'                => __( 'Azuriraj natjecatelja', 'vuv' ),
		'view_item'                  => __( 'Pregledaj natjecatelja', 'vuv' ),
		'separate_items_with_commas' => __( 'Odvoji s zarezome', 'vuv' ),
		'add_or_remove_items'        => __( 'Dodaj ili ukloni', 'vuv' ),
		'choose_from_most_used'      => __( 'Odaberi iz najcesce koristenih', 'vuv' ),
		'popular_items'              => __( 'Poplarni natjecatelji', 'vuv' ),
		'search_items'               => __( 'Pretrazi', 'vuv' ),
		'not_found'                  => __( 'Nije porandeno', 'vuv' ),
		'no_terms'                   => __( 'Nije pronadeno', 'vuv' ),
		'items_list'                 => __( 'Lista', 'vuv' ),
		'items_list_navigation'      => __( 'Navigacija', 'vuv' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'natjecatelj', array( 'emisija' ), $args );

}
add_action( 'init', 'registriraj_taksonomiju_natjecatelj', 0 );
// Register Custom Taxonomy
function registriraj_taksonomiju_ziri() {

	$labels = array(
		'name'                       => _x( 'Žiri', 'Taxonomy General Name', 'vuv' ),
		'singular_name'              => _x( 'Žiri', 'Taxonomy Singular Name', 'vuv' ),
		'menu_name'                  => __( 'Žiri', 'vuv' ),
		'all_items'                  => __( 'Žiri', 'vuv' ),
		'parent_item'                => __( '', 'vuv' ),
		'parent_item_colon'          => __( '', 'vuv' ),
		'new_item_name'              => __( 'Novi clan žirija', 'vuv' ),
		'add_new_item'               => __( 'Dodaj novog člana', 'vuv' ),
		'edit_item'                  => __( 'Uredi člana', 'vuv' ),
		'update_item'                => __( 'Azuriraj člana', 'vuv' ),
		'view_item'                  => __( 'Pregledaj člana', 'vuv' ),
		'separate_items_with_commas' => __( 'Odvoji s zarezome', 'vuv' ),
		'add_or_remove_items'        => __( 'Dodaj ili ukloni', 'vuv' ),
		'choose_from_most_used'      => __( 'Odaberi iz najcesce koristenih', 'vuv' ),
		'popular_items'              => __( 'Poplarni članovi žirija', 'vuv' ),
		'search_items'               => __( 'Pretrazi', 'vuv' ),
		'not_found'                  => __( 'Nije porandeno', 'vuv' ),
		'no_terms'                   => __( 'Nije pronadeno', 'vuv' ),
		'items_list'                 => __( 'Lista', 'vuv' ),
		'items_list_navigation'      => __( 'Navigacija', 'vuv' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'ziri', array( 'emisija' ), $args );

}
add_action( 'init', 'registriraj_taksonomiju_ziri', 0 );


function add_meta_box_serija_kolicina(){
	add_meta_box( 'filmoteka_meta_box_primjerci_serija', 'Broj primjeraka serija', 'html_meta_box_serija_kolicina', 'serija');
}
function html_meta_box_serija_kolicina($post){
	wp_nonce_field('spremi_seriju', 'broj_primjeraka_nonce');
	
	//dohvaćanje meta vrijednosti
	$serija_kolicina = get_post_meta($post->ID, 'kolicina_primjeraka_serija', true);
	
	echo '
	<div>
		<div>
			<label for="kolicina_primjeraka_serija">Broj primjeraka serije koji su dostupni: </label>
			<input type="text" id="kolicina_primjeraka_serija" name="kolicina_primjeraka_serija" value="'.$serija_kolicina.'" />
		</div>
	</div>';
}
function spremi_seriju($post_id){
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce_broj_primjeraka = ( isset( $_POST[ 'broj_primjeraka_nonce' ] ) && wp_verify_nonce(
	$_POST[ 'broj_primjeraka_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
	
	if ($is_autosave || $is_revision || !$is_valid_nonce_broj_primjeraka) {
	 return;
	}
	if(!empty($_POST['kolicina_primjeraka_serija'])){
		update_post_meta($post_id, 'kolicina_primjeraka_serija',
		$_POST['kolicina_primjeraka_serija']);
	}
	else delete_post_meta($post_id, 'kolicina_primjeraka_serija');
	
}
add_action( 'add_meta_boxes', 'add_meta_box_serija_kolicina' );
add_action( 'save_post', 'spremi_seriju' );

function add_meta_box_film_kolicina(){
	add_meta_box( 'filmoteka_meta_box_primjerci_filmova', 'Broj primjeraka filma', 'html_meta_box_film_kolicina', 'film');
}
function html_meta_box_film_kolicina($post){
	wp_nonce_field('spremi_film', 'broj_primjeraka_nonce');
	
	//dohvaćanje meta vrijednosti
	$film_kolicina = get_post_meta($post->ID, 'kolicina_primjeraka_film', true);
	
	echo '
	<div>
		<div>
			<label for="kolicina_primjeraka_film">Broj primjeraka filma koji su dostupni: </label>
			<input type="text" id="kolicina_primjeraka_film" name="kolicina_primjeraka_film" value="'.$film_kolicina.'" />
		</div>
	</div>';
}
function spremi_film($post_id){
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce_broj_primjeraka = ( isset( $_POST[ 'broj_primjeraka_nonce' ] ) && wp_verify_nonce(
	$_POST[ 'broj_primjeraka_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
	
	if ($is_autosave || $is_revision || !$is_valid_nonce_broj_primjeraka) {
	 return;
	}
	if(!empty($_POST['kolicina_primjeraka_film'])){
		update_post_meta($post_id, 'kolicina_primjeraka_film',
		$_POST['kolicina_primjeraka_film']);
	}
	else delete_post_meta($post_id, 'kolicina_primjeraka_film');
	
}
add_action( 'add_meta_boxes', 'add_meta_box_film_kolicina' );
add_action( 'save_post', 'spremi_film' );


function add_meta_box_emisija_kolicina(){
	add_meta_box( 'filmoteka_meta_box_primjerci_emisije', 'Broj primjeraka emisije', 'html_meta_box_emisija_kolicina', 'emisija');
}
function html_meta_box_emisija_kolicina($post){
	wp_nonce_field('spremi_emisiju', 'broj_primjeraka_nonce');
	
	//dohvaćanje meta vrijednosti
	$emisija_kolicina = get_post_meta($post->ID, 'kolicina_primjeraka_emisije', true);
	
	echo '
	<div>
		<div>
			<label for="kolicina_primjeraka_emisije">Broj primjeraka emisije koji su dostupni: </label>
			<input type="text" id="kolicina_primjeraka_emisije" name="kolicina_primjeraka_emisije" value="'.$emisija_kolicina.'" />
		</div>
	</div>';
}
function spremi_emisiju($post_id){
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce_broj_primjeraka = ( isset( $_POST[ 'broj_primjeraka_nonce' ] ) && wp_verify_nonce(
	$_POST[ 'broj_primjeraka_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
	
	if ($is_autosave || $is_revision || !$is_valid_nonce_broj_primjeraka) {
		return;
	}
	if(!empty($_POST['kolicina_primjeraka_emisije'])){
		update_post_meta($post_id, 'kolicina_primjeraka_emisije',
		$_POST['kolicina_primjeraka_emisije']);
	}
	else delete_post_meta($post_id, 'kolicina_primjeraka_emisije');
	
}
add_action( 'add_meta_boxes', 'add_meta_box_emisija_kolicina' );
add_action( 'save_post', 'spremi_emisiju' );

function daj_najnovije_filmove(){
    $filmovi = get_posts(array(
        'numberposts' => 3,
        'post_status' => 'publish',
        'post_type' =>'film',
        'order_by' => 'post_date',
    ));
    //var_dump($filmovi);
    $brojac = 0;
	$brojac_const = 0;
	$htmlForMovies='';
    foreach ($filmovi as $film){
		$brojac = $brojac + 1;
		$brojac_const = $brojac_const + 1;
        $tax_glumci = get_the_terms($film->ID, 'glavni_glumci');
        $tax_redatelj = get_the_terms($film->ID, 'redatelj');
        $tax_zanr = get_the_terms($film->ID, 'zanr');
        $tax_godina = get_the_terms($film->ID, 'godina');
        $tax_ocjena = get_the_terms($film->ID, 'ocjena');
        $film_kolicina = get_post_meta($film->ID, 'kolicina_primjeraka_film', false);
        $film_naslov=$film->post_title;
        $sIstaknutaSlika="";
        if( get_the_post_thumbnail_url($film->ID) )
        {
            $sIstaknutaSlika = get_the_post_thumbnail_url($film->ID);
        }
        else
        {
            $sIstaknutaSlika = get_template_directory_uri(). '/img/no_image.jpg';
        }
        if($film_naslov!=""){
            $obj=(object)[
                'id_filma' => $film->ID,
                'naziv_filma' => $film->post_title,
                'istaknuta_slika' => $sIstaknutaSlika,
                'guid' => $film->guid,
                'glumci' => array(),
                'redatelj' => array(),
                'zanr' => array(),
                'godina' => array(),
                'ocjena' => array(),
				'nagrade' => array(),
                'kolicine'=> array()
            ];
                $glumci=array();
                $redatelj=array();
                $zanr=array();
                $godina=array();
                $ocjena=array();
				$nagrade=array();
                $kolicine=array();
				if($tax_glumci){
					foreach ($tax_glumci as $term) {
    
						$glu=(object)[
							'glumac_naziv' => $term->name,
							'glumac_slug' => $term->slug
						];
						array_push($glumci, $glu);
					}
					array_push($obj->glumci, $glumci);
				}
                
                foreach ($tax_redatelj as $term) {
    
                    $red=(object)[
                        'redatelj_naziv' => $term->name,
                        'redatelj_slug' => $term->slug
                    ];
                    array_push($redatelj, $red);
                }
                foreach ($tax_zanr as $term) {
    
                    $zan=(object)[
                        'zanr_naziv' => $term->name,
                        'zanr_slug' => $term->slug
                    ];
                    array_push($zanr, $zan);
                }
                foreach ($tax_godina as $term) {
    
                    $god=(object)[
                        'godina_naziv' => $term->name,
                        'godina_slug' => $term->slug
                    ];
                    array_push($godina, $god);
                }
                foreach ($tax_ocjena as $term) {
    
                    $ocj=(object)[
                        'ocjena_naziv' => $term->name,
                        'ocjena_slug' => $term->slug
                    ];
                    array_push($ocjena, $ocj);
                }
				if($tax_nagrade){
					foreach ($tax_nagrade as $term) {
    
						$n=(object)[
							'nagrada_naziv' => $term->name,
							'nagrada_slug' => $term->slug
						];
						array_push($nagrada, $n);
					}
					array_push($obj->nagrade, $nagrada);
				}
				
                foreach ($film_kolicina as $key) {
                    $kol=(object)[
                        'kolicina_filmova' => $key
                    ];
                    array_push($kolicine, $kol);
                }
                
                array_push($obj->redatelj, $redatelj);
                array_push($obj->zanr, $zanr);
                array_push($obj->godina, $godina);
                array_push($obj->ocjena, $ocjena);
				
                array_push($obj->kolicine, $kolicine);
				
			if ($brojac == 1){
				$htmlForMovies .= '
				<div class="movieContainer">
					<div class="row">';
			}
			if($brojac >= 1 && $brojac<=3){
				$htmlForMovies.='
				<div class="col-4">
					<div class="">
						<a href="'.$film->guid.'">
							<img class="movieImage" src="'.$sIstaknutaSlika.'">
						</a>
					</div>
					<div class="movieHeader">
						<h3><a href="'.$film->guid.'">'.$film->post_title.'</a></h3>
					</div>
				</div>';
			}if($brojac==3 ){
				$htmlForMovies.='
					</div>
				</div>';
				$brojac = 0;
			}if($brojac_const == count($filmovi)){
				$htmlForMovies.='
					</div>
				</div>';
			}

        
        }  
    }
echo $htmlForMovies;
}
function daj_filmove( $slug ){
    $args = array(
        'posts_per_page' => -1,
        'post_type' => 'film',
        'post_status' => 'publish',
        'tax_query' => array(
            array(
                'taxonomy' => 'zanr',
                'field' => 'slug',
                'terms' => $slug
            )
    ));
    $filmovi = get_posts( $args );
	//var_dump($filmovi);
	$brojac = 0;
	$brojac_const = 0;
	$htmlForMovies='';
    foreach ($filmovi as $film){
		$brojac = $brojac + 1;
		$brojac_const = $brojac_const + 1;
        $tax_glumci = get_the_terms($film->ID, 'glavni_glumci');
        $tax_redatelj = get_the_terms($film->ID, 'redatelj');
        $tax_zanr = get_the_terms($film->ID, 'zanr');
        $tax_godina = get_the_terms($film->ID, 'godina');
        $tax_ocjena = get_the_terms($film->ID, 'ocjena');
        $film_kolicina = get_post_meta($film->ID, 'kolicina_primjeraka_film', false);
        $film_naslov=$film->post_title;
        $sIstaknutaSlika="";
        if( get_the_post_thumbnail_url($film->ID) )
        {
            $sIstaknutaSlika = get_the_post_thumbnail_url($film->ID);
        }
        else
        {
            $sIstaknutaSlika = get_template_directory_uri(). '/img/no_image.jpg';
        }
        if($film_naslov!=""){
            $obj=(object)[
                'id_filma' => $film->ID,
                'naziv_filma' => $film->post_title,
                'istaknuta_slika' => $sIstaknutaSlika,
                'guid' => $film->guid,
                'glumci' => array(),
                'redatelj' => array(),
                'zanr' => array(),
                'godina' => array(),
                'ocjena' => array(),
				'nagrade' => array(),
                'kolicine'=> array()
            ];
                $glumci=array();
                $redatelj=array();
                $zanr=array();
                $godina=array();
                $ocjena=array();
				$nagrade=array();
                $kolicine=array();
				if($tax_glumci){
					foreach ($tax_glumci as $term) {
    
						$glu=(object)[
							'glumac_naziv' => $term->name,
							'glumac_slug' => $term->slug
						];
						array_push($glumci, $glu);
					}
					array_push($obj->glumci, $glumci);
				}
                
                foreach ($tax_redatelj as $term) {
    
                    $red=(object)[
                        'redatelj_naziv' => $term->name,
                        'redatelj_slug' => $term->slug
                    ];
                    array_push($redatelj, $red);
                }
                foreach ($tax_zanr as $term) {
    
                    $zan=(object)[
                        'zanr_naziv' => $term->name,
                        'zanr_slug' => $term->slug
                    ];
                    array_push($zanr, $zan);
                }
                foreach ($tax_godina as $term) {
    
                    $god=(object)[
                        'godina_naziv' => $term->name,
                        'godina_slug' => $term->slug
                    ];
                    array_push($godina, $god);
                }
                foreach ($tax_ocjena as $term) {
    
                    $ocj=(object)[
                        'ocjena_naziv' => $term->name,
                        'ocjena_slug' => $term->slug
                    ];
                    array_push($ocjena, $ocj);
                }
				if($tax_nagrade){
					foreach ($tax_nagrade as $term) {
    
						$n=(object)[
							'nagrada_naziv' => $term->name,
							'nagrada_slug' => $term->slug
						];
						array_push($nagrada, $n);
					}
					array_push($obj->nagrade, $nagrada);
				}
				
                foreach ($film_kolicina as $key) {
                    $kol=(object)[
                        'kolicina_filmova' => $key
                    ];
                    array_push($kolicine, $kol);
                }
                
                array_push($obj->redatelj, $redatelj);
                array_push($obj->zanr, $zanr);
                array_push($obj->godina, $godina);
                array_push($obj->ocjena, $ocjena);
				
                array_push($obj->kolicine, $kolicine);
				
			if ($brojac == 1){
				$htmlForMovies .= '
				<div class="movieContainer">
					<div class="row">';
			}
			if($brojac >= 1 && $brojac<=3){
				$htmlForMovies.='
				<div class="col-4">
					<div class="">
						<a href="'.$film->guid.'">
							<img class="movieImage" src="'.$sIstaknutaSlika.'">
						</a>
					</div>
					<div class="movieHeader">
						<h3 class="movieHeaderOnly"><a href="'.$film->guid.'">'.$film->post_title.'</a></h3>
					</div>
				</div>';
			}if($brojac==3 ){
				$htmlForMovies.='
					</div>
				</div>';
				$brojac = 0;
			}if($brojac_const == count($filmovi)){
				$htmlForMovies.='
					</div>
				</div>';
			}

        
        }  
}
echo $htmlForMovies;
}

function daj_serije(){
	$serije = get_posts(array(
        'post_status' => 'publish',
        'post_type' =>'serija',
        'order_by' => 'post_date',
    ));
    //var_dump($serije);
    $brojac = 0;
	$brojac_const = 0;
	$html='';
    foreach ($serije as $serija){
		$brojac = $brojac + 1;
		$brojac_const = $brojac_const + 1;
		$tax_zanr = get_the_terms($serija->ID, 'zanr');
        $tax_ocjena = get_the_terms($serija->ID, 'ocjena');
        $tax_glumci = get_the_terms($serija->ID, 'glavni_glumci');
        $tax_broj_sezona = get_the_terms($serija->ID, 'broj_sezona');
        $tax_epizoda = get_the_terms($serija->ID, 'epizoda');
        $serija_kolicina = get_post_meta($serija->ID, 'kolicina_primjeraka_serija', false);
        $serija_naslov=$serija->post_title;
        $sIstaknutaSlika="";
        if( get_the_post_thumbnail_url($serija->ID) )
        {
            $sIstaknutaSlika = get_the_post_thumbnail_url($serija->ID);
        }
        else
        {
            $sIstaknutaSlika = get_template_directory_uri(). '/img/no_image.jpg';
        }
        if($serija_naslov!=""){
            $obj=(object)[
                'id_serije' => $serija->ID,
                'naziv_serije' => $serija->post_title,
                'istaknuta_slika' => $sIstaknutaSlika,
                'guid' => $serija->guid,
                'zanr' => array(),
                'ocjena' => array(),
                'glumci' => array(),
                'broj_sezona' => array(),
                'epizode' => array(),
                'kolicine'=> array()
            ];
			$zanr=array();
			$ocjena=array();
			$glumci=array();
			$broj_sezona=array();
			$epizoda=array();
			$kolicine=array();
                foreach ($tax_glumci as $term) {
    
                    $glu=(object)[
                        'glumac_naziv' => $term->name,
                        'glumac_slug' => $term->slug
                    ];
                    array_push($glumci, $glu);
                }
                foreach ($tax_broj_sezona as $term) {
    
                    $red=(object)[
                        'broj_sezona_naziv' => $term->name,
                        'broj_sezona_slug' => $term->slug
                    ];
                    array_push($broj_sezona, $red);
                }
                foreach ($tax_zanr as $term) {
    
                    $zan=(object)[
                        'zanr_naziv' => $term->name,
                        'zanr_slug' => $term->slug
                    ];
                    array_push($zanr, $zan);
                }
                foreach ($tax_epizoda as $term) {
    
                    $ep=(object)[
                        'epozoda_naziv' => $term->name,
                        'epozoda_slug' => $term->slug
                    ];
                    array_push($epizoda, $ep);
                }
                foreach ($tax_ocjena as $term) {
    
                    $ocj=(object)[
                        'ocjena_naziv' => $term->name,
                        'ocjena_slug' => $term->slug
                    ];
                    array_push($ocjena, $ocj);
                }
                foreach ($serija_kolicina as $key) {
                    $kol=(object)[
                        'serija_kolicina' => $key
                    ];
                    array_push($kolicine, $kol);
                }
                array_push($obj->zanr, $zanr);
                array_push($obj->glumci, $glumci);
                array_push($obj->ocjena, $ocjena);
                array_push($obj->broj_sezona, $broj_sezona);
                array_push($obj->epizode, $epizoda);
                array_push($obj->kolicine, $kolicine);
				
			if ($brojac == 1){
				$html .= '
				<div class="movieContainer">
					<div class="row">';
			}
			if($brojac >= 1 && $brojac<=3){
				$html.='
				<div class="col-4">
					<div class="">
						<a href="'.$serija->guid.'">
							<img class="movieImage" src="'.$sIstaknutaSlika.'">
						</a>
					</div>
					<div class="movieHeader">
						<h3 calss="movieHeaderOnly"><a href="'.$serija->guid.'">'.$serija->post_title.'</a></h3>
					</div>
				</div>';
			}if($brojac==3 ){
				$html.='
					</div>
				</div>';
				$brojac = 0;
			}if($brojac_const == count($serije)){
				$html.='
					</div>
				</div>';
			}
        }  
    }
echo $html;
}
function daj_emisije(){
	$emisije = get_posts(array(
        'post_status' => 'publish',
        'post_type' =>'emisija',
        'order_by' => 'post_date',
    ));
    //var_dump($emisije);
    $brojac = 0;
	$brojac_const = 0;
	$htmlForMovies='';
    foreach ($emisije as $emisija){
		$brojac = $brojac + 1;
		$brojac_const = $brojac_const + 1;
        $tax_vrsta_emisije = get_the_terms($emisija->ID, 'vrsta_emisije');
        $tax_voditelji = get_the_terms($emisija->ID, 'voditelji');
        $tax_gosti = get_the_terms($emisija->ID, 'gosti');
        $tax_natjecatelj = get_the_terms($emisija->ID, 'natjecatelj');
        $tax_ziri = get_the_terms($emisija->ID, 'ziri');
		//var_dump($tax_ziri);
        $emisija_kolicina = get_post_meta($emisija->ID, 'kolicina_primjeraka_emisije', false);
        $emisija_naslov=$emisija->post_title;
        $sIstaknutaSlika="";
        if( get_the_post_thumbnail_url($emisija->ID) )
        {
            $sIstaknutaSlika = get_the_post_thumbnail_url($emisija->ID);
        }
        else
        {
            $sIstaknutaSlika = get_template_directory_uri(). '/img/no_image.jpg';
        }
        if($emisija_naslov!=""){
            $obj=(object)[
                'id_emisija' => $emisija->ID,
                'naziv_emisijaa' => $emisija->post_title,
                'istaknuta_slika' => $sIstaknutaSlika,
                'guid' => $emisija->guid,
                'vrsta_emisije' => array(),
                'voditelji' => array(),
                'gosti' => array(),
                'natjecatelji' => array(),
                'ziri' => array(),
                'kolicine'=> array()
            ];
                $vrsta_emisije=array();
                $voditelj=array();
                $gost=array();
                $natjecatelj=array();
                $ziri=array();
                $kolicine=array();
                foreach ($tax_vrsta_emisije as $term) {
    
                    $em=(object)[
                        'vrsta_emisije_naziv' => $term->name,
                        'vrsta_emisije_slug' => $term->slug
                    ];
                    array_push($vrsta_emisije, $em);
                }
				array_push($obj->vrsta_emisije, $vrsta_emisije);

				if($tax_voditelji){
					foreach ($tax_voditelji as $term) {
    
						$vod=(object)[
							'voditelj_naziv' => $term->name,
							'voditelj_slug' => $term->slug
						];
						array_push($voditelj, $vod);
					}
					array_push($obj->voditelji, $voditelj);
				}

                if($tax_gosti){
					foreach ($tax_gosti as $term) {
    
						$g=(object)[
							'gost_naziv' => $term->name,
							'gost_slug' => $term->slug
						];
						array_push($gost, $g);
					}
					array_push($obj->gosti, $gost);
				}
                
				if($tax_natjecatelj){
					foreach ($tax_natjecatelj as $term) {
    
						$nat=(object)[
							'natjecatelj_naziv' => $term->name,
							'natjecatelj_slug' => $term->slug
						];
						array_push($natjecatelj, $nat);
					}
					array_push($obj->natjecatelji, $natjecatelj);
				}
                
				if($tax_ziri){
					foreach ($tax_ziri as $term) {
    
						$z=(object)[
							'ziri_naziv' => $term->name,
							'ziri_slug' => $term->slug
						];
						array_push($ziri, $z);
					}
					array_push($obj->ziri, $ziri);
				}
                foreach ($emisija_kolicina as $key) {
                    $kol=(object)[
                        'kolicina_emisija' => $key
                    ];
                    array_push($kolicine, $kol);
                }
                array_push($obj->kolicine, $kolicine);
				
			if ($brojac == 1){
				$htmlForMovies .= '
				<div class="movieContainer">
					<div class="row">';
			}
			if($brojac >= 1 && $brojac<=3){
				$htmlForMovies.='
				<div class="col-4">
					<div class="">
						<a href="'.$emisija->guid.'">
							<img class="emisijaImage" src="'.$sIstaknutaSlika.'">
						</a>
					</div>
					<div class="movieHeader">
						<h3 class="movieHeaderOnly"><a href="'.$emisija->guid.'">'.$emisija->post_title.'</a></h3>
					</div>
				</div>';
			}if($brojac==3 ){
				$htmlForMovies.='
					</div>
				</div>';
				$brojac = 0;
			}if($brojac_const == count($emisije)){
				$htmlForMovies.='
					</div>
				</div>';
			}
        }  
    }
echo $htmlForMovies;
}

add_action( 'register_form', 'myplugin_register_form' );
function myplugin_register_form() {

    $first_name = ( ! empty( $_POST['first_name'] ) ) ? trim( $_POST['first_name'] ) : '';
    $last_name = ( ! empty( $_POST['last_name'] ) ) ? trim( $_POST['last_name'] ) : '';

        ?>
        <p>
            <label for="first_name"><?php _e( 'Ime', 'mydomain' ) ?><br />
            <input type="text" name="first_name" id="first_name" class="input" value="<?php echo esc_attr( wp_unslash( $first_name ) ); ?>" size="25" /></label>
        </p>

        <p>
            <label for="last_name"><?php _e( 'Prezime', 'mydomain' ) ?><br />
            <input type="text" name="last_name" id="last_name" class="input" value="<?php echo esc_attr( wp_unslash( $last_name ) ); ?>" size="25" /></label>
        </p>

		<p>
			<label for="user_password"><?php _e('Lozinka') ?><br />
			<input type="password" name="user_password" id="user_password" class="input" value="<?php echo esc_attr(wp_unslash($user_login)); ?>" size="25" /></label>
		</p>
	    <p>
			<label for="user_password2"><?php _e('Potvrdi lozinku') ?><br />
			<input type="password" name="user_password2" id="user_password2" class="input" value="<?php echo esc_attr(wp_unslash($user_login)); ?>" size="25" /></label>
		</p>

        <?php
    }

    //2. Add validation. In this case, we make sure first_name and last_name is required.
    add_filter( 'registration_errors', 'myplugin_registration_errors', 10, 3 );
    function myplugin_registration_errors( $errors, $sanitized_user_login, $user_email ) {

        if ( empty( $_POST['first_name'] ) || ! empty( $_POST['first_name'] ) && trim( $_POST['first_name'] ) == '' ) {
            $errors->add( 'first_name_error', __( '<strong>ERROR</strong>: Ime je obavezan podatak.', 'mydomain' ) );
        }
        if ( empty( $_POST['last_name'] ) || ! empty( $_POST['last_name'] ) && trim( $_POST['last_name'] ) == '' ) {
            $errors->add( 'last_name_error', __( '<strong>ERROR</strong>: Prezime je obavezan podatak.', 'mydomain' ) );
        }
		if ( empty( $_POST['user_password'] ) || ! empty( $_POST['user_password'] ) && trim( $_POST['user_password'] ) == '' ) {
            $errors->add( 'user_password_error', __( '<strong>ERROR</strong>: Lozinka je obavezan podatak.', 'mydomain' ) );
        }
		if ( empty( $_POST['user_password2'] ) || ! empty( $_POST['user_password2'] ) && trim( $_POST['user_password2'] ) == '' ) {
            $errors->add( 'user_password2_error', __( '<strong>ERROR</strong>: Ponovljena lozinka je obavezan podatak.', 'mydomain' ) );
        }
        return $errors;
    }

    //3. Finally, save our extra registration user meta.
    add_action( 'user_register', 'myplugin_user_register' );
    function myplugin_user_register( $user_id ) {
        if ( ! empty( $_POST['first_name'] ) ) {
            update_user_meta( $user_id, 'first_name', trim( $_POST['first_name'] ) );
            update_user_meta( $user_id, 'last_name', trim( $_POST['last_name'] ) );
        }

		if ( isset( $_POST['user_password'] ) ){
			wp_set_password( $_POST['user_password'], $user_id );
		}
    }

	function taxonomy_rewrite_fix($wp_rewrite) {
		$r = array();
		foreach($wp_rewrite->rules as $k=>$v){
			$r[$k] = str_replace('catalog=$matches[1]&paged=','catalog=$matches[1]&page=',$v);
		}
		$wp_rewrite->rules = $r;
	}
	add_filter('generate_rewrite_rules', 'taxonomy_rewrite_fix');

//UCITAVANJE CSS DATOTEKA
function UcitajCssTeme()
{	
	wp_enqueue_style( 'clean-blog-css', get_template_directory_uri() . '/css/clean-blog.min.css' );
	wp_enqueue_style( 'bootstrap-css', get_template_directory_uri() . '/vendor/bootstrap/css/bootstrap.min.css' );
	wp_enqueue_style( 'fontawesome-css', get_template_directory_uri() . '/vendor/fontawesome-free/css/all.min.css' );
	wp_enqueue_style( 'glavni-css', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'UcitajCssTeme' );

//UCITAVANJE JS DATOTEKA
function UcitajJsTeme()
{		
	wp_enqueue_script('bootstrap-js', get_template_directory_uri().'/vendor/bootstrap/js/bootstrap.min.js', array('jquery'), true);
	wp_enqueue_script('bootstrap-js', get_template_directory_uri().'/vendor/bootstrap/js/bootstrap.bundle.min.js', array('jquery'), true);
	wp_enqueue_script('fontawesome-js', get_template_directory_uri().'/vendor/fontawesome-free/js/all.min.js', array('jquery'), true);
	wp_enqueue_script('jquery-js', get_template_directory_uri().'/vendor/jquery/jquery.min.js', array('jquery'), true);	
	wp_enqueue_style( 'clean-blog-js', get_template_directory_uri() . '/js/clean-blog.min.js' );
	wp_enqueue_script('glavni-js', get_template_directory_uri().'/js/skripta.js', array('jquery'), true);
}
add_action( 'wp_enqueue_scripts', 'UcitajJsTeme' );

?>