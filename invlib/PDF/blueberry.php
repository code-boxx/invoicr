<?php
// (A) HTML HEADER & STYLES
$this->data = "<!DOCTYPE html><html><head><style>".
"html,body{font-family:DejaVuSans}#company,#billship{margin-bottom:30px}#billship,#company,#items{width:100%;border-collapse:collapse}#company td,#billship td,#items td,#items th{padding:10px}#company td{vertical-align:top}#bigi{margin-bottom:20px;font-size:28px;font-weight:700;color:#258ec7}#co-addr{font-size:.95em;color:#888}#co-right img{max-width:180px;height:auto}#billship td{width:33%}#items th{text-align:left;background:#98c5dc;padding:20px 10px}#items td{background:#e4eff5;border-bottom:1px solid #c8d2d7}.idesc{color:#6099b6}#items tr.ttl td{background:#98c5dc;border-bottom:none;font-weight:700}.right{text-align:right}#notes{background:#e4eff5;padding:10px;margin-top:30px}".
"</style></head><body><div id='invoice'>";

// (B) COMPANY
$this->data .= "<table id='company'><tr><td id='co-left'><div id='bigi'>SALES INVOICE</div><div id='co-addr'>";
for ($i=2;$i<count($this->company);$i++) {
	$this->data .= $this->company[$i]."<br>";
}
$this->data .= "</div></td><td id='co-right' class='right'><img src='".$this->company[0]."'/></td></table>";

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
$this->data .= "<table id='items'><tr><th>ITEM</th><th>QUANTITY</th><th>UNIT PRICE</th><th>AMOUNT</th></tr>";
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