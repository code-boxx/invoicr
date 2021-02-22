<?php
// (A) HTML HEADER & STYLES
$this->data .= "<!DOCTYPE html><html><head><style>".
"html,body{font-family:DejaVuSans}#invoice{max-width:800px;margin:0 auto}#billship,#company,#items{width:100%;border-collapse:collapse}#company td,#billship td,#items td,#items th{padding:15px}#company,#billship{margin-bottom:30px}#company img{max-width:180px;height:auto}#bigi{font-size:28px;color:#ad132f}#billship{background:#b92d2d;color:#fff}#billship td{width:33%}#items th{text-align:left;border-top:2px solid #f6a5a5;border-bottom:2px solid #f6a5a5}#items td{border-bottom:1px solid #f6a5a5}.idesc{color:#ca3f3f}.ttl{font-weight:700}.right{text-align:right}#notes{margin-top:30px;font-size:.95em}".
"</style></head><body><div id='invoice'>";

// (B) COMPANY
$this->data .= "<table id='company'><tr><td><img src='".$this->company[0]."'/></td><td class='right'>";
for ($i=2;$i<count($this->company);$i++) {
	$this->data .= "<div>".$this->company[$i]."</div>";
}
$this->data .= "</td></tr></table>";
$this->data .= "<div id='bigi'>SALES INVOICE</div>";

// (C) BILL TO
$this->data .= "<table id='billship'><tr><td><strong>BILL TO</strong><br>";
foreach ($this->billto as $b) { $this->data .= $b."<br>"; }

// (D) SHIP TO
$this->data .= "</td><td><strong>SHIP TO</strong><br>";
foreach ($this->shipto as $s) { $this->data .= $s."<br>"; }

// (E) INVOICE INFO
$this->data .= "</td><td>";
foreach ($this->head as $i) {
	$this->data .= "<strong>$i[0]:</strong> $i[1]<br>";
}
$this->data .= "</td></tr></table>";

// (F) ITEMS
$this->data .= "<table id='items'><tr><th>Item</th><th>Quantity</th><th>Unit Price</th><th>Amount</th></tr>";
foreach ($this->items as $i) {
	$this->data .= "<tr><td><div>".$i[0]."</div>".($i[1]==""?"":"<small class='idesc'>$i[1]</small>")."</td><td>".$i[2]."</td><td>".$i[3]."</td><td>".$i[4]."</td></tr>";
}

// (G) TOTALS
if (count($this->totals)>0) { foreach ($this->totals as $t) {
	$this->data .= "<tr class='ttl'><td class='right' colspan='3'>$t[0]</td><td>$t[1]</td></tr>";
}}
$this->data .= "</table>";


// (H) NOTES
if (count($this->notes)>0) {
	$this->data .= "<div id='notes'>";
	foreach ($this->notes as $n) {
		$this->data .= $n."<br>";
	}
	$this->data .= "</div>";
}

// (I) CLOSE
$this->data .= "</div></body></html>";
$mpdf->WriteHTML($this->data);