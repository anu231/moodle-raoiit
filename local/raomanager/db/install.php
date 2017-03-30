<?php


function xmldb_local_raomanager_install(){
    global $DB;

    $centers = array(
        1  => array("Thane (Lokpuram)", "1,2,9,18,43", "C",1),
        2  => array("Thane", "1,2,9,18,43", "C",1),
        3  => array("Dombivali", "3", "C",1),
        4  => array("Powai (CSC)", "4", "C",1),
        5  => array("Andheri","5,22,24,42", "W",1),
        6  => array("Kandivali (TVM)", "6", "W",1),
        7  => array("Borivali","7,12", "W",1),
        8  => array("Kalyan (Birla)","8,25", "C",1),
        9  => array("Sion","9", "C",1),
        10 => array("Nerul","10,17,29", "C",1),
        11 => array("Kandivali (Thakur)","11", "W",1),
        12 => array("Kandivali (T.P. Bhatia)","12,23,7,46", "W",1),
        13 => array("Silvassa", "13", "S",0),
        14 => array("Mumbai (HO)", "14", "C",1),
        15 => array("Kota", "15", "K",0),
        16 => array("Bokaro", "16", "B",0),
        17 => array("Sanpada (Jaipuriar)","10,17", "C",1),
        18 => array("Dadar", "18", "C",1),
        19 => array("Vasai", "19", "W",1),
        20 => array("Kandivali (Ryan)", "20", "W",1),
        21 => array("Kandivali (SVIS)", "21", "W",1),
        22 => array("Andheri (Rajhans)", "22", "W",1),
        23 => array("Bhayandar(Ram Ratna)","7,23", "W",1),
        24 => array("Goregaon (Gokuldham)", "24", "W",1),
        25 => array("Kalyan","8,25", "C",1),
        26 => array("Kamothe (MNR)", "26", "C",1),
        27 => array("Thane (Vasant Vihar)","1,2,27", "C",0),
        28 => array("Santacruz", "28", "W",1),
        29 => array("Kharghar (Ryan)", "29", "C",0),
        30 => array("Powai (Podar)", "30", "C",1),
        31 => array("Mira Road (LR Tiwari)","31,45", "W",1),
        99 => array("Powai (Guest House)","4,14", "C",1),
        32 => array("Nalasopara", "32", "W",0),
        33 => array("Goregaon", "33", "W",1),
        34 => array("Kalyan (Podar)","8,25,34", "C",0),
        35 => array("Pune (Tilak Road)", "35", "C",1),
        36 => array("Pune (Undri)", "36", "C",0),
        37 => array("Pune (Pimpri)", "37", "C",1),
        38 => array("Charni Road (Wilson)", "38", "W",1),
        39 => array("Walkeshwar (Gopi Birla)", "39", "W",0),
        40 => array("Kharghar", "40", "C",1),
        41 => array("Pune (Chinchwad)", "41", "C",1),
        42 => array("Andheri (Hansraj)", "42", "W",0),
        43 => array("Thane (Ghodbunder)","1,2,27,43", "C",1),
        44 => array("Pune (Camp)", "44", "C",1),
        45 => array("Mira Road (NL Dalmia)","31,45", "W",1),
        46 => array("Virar","19,46", "W",1),
        10 => array("Mumbai (HO) - DLPD", "100", "C",1),
        47 => array("Nagpur (Dharampeth)", "47", "C",1),
        48 => array("Delhi (Kalu Sarai)", "48", "C",1),
        49 => array("Delhi (Punjabi Bagh)", "49", "C",1),
        50 => array("Delhi (Janakpuri)", "50", "C",1),
        51 => array("Delhi (Mayur Vihar)", "51", "C",1),
        52 => array("Pune (FC Road)", "52", "C",1)
    );

    foreach ($centers as $key => $c) {
        $arr = array();
        $arr['id'] = $key;
        $arr['name'] = $c[0];
        $arr['nearbycenters'] = $c[1];
        $arr['zone'] = $c[2];
        $arr['status'] = $c[3];
        $arr['timecreated'] = time();
        $DB->insert_record("raomanager_centers", $arr);
    }

}
xmldb_local_raomanager_install();
