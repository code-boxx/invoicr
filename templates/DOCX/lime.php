<?php
/*
 * LIME DOCX INVOICE THEME
 * Visit https://code-boxx.com/invoicr-php-invoice-generator for more
 */

// THE TABLE STYLE
$tableStyle = [
	'width' => 5000, 
	'unit' => 'pct', 
	'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER
];

// COMPANY LOGO + INFORMATION
$section = $pw->addSection();
$table = $section->addTable($tableStyle);
$cell = $table->addRow()->addCell();
$cell->addImage($this->company[1], ['width'=>120]);
for ($i=2;$i<count($this->company);$i++) {
	$cell->addText($this->company[$i], 
		['color' => '888888','size' => "9"], 
		['spaceAfter' => 0]
	);
}

// INVOICE INFO
$cell = $table->addCell();
$cell->addText("SALES INVOICE",
	['color'=>"bcd030", 'bold'=>true, 'size'=>20],
	['spaceAfter' => 10, 'spaceBefore'=>0, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]
);

foreach ($this->invoice as $i) {
	$textrun = $cell->addTextRun(['spaceAfter' => 0, 'spaceBefore'=>0, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
	$textrun->addText($i[0].": ",['bold'=>true]);
	$textrun->addText($i[1]);
}

$section->addText(" ",[],['spaceBefore'=>200]);

// BILL TO
$table = $section->addTable($tableStyle);
$style = ['bgColor'=>"d0ec0e"];
$cell = $table->addRow()->addCell(2500,$style);
$cell->addText("BILL TO",['bold'=>true],['spaceAfter' => 0, 'spaceBefore'=>0]);
foreach ($this->billto as $b) { 
	$cell->addText($b,[],['spaceAfter' => 0, 'spaceBefore'=>0]);
}

// SHIP TO
$cell = $table->addCell(2500,$style);
$cell->addText("SHIP TO",['bold'=>true],['spaceAfter' => 0, 'spaceBefore'=>0]);
foreach ($this->shipto as $s) { 
	$cell->addText($s,[],['spaceAfter' => 0, 'spaceBefore'=>0]);
}

// ITEMS
$section->addText(" ",[],['spaceBefore'=>200]);
$table = $section->addTable($tableStyle);
$table->addRow();
$cell = $table->addCell(2000, $style);
$cell->addText("Item",['bold'=>true]);
$cell = $table->addCell(1000, $style);
$cell->addText("Quantity",['bold'=>true]);
$cell = $table->addCell(1000, $style);
$cell->addText("Unit Price",['bold'=>true]);
$cell = $table->addCell(1000, $style);
$cell->addText("Amount",['bold'=>true]);
$style = ['borderBottomSize' => 10, 'borderBottomColor' => 'dce4a5', 'bgcolor'=>'f8ffc7'];
foreach ($this->items as $i) {
	$table->addRow();
	$cell = $table->addCell(2000, $style);
	$cell->addText($i[0]);
	if ($i[1]!="") { $cell->addText($i[1],['size'=>9,'color'=>'757e39']); }
	$cell = $table->addCell(1000, $style);
	$cell->addText($i[2]);
	$cell = $table->addCell(1000, $style);
	$cell->addText($i[3]);
	$cell = $table->addCell(1000, $style);
	$cell->addText($i[4]);
}

// TOTALS
if (count($this->totals)>0) { foreach ($this->totals as $t) {
	$table->addRow();
	$cell = $table->addCell(4000, array_merge($style, ['gridSpan' => 3]));
	$cell->addText($t[0], ['bold'=>true]);
	$cell = $table->addCell(1000, $style);
	$cell->addText($t[1], ['bold'=>true]);
}}

// NOTES
if (count($this->notes)>0) {
	$section->addText(" ");
	$table = $section->addTable($tableStyle);
	$cell = $table->addRow()->addCell();
	foreach ($this->notes as $n) {
		$cell->addText($n,[],['spaceAfter' => 0, 'spaceBefore'=>0]);
	}
}
?>