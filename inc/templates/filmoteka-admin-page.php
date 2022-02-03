<style >
h1{
    font-size:25px;
}
table{
    width: 50%;
}
th, td {
  width: 25%;
  text-align: center;
  padding: 15px;
  border-bottom: 1px solid #ddd;
}
tr:nth-child(even) {background-color: #f7f7f7;}
</style>
<h1>Pregled</h1>
<br>
<h2>Posuđeni filmovi</h2>
<br>
<br>

<?php 
    $admin_arr = daj_podatke_admin();
    $admin_table_html = '
    <table>
    <tr>
        <th>Naziv filma</th>
        <th>Broj</th>
    </tr>';
    
     foreach($admin_arr as $row){
        $admin_table_html .= '<tr>';
        $admin_table_html .= '<td>'.$row->nazivPosudenogFilma.'</td>';
        $admin_table_html .= '<td>'.$row->brojPosudenih.'</td>';
        $admin_table_html .= '</tr>';
        
    }
    $admin_table_html .= '</table>';
    echo $admin_table_html;
?>
