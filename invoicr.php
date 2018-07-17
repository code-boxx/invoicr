<?php
/*
 * INVOICR : THE PHP INVOICE GENERATOR (HTML, DOCX, PDF)
 * Visit https://code-boxx.com/invoicr-php-invoice-generator for more
 */

class Invoicr{
	/* [INVOICE DATA] */
	// YOUR COMPANY DATA - CHANGE THIS TO YOUR OWN
	// FIRST ONE IS THE URL PATH TO THE COMPANY LOGO - USED IN HTML GENERATION
	// SECOND IS THE ABSOLUTE FILE PATH TO THE COMPANY LOGO - USED IN PDF/DOCX GENERATION
	// FOLLOWED BY COMPANY NAME, ADDRESS, CONTACT, WHATEVER YOU WANT TO ADD
	private $company=[
		"http://your-site.com/cb-logo.png",
		"/var/http/your-site.com/cb-logo.png",
		"Company Name", 
		"Street Address, City, State, Zip",
		"Phone: xxx-xxx-xxx | Fax: xxx-xxx-xxx",
		"https://your-site.com",
		"doge@your-site.com"
	];

	// INVOICE INFORMATION
	// TOTALS - NAME, VALUE
	private $invoice=[];

	// BILL & SHIP TO
	// YOU CAN TECHNICALLY PUT WHATEVER INFORMATION YOU WANT
	private $billto=[];
	private $shipto=[];

	// ITEMS - NAME, DESCRIPTION, QTY, PRICE EACH, SUB-TOTAL
	private $items=[];

	// TOTALS - NAME, AMOUNT
	private $totals=[];

	// EXTRA NOTES, IF ANY
	private $notes=[];

	function add($type,$data){
	// add() : add data
	// PARAM $type : type of data, as above
	//       $data : data to add

		if (!is_array($this->$type)) { die("Not a valid data type"); }
		$this->$type[] = $data;
	}

	function set($type,$data){
	// set() : totally replace data
	// PARAM $type : type of data, as above
	//       $data : data to set

		if (!is_array($this->$type)) { die("Not a valid data type"); }
		$this->$type = $data;
	}

	function get($type){
	// get() : get data
	// PARAM $type : type of data, as above

		if (!is_array($this->$type)) { die("Not a valid data type"); }
		return $this->$type;
	}

	/* [TEMPLATE] */
	private $path_template = __DIR__ . DIRECTORY_SEPARATOR . "templates" . DIRECTORY_SEPARATOR ;
	private $template = "simple";
	function template($template="simple"){
	// template() ; use the specified template

		// THE PHYSICAL TEMPLATE SHOULD BE IN THE RESPECTIVE 
		// TEMPLATE/TYPE/$template.php
		$this->template = $template;
	}

	/* [OUTPUT] */
	private $data="";
	function outputDown($file="invoice.html",$size=""){
	// outputDown() : force download headers
	// PARAM $file : filename
	//       $size : file size (optional)

		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename='.$file);
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		if (is_numeric($size)) { header('Content-Length: ' . $size); }
	}

	function outputHTML($mode=1,$save="invoice.html"){
	// outputHTML() : output in HTML
	// PARAM $mode : 1 = show in browser
	//               2 = force download (provide the file name in $save)
	//               3 = save on server (provide the absolute path and file name in $save)
	//       $save : output filename

		// LOAD TEMPLATE FILE
		$file = $this->path_template . "HTML" . DIRECTORY_SEPARATOR . $this->template . ".php";
		if (!file_exists($file)) { die("$file not found."); }
		$this->data = "";
		require $file;

		// OUTPUT
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
				$stream = @fopen($save, 'w');
				if (!$stream) {
				  die("Error opening the file " . $save);
				} else {
				  fwrite($stream, $this->data);
				  if (!fclose($stream)) { die("Error closing ".$save); }
				}
				break;
		}
	}

	function outputPDF($mode=1,$save="invoice.pdf"){
	// outputPDF() : output in PDF
	// PARAM $mode : 1 = show in browser
	//               2 = force download (provide the file name in $save)
	//               3 = save on server (provide the absolute path and file name in $save)
	//       $save : output filename

		// MPDF
		require __DIR__ . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "autoload.php";
		$mpdf = new \Mpdf\Mpdf();

		// LOAD TEMPLATE FILE
		$file = $this->path_template . "PDF" . DIRECTORY_SEPARATOR . $this->template . ".php";
		if (!file_exists($file)) { die("$file not found."); }
		$this->data = "";
		require $file;

		// OUTPUT
		switch ($mode) {
			// SHOW IN BROWSER
			default: case 1:
				$mpdf->Output();
				break;

			// FORCE DOWNLOAD
			case 2:
				$mpdf->Output($save,'D');
				break;

			// SAVE FILE ON SERVER
			case 3:
				$mpdf->Output($save);
				break;
		}
	}

	function outputDOCX($mode=1,$save="invoice.docx"){
	// outputHTML() : output in HTML
	// PARAM $mode : 1 = force download (provide the file name in $save)
	//               2 = save on server (provide the absolute path and file name in $save)
	//       $save : output filename

		// PHPWORD
		require __DIR__ . DIRECTORY_SEPARATOR . "phpword" . DIRECTORY_SEPARATOR . "autoload.php";
		$pw = new \PhpOffice\PhpWord\PhpWord();

		// LOAD TEMPLATE FILE
		$file = $this->path_template . "DOCX" . DIRECTORY_SEPARATOR . $this->template . ".php";
		if (!file_exists($file)) { die("$file not found."); }
		$this->data = "";
		require $file;

		// OUTPUT
		switch ($mode) {
			// FORCE DOWNLOAD
			default: case 1:
				$this->outputDown($save);
				$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($pw, 'Word2007');
				$objWriter->save('php://output');
				break;

			// SAVE FILE ON SERVER
			case 2:
				$pw->save($save, "Word2007");
				break;
		}
	}
}
?>