<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function aa_admin_help_page() {
    ?>
    Folgende Platzhalter können Sie in der Amazon Affialiate Einstellungen verwenden
    <h2>Für Produkte sind folgende Platzhalter definiert.</h2>
    <ul>
        <li>#_NAME => Gibt den Produkttitel wieder</li>
        <li>#_DESCRIBITION => Gibt die Produktbeschreibung aus</li>
        <li>#_PRICE => Gibt den Produktpreis wieder</li>
        <li>#_AMAZONURL => Gibt einen Link direkt zum Amazonshop aus</li>
        <li>#_ASIN => Gibt die ASIN aus</li>
        <li>#_EAN => EAN wird ausgegeben</li>
        <li>#_LABEL => Gibt das Produktlabel wieder</li>
        <li>#_MANUFACTURER => Gibt den Hersteller wieder</li>
        <li>#_PRODUCTGROUP => Zeigt die Produktgruppe</li>
        <li>#_PUBLISHER => Publisher wird ausgegeben</li>
        <li>#_STUDIO => Gibt das Studio wieder</li>
        <li>#_AUTHOR => Author wird ausgegeben</li>
        <li>#_ITEMLINK => lokale Produktseite wird durch diesen Link aufgerufen</li>
        <li>#_FEATURELIST => gibt eine Liste von Features aus</li>
        <li>#_MAINPICTURE => Gibt das erste Amazon Produktbild wieder</li>
        <li>#_ALTERNATIVEPICTURE => alternative Bilder werden ausgegeben</li>

    </ul>
    <h2>Für Kategorien sind folgende Platzhalter definiert.</h2>
    <ul>
        <li>#_GERMANNAME => Übersetzung der orignal Amazonkategoriebezeichnung</li>
        <li>#_ENGLISHNAME => Die orignal Bezeichnung von Amazon</li>
        <li>#_CATLINK => Gibt den Link zur Kategorie aus</li>
        <li>#_CATITEMS => Gibt eine Liste der Produkte unter dieser Kategorie an</li>
    </ul>
<?php
}
?>
