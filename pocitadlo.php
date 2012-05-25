<?php
/******************************************************************************/
/* Vypis důležitých parametrů */
function toVsechnoVim() {
    echo "<pre>";
    print_r($_SERVER);
    echo "</pre>";
}  
////////////////////////////////////////////////////////////////////////////////
// Počítadlo //
///////////////

function pocitej() {
    // v proměnné remoteIP uchovej adresu 
    $remoteIP = $_SERVER["REMOTE_ADDR"];

/******************************************************************************/
///////////////////////////////////////////////////////////////////////////////
// Slouží k vybudování asociativního pole pristupy , klíč pole a hodnoty bere z
// txt souboru pocitadlo
// načti z něj hodnoty , aktualizuj je a pak je zpátky zapiš do souboru
///////////////////////////////////////////////////////////////////////////////
/******************************************************************************/
    // Otevři soubor pocitadlo.txt v adresáři w
    // r+ znamená mód pro čtení a pro zápis !    
    $handler = fopen("w/pocitadlo.txt", "r+");
    // Exkluzivně zavři zámek handler aby nebyl pro čtení někým jiným a to nevedlo k pádu
    flock($handler, LOCK_EX);
    // do proměnné (line) přiřď linku z (handleru) (2x %s je tam protoZě rětězce znaků jdou každá do jiné promĚnné listu) 
    while ($line = fscanf($handler, "%s%s\n")) {
        // první string z (line) do (ip) druhý do (pocet)
        list($ip, $pocet) = $line;
        // přiřádí asociativnímu poli klíč (ip) a pak hodnotu (pocet )
        $pristupy[$ip] = $pocet;
    }
/******************************************************************************/    
    // skočím na začátek souboru
    fseek($handler,0);
    // skrátím na nulovou délku
    ftruncate($handler,0);
  // pokuď uŽ element pole přístupů dle ip adresy existuje jen zvíším hodnotu Že tato ip už stránku navštívila 
    if ( isset( $pristupy[$remoteIP] ) ) {
        $pristupy[$remoteIP]++;
  // jinak při prvním přístupu dané ip(uživatele) přiřadím prvku asoc. pole hodnotu 1      
    } else {
        $pristupy[$remoteIP] = 1;
    }
/******************************************************************************/  
/******************************************************************************/  
// aktualizované pole přístupy zapiš z5 do souboru
    //ulozDB($pristupy);
    // zapíšu
    foreach ($pristupy as $ip => $pocet) {
        fputs($handler, $ip . " " . $pocet . "\n");
    }
    // odevřu zámek handler
    //flock($handler, LOCK_UN);
    // zavřu handlera
    fclose($handler);
    // celkem přístupů a poCět unikátníh ip (uživatelů)
    $celkem = 0;
    $unikat = 0;
    
    // pro všechny přístupy dle klíče ip (asociativní pole má klíč)
    foreach ($pristupy as $ip => $pocet) {
        // všechny tyto počty všech ip(uživatelů) přičti do proměnné celkem
        $celkem += $pocet;
        // protože unikátní uživatel je definován unikatním ip, poČet unikátníh uživatelů pak bude 
        // vycházet z počtu elementů asociativního pole pristupy
        $unikat++;
    }
    
    // vrátí pole hodnottt unikat a celkem ty se pak vypíšííííí
    return array($unikat, $celkem);
}

/******************************************************************************/
//  oddělení čtení a psaní není vhodné z důvodu vícenásobného přístupu
function nactiDB() {
    $handle = fopen("w/pocitadlo.txt", "r");
    while ($line = fscanf($handle, "%s%s\n")) {
        list($ip, $pocet) = $line;
        $db[$ip] = $pocet;
    }
    //fclose($handle);
    return $db;
}

/******************************************************************************/
function ulozDB($db) {
    $handler = fopen("w/pocitadlo.txt","w");
    foreach ($db as $ip => $pocet) {
        fputs($handler, $ip . " " . $pocet . "\n");
    }
    //fclose($handler);

}

////////////////////////////////////////////////////////////////////////////////
/// ANKETA ///////////
//////////////////////
function anketa($otazka, $moznosti, $soubor) {
    // když soubor existuje
    if ( file_exists($soubor) ) {
        // do handlera opět nahraj obsah souboru
        $handler = fopen($soubor,'r');
        // linku po lince přiřaď do promĚnné line
        while ($line = fscanf($handler, "%s%s\n")) {
            // protoŽe linka je vŽy tvoŘená dvěma po sobě jdoucími hodnotami
            // jedna se přiřadí do indexu a druhá do ccc
            list($index, $ccc) = $line;
            // asociativní pole klíč index hodnota je pak ccc
            $cetnosti[$index] = $ccc;
        }
        // zavři handlera
       // fclose($handler);
    } else {
         // když soubor neexistuje pro všechny možnosti zapiš do souboru 0
        $handler = fopen($soubor,'w');
        foreach ($moznosti as $index=>$mmm) {
            fputs($handler, "$index 0\n");
        }
        fclose($handler);
    }
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////    
    // pokuď bylo kliknuto zvyš tu danou Četnost dle kliknutého elementu
    if ( isset($_GET[$soubor]) ) {
        $cetnosti[$_GET[$soubor]]++;       
        $handler = fopen($soubor,'w');
        foreach ($cetnosti as $index=>$ccc) {
            fputs($handler, "$index $ccc\n");
        }
      //  fclose($handler);
    } 
////////////////////////////////////////////////////////////////////////////////
// graficky to znázornii
    echo "<strong>$otazka</strong><br />" ; 
    echo "<ul>" ; 
    foreach ($moznosti as $index=>$mmm) {

        $sirka =300 * $cetnosti[$index] / max($cetnosti);
        echo "<li><a href=\"index.php?$soubor=$index\">$mmm</a>: <br/><span style=\"background-color:#6789ab; display:block; width:${sirka}px;\">$cetnosti[$index]</span></li>" ; 
    }
    echo "</ul>" ; 


    echo "<pre>";
    print_r($_GET);
    print_r($cetnosti);
    echo "</pre>"; 

}



?>