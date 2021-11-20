<?php
class Invoicr {
  /*** [I] INVOICR DATA ***/
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

  /*** [II] INVOICR TEMPLATE + OUTPUT ***/
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
  //  $save : output filename
  function outputHTML ($mode=1, $save="invoice.html") {
    // (G1) LOAD TEMPLATE FILE
    $file = $this->pathH . $this->template . ".php";
    if (!file_exists($file)) { exit("$file not found."); }
    $this->data = "";
    require $file;

    // (G2) OUTPUT HTML
    switch ($mode) {
      // OUTPUT ON SCREEN
      default: case 1:
        echo $this->data;
        break;

      // FORCE DOWNLOAD
      case 2:
        $this->outputDown($save, strlen($this->data));
        echo $this->data;
        break;

      // SAVE TO FILE ON SERVER
      case 3:
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
      // SHOW IN BROWSER
      default: case 1:
        $mpdf->Output();
        break;

      // FORCE DOWNLOAD
      case 2:
        $mpdf->Output($save, "D");
        break;

      // SAVE FILE ON SERVER
      case 3:
        $mpdf->Output($save);
        break;
    }
  }

  // (I) OUTPUTDOCX() : OUTPUT IN DOCX
  //  $mode : 1 = force download (provide the file name in $save)
  //         2 = save on server (provide the absolute path and file name in $save)
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
      // FORCE DOWNLOAD
      default: case 1:
        $this->outputDown($save);
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($pw, "Word2007");
        $objWriter->save("php://output");
        break;

      // SAVE FILE ON SERVER
      case 2:
        $pw->save($save, "Word2007");
        break;
    }
  }
}
$invoicr = new Invoicr();
