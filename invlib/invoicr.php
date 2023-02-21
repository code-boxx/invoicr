<?php
class Invoicr {
  /*** [PART 1] INVOICR DATA ***/
  // (A) FILE PATHS
  private $pathI = __DIR__ . DIRECTORY_SEPARATOR;
  private $pathV = __DIR__ . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR;
  private $pathD = __DIR__ . DIRECTORY_SEPARATOR . "DOCX" . DIRECTORY_SEPARATOR;
  private $pathH = __DIR__ . DIRECTORY_SEPARATOR . "HTML" . DIRECTORY_SEPARATOR;
  private $pathP = __DIR__ . DIRECTORY_SEPARATOR . "PDF" . DIRECTORY_SEPARATOR;

  // (B) FLAGS & TEMP
  private $template = "simple"; // INVOICE TEMPLATE TO USE
  private $data = null; // TEMP DATA TO GENERATE INVOICE

  // (C) INVOICE DATA
  // (C1) COMPANY HEADER - CHANGE TO YOUR OWN!
  private $company = [
    "http://localhost/code-boxx-logo.png", // URL TO COMPANY LOGO, FOR HTML INVOICES
    "D:/http/code-boxx-logo.png", // FILE PATH TO COMPANY LOGO, FOR PDF/DOCX INVOICES
    "Company Name",
    "Street Address, City, State, Zip",
    "Phone: xxx-xxx-xxx | Fax: xxx-xxx-xxx",
    "https://your-site.com",
    "doge@your-site.com"
  ];

  // (C2) HEADERS - INVOICE #, DATE OF PURCHASE, DUE DATE
  private $head = [];

  // (C3) BILL & SHIP TO
  private $billto = [];
  private $shipto = [];

  // (C4) ITEMS - NAME, DESCRIPTION, QTY, PRICE EACH, SUB-TOTAL
  private $items = [];

  // (C5) TOTALS - NAME, AMOUNT
  private $totals = [];

  // (C6) EXTRA FOOTER NOTES, IF ANY
  private $notes = [];

  // (D) INVOICE DATA YOGA
  // (D1) ADD () : ADD INVOICE DATA
  // PARAM $type : type of data (as above - head, billto, items, etc...)
  //       $data : data to add
  function add ($type, $data) {
    if (!isset($this->$type)) { exit("Not a valid data type - $type"); }
    $this->$type[] = $data;
  }

  // (D2) SET() : TOTALLY REPLACE INVOICE DATA
  // PARAM $type : type of data (as above - head, billto, items, etc...)
  //       $data : data to set
  function set ($type, $data) {
    if (!isset($this->$type)) { exit("Not a valid data type - $type"); }
    $this->$type = $data;
  }

  // (D3) GET () : GET INVOICE DATA
  // PARAM $type : type of data (as above - head, billto, items, etc...)
  function get ($type) {
    if (!isset($this->$type)) { exit("Not a valid data type - $type"); }
    return $this->$type;
  }

  // (D4) RESET () : RESET INVOICE DATA
  function reset () {
    $this->company = [];
    $this->head = [];
    $this->billto = [];
    $this->shipto = [];
    $this->items = [];
    $this->totals = [];
    $this->notes = [];
    $this->template = "simple";
    $this->data = null;
  }

  /*** [PART 2] INVOICR TEMPLATE + OUTPUT ***/
  // (E) TEMPLATE () : USE THE SPECIFIED TEMPLATE
  function template ($template="simple") {
    $this->template = $template;
  }

