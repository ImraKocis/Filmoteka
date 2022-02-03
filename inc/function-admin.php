<?php

/*
	
@package filmoteka
	
	========================
		ADMIN PAGE
	========================
*/

function filmoteka_add_admin_page() {
	
	add_menu_page( 'Filmoteka Pregled', 'Filmoteka', 'manage_options', 'filmoteka_admin_panel', 'filmoteka_theme_create_page', get_template_directory_uri() . '/img/movie-reel.png', 110 );

	add_submenu_page( 'filmoteka_admin_panel', 'Filmoteka Pregled', 'Pregled', 'manage_options', 'filmoteka_admin_panel', 'filmoteka_theme_create_page' );

	add_submenu_page( 'filmoteka_admin_panel', 'Filmoteka Administracija', 'Administracija', 'manage_options', 'filmoteka_admin_page', 'filmoteka_theme_admin_page' );
	
}
add_action( 'admin_menu', 'filmoteka_add_admin_page' );

function filmoteka_theme_create_page() {
    require_once( get_template_directory(). '/inc/tamplates/filmoteka-admin-page.php');
}

function filmoteka_theme_admin_page(){
	require_once(get_template_directory(). '/inc/tamplates/filmoteka-admin-admin.php');
}