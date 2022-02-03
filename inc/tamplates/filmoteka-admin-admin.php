<style >
h1{
    font-size:25px;
}
table{
    width: 80%;
}
th, td {
  width: 25%;
  text-align: center;
  padding: 15px;
  border-bottom: 1px solid #ddd;
}
tr:nth-child(even) {background-color: #f7f7f7;}
</style>
<h1>Administracija</h1>
<br>
<h2>Posuđeni filmovi s korisnicima</h2>
<br>
<br>

<?php 
    $admin_arr = daj_podatke_admin_sve();
    $admin_table_html = '
    <table>
    <tr>
        <th>Ime i prezime</th>
        <th>Naziv filma</th>
        <th>Datum posudbe</th>
        <th>Rok vracanja</th>
        <th>Vrati</th>
    </tr>';
    
     foreach($admin_arr as $row){
        $admin_table_html .= '<tr>';
        $admin_table_html .= '<td>'.$row->username.'</td>';
        $admin_table_html .= '<td>'.$row->nazivPosudenogFilma.'</td>';
        $admin_table_html .= '<td>'.$row->datumPosudbe.'</td>';
        $admin_table_html .= '<td>'.$row->rokVracanja.'</td>';
        $id_posudbe = $row->id;
        //$admin_table_html .= '<td><button onClick="vratiFilm(\''.$row->id.'\');">Vrati</button></td>';
        $admin_table_html .= '
        <td>
            <form method="post" action="">
                <input type="hidden" name="film_id" value='.$row->id.' /><input type="hidden" name="action" value="vrati" /><button class="button" type="submit">Vrati</button>
            </form>
        </td>';
        $admin_table_html .= '</tr>';
    }
    $admin_table_html .= '</table>';
    echo $admin_table_html;
    if (isset($_POST['action']) && $_POST['action']=="vrati"){
        global $wpdb;
	    $id = $_POST['film_id'];
        $id_int = (int)$id;
        $id_posta_obj = $wpdb->get_results('SELECT * FROM admin WHERE id = '.$id_int);
        $id_posta = $id_posta_obj[0]->filmID;
        $wpdb->query('DELETE FROM admin WHERE id = '.$id_int);
        $post_obj = get_post($id_posta);
        $vrsta_posta = $post_obj->post_type;
        
        if($vrsta_posta == 'film'){
            $kolicina_item = get_post_meta($id_posta, 'kolicina_primjeraka_film', true); 
            update_post_meta( $id_posta, 'kolicina_primjeraka_film', $kolicina_item+1 );
        }elseif ($vrsta_posta=='serija') {
            $kolicina_item = get_post_meta($id_posta, 'kolicina_primjeraka_serija', true); 
            update_post_meta( $id_posta, 'kolicina_primjeraka_serija', $kolicina_item+1 );
        }elseif($vrsta_posta=='emisija') {
            $kolicina_item = get_post_meta($id_posta, 'kolicina_primjeraka_emisije', true); 
            update_post_meta( $id_posta, 'kolicina_primjeraka_emisije', $kolicina_item+1 );
        }
    }
?>