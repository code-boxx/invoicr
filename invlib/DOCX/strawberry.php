<?php
// (A) THE TABLE STYLE
$tableStyle = [
	'width' => 5000, 
	'unit' => 'pct', 
	'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER
];

// (B) COMPANY
$section = $pw->addSection();
$table = $section->addTable($tableStyle);
$cell = $table->addRow()->addCell(2500);
$cell->addImage($this->company[1], ['width'=>120]);
for ($i=2;$i<count($this->company);$i++) {
	$cell->addText($this->company[$i], 
		['color' => '888888','size' => "9"], 
		['spaceAfter' => 0]
	);
}
$cell = $table->addCell(2500);
$cell->addText("SALES INVOICE",
	['color'=>"ffffff", 'bgColor'=>"e671a6", 'bold'=>true, 'size'=>20],
	['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]
);

// (C) BILL TO
$section->addText(" ",[],['spaceBefore'=>200]);
$table = $section->addTable($tableStyle);
$cell = $table->addRow()->addCell(1667);
$cell->addText("BILL TO",['bold'=>true,'color'=>"e671a6"],['spaceAfter' => 0, 'spaceBefore'=>0]);
foreach ($this->billto as $b) { 
	$cell->addText($b,[],['spaceAfter' => 0, 'spaceBefore'=>0]);
}

// (D) SHIP TO
$cell = $table->addCell(1667);
$cell->addText("SHIP TO",['bold'=>true,'color'=>"e671a6"],['spaceAfter' => 0, 'spaceBefore'=>0]);
foreach ($this->shipto as $s) { 
	$cell->addText($s,[],['spaceAfter' => 0, 'spaceBefore'=>0]);
}

// (E) INVOICE INFO
$cell = $table->addCell(1666);
foreach ($this->head as $i) {
	$textrun = $cell->addTextRun(['spaceAfter' => 0, 'spaceBefore'=>0]);
	$textrun->addText($i[0].": ",['bold'=>true,'color'=>"e671a6"]);
	$textrun->addText($i[1]);
}

// (F) ITEMS
$section->addText(" ",[],['spaceBefore'=>200]);
$style = ['borderBottomSize' => 15, 'borderBottomColor' => 'FFFFFF', 'bgColor'=>"ea6ca9"];
$table = $section->addTable($tableStyle);
$table->addRow();
$cell = $table->addCell(2000, $style);
$cell->addText("Item",['bold'=>true,'color'=>'FFFFFF']);
$cell = $table->addCell(1000, $style);
$cell->addText("Quantity",['bold'=>true,'color'=>'FFFFFF']);
$cell = $table->addCell(1000, $style);
$cell->addText("Unit Price",['bold'=>true,'color'=>'FFFFFF']);
$cell = $table->addCell(1000, $style);
$cell->addText("Amount",['bold'=>true,'color'=>'FFFFFF']);
$style = ['borderBottomSize' => 15, 'borderBottomColor' => 'FFFFFF', 'bgColor'=>'fbe3ef'];
foreach ($this->items as $i) {
	$table->addRow();
	$cell = $table->addCell(2000, $style);
	$cell->addText($i[0]);
	if ($i[1]!="") { $cell->addText($i[1],['size'=>9,'color'=>'ae2e6c']); }
	$cell = $table->addCell(1000, $style);
	$cell->addText($i[2]);
	$cell = $table->addCell(1000, $style);
	$cell->addText($i[3]);
	$cell = $table->addCell(1000, $style);
	$cell->addText($i[4]);
}

// (G) TOTALS
if (count($this->totals)>0) { foreach ($this->totals as $t) {
	$table->addRow();
	$cell = $table->addCell(4000, array_merge($style, ['gridSpan' => 3]));
	$cell->addText($t[0], ['bold'=>true,'color'=>'af3470']);
	$cell = $table->addCell(1000, $style);
	$cell->addText($t[1], ['bold'=>true,'color'=>'af3470']);
}}

// (H) NOTES
if (count($this->notes)>0) {
	$section->addText(" ");
	$style = ['bgcolor'=>'fbe3ef'];
	$table = $section->addTable($tableStyle);
	$cell = $table->addRow()->addCell(5000, $style);
	foreach ($this->notes as $n) {
		$cell->addText($n);
	}
}