  // (F) OUTPUTDOWN () : HELPER FUNCTION FOR FORCE DOWNLOAD
  //  $file : filename
  //  $size : file size (optional)
  function outputDown ($file="invoice.html", $size="") {
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"$file\"");
    header("Expires: 0");
    header("Cache-Control: must-revalidate");
    header("Pragma: public");
    if (is_numeric($size)) { header("Content-Length: $size"); }
  }

  // (G) OUTPUTHTML () : OUTPUT IN HTML
  //  $mode : 1 = show in browser
  //          2 = force download (provide the file name in $save)
  //          3 = save on server (provide the absolute path and file name in $save)
  //          4 = show in browser + save screenshot as png
  //  $save : output filename
  function outputHTML ($mode=1, $save=null) {
    // (G1) TEMPLATE FILE CHECK
    $fileCSS = $this->pathH . $this->template . ".css";
    $fileHTML = $this->pathH . $this->template . ".php";
    if (!file_exists($fileCSS)) { exit("$fileCSS not found."); }
    if (!file_exists($fileHTML)) { exit("$fileHTML not found."); }

    // (G2) GENERATE HTML INTO BUFFER
    ob_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <style><?php readfile($fileCSS); ?></style>
    <?php if ($mode==4) { ?>
    <script src="invlib/html2canvas.min.js"></script>
    <script>window.onload= () => html2canvas(document.getElementById("invoice")).then(canvas => {
      let a = document.createElement("a");
      <?php if ($save===null) { $save = "invoice-" . strtotime("now") . ".png"; } ?>
      a.download = "<?=$save?>";
      a.href = canvas.toDataURL("image/png");
      a.click();
    });</script>
    <?php } ?>
  </head>
  <body><div id="invoice"><?php require $fileHTML; ?></div></body>
</html>
    <?php
    $this->data = ob_get_contents();
    ob_end_clean();

    // (G3) OUTPUT HTML
    switch ($mode) {
      // (G3-1) OUTPUT ON SCREEN (SAVE TO PNG)
      default: case 1: case 4:
        echo $this->data;
        break;

      // (G3-2) FORCE DOWNLOAD
      case 2:
        if ($save===null) { $save = "invoice-" . strtotime("now") . ".html"; }
        $this->outputDown($save, strlen($this->data));
        echo $this->data;
        break;

      // (G3-3) SAVE TO FILE ON SERVER
      case 3:
        if ($save===null) { $save = "invoice-" . strtotime("now") . ".html"; }
        $stream = @fopen($save, "w");
        if (!$stream) {
          exit("Error opening the file " . $save);
        } else {
          fwrite($stream, $this->data);
          if (!fclose($stream)) { exit("Error closing ".$save); }
        }
      break;
    }
  }

  // (H) OUTPUTPDF() : OUTPUT IN PDF
  // $mode : 1 = show in browser
  //         2 = force download (provide the file name in $save)
  //         3 = save on server (provide the absolute path and file name in $save)
  // $save : output filename
  function outputPDF ($mode=1, $save="invoice.pdf") {
    // (H1) LOAD LIBRARY
    require $this->pathV . "autoload.php";
    $mpdf = new \Mpdf\Mpdf();

    // (H2) LOAD TEMPLATE FILE
    $file = $this->pathP . $this->template . ".php";
    if (!file_exists($file)) { exit("$file not found."); }
    $this->data = "";
    require $file;

    // (H3) OUTPUT
    switch ($mode) {
      // (H3-1) SHOW IN BROWSER
      default: case 1:
        $mpdf->Output();
        break;

      // (H3-2) FORCE DOWNLOAD
      case 2:
        $mpdf->Output($save, "D");
        break;

      // (H3-3) SAVE FILE ON SERVER
      case 3:
        $mpdf->Output($save);
        break;
    }
  }

  // (I) OUTPUTDOCX() : OUTPUT IN DOCX
  //  $mode : 1 = force download (provide the file name in $save)
  //          2 = save on server (provide the absolute path and file name in $save)
  //  $save : output filename
  function outputDOCX ($mode=1, $save="invoice.docx") {
    // (I1) LOAD LIBRARY
    require $this->pathV . "autoload.php";
    $pw = new \PhpOffice\PhpWord\PhpWord();

    // (I2) LOAD TEMPLATE FILE
    $file = $this->pathD . $this->template . ".php";
    if (!file_exists($file)) { exit("$file not found."); }
    $this->data = "";
    require $file;

    // (I3) OUTPUT
    switch ($mode) {
      // (I3-1) FORCE DOWNLOAD
      default: case 1:
        $this->outputDown($save);
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($pw, "Word2007");
        $objWriter->save("php://output");
        break;

      // (I3-2) SAVE FILE ON SERVER
      case 2:
        $pw->save($save, "Word2007");
        break;
    }
  }
}

// (J) "START" INVOICR
$invoicr = new Invoicr();
