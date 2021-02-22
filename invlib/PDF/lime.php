<?php
// (A) HTML HEADER & STYLES
$this->data = "<!DOCTYPE html><html><head><style>".
"html,body{font-family:DejaVuSans}#invoice{max-width:800px;margin:0 auto}#company,#billship{margin-bottom:30px}#billship,#company,#items{width:100%;border-collapse:collapse}#company td,#billship td,#items td,#items th{padding:10px}#company td{vertical-align:top}#company img{max-width:180px;height:auto}#co-info{font-size:.95em;color:#888;margin-top:10px}#co-right{text-align:right}#bigi{font-size:28px;font-weight:700;color:#bcd030}#invinfo{margin-top:10px}#billship td{width:50%;background:#d0ec0e}#items th{background:#d0ec0e;text-align:left}#items td{border-bottom:1px solid #dce4a5;background:#f8ffc7}.idesc{color:#757e39}.ttl{font-weight:700}.right{text-align:right}#notes{margin-top:30px;font-size:.95em}".
"</style></head><body><div id='invoice'>";

// (B) COMPANY
$this->data .= "<table id='company'><tr><td><img src='".$this->company[0]."'/><div id='co-info'>";
for ($i=2;$i<count($this->company);$i++) {
	$this->data .= $this->company[$i]."<br/>";
}
$this->data .= "</div></td><td id='co-right'><div id='bigi'>INVOICE</div><div id='invinfo'>";

// (C) INVOICE INFORMATION
foreach ($this->head as $i) {
	$this->data .= "<strong>$i[0]:</strong> $i[1]<br>";
}
$this->data .= "</div></td></tr></table>";

// (D) BILL TO
$this->data .= "<table id='billship'><tr><td><strong>BILL TO</strong><br>";
foreach ($this->billto as $b) { $this->data .= $b."<br>"; }

// (E) SHIP TO
$this->data .= "</td><td><strong>SHIP TO</strong><br>";
foreach ($this->shipto as $s) { $this->data .= $s."<br>"; }
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