<?php
// (A) HTML HEADER & STYLES
$this->data = "<!DOCTYPE html><html><head><style>".
"html,body{font-family:DejaVuSans}#billship,#company{margin-bottom:30px}#company img{max-width:180px;height:auto}#co-left{padding:10px;font-size:.95em;color:#888}#co-right{vertical-align:top;text-align:right;font-size:24px;color:#ea6ca9}#items td,#items th{font-weight:400;border-bottom:3px solid #fff}#billship td,#items td,#items th,#notes{padding:10px}.pink{color:#e671a6}#billship,#company,#items{width:100%;border-collapse:collapse}#billship td{width:33%}#items th{color:#fff;background:#ea6ca9;text-align:left}#items td{background:#fbe3ef}.idesc{color:#ae2e6c}.ttl{color:#af3470}.right{text-align:right}#notes{background:#fbe3ef;margin-top:30px}".
"</style></head><body><div id='invoice'>";

// (B) COMPANY
$this->data .= "<table id='company'><tr><td><img src='".$this->company[0]."'/><div id='co-left'>";
for ($i=2;$i<count($this->company);$i++) {
	$this->data .= $this->company[$i]."<br>";
}
$this->data .= "</div></td><td id='co-right'>SALES INVOICE</td></tr></table>";

// (C) BILL TO
$this->data .= "<table id='billship'><tr><td><strong class='pink'>BILL TO</strong><br>";
foreach ($this->billto as $b) { $this->data .= $b."<br>"; }

// (D) SHIP TO
$this->data .= "</td><td><strong class='pink'>SHIP TO</strong><br>";
foreach ($this->shipto as $s) { $this->data .= $s."<br>"; }

// (E) INVOICE INFO
$this->data .= "</td><td>";
foreach ($this->head as $i) {
	$this->data .= "<strong class='pink'>$i[0]:</strong> $i[1]<br>";
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