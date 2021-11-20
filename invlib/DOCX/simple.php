<?php
// (A) THE TABLE STYLE
$tableStyle = [
	"width" => 5000,
	"unit" => "pct",
	"alignment" => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER
];

// (B) COMPANY
$section = $pw->addSection();
$table = $section->addTable($tableStyle);
$cell = $table->addRow()->addCell(2500);
$cell->addImage($this->company[1], ["width"=>120]);
$cell = $table->addCell(2500);
for ($i=2;$i<count($this->company);$i++) {
	$cell->addText($this->company[$i], [], [
		"spaceAfter" => 0,
		"alignment" => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]
	);
}

// (C) BIG SALES INVOICE
$section->addText("SALES INVOICE",
	["color"=>"ad132f", "bold"=>true, "size"=>20],
	["spaceAfter" => 500, "spaceBefore"=>500]);

// (D) BILL TO
$table = $section->addTable($tableStyle);
$cell = $table->addRow()->addCell(1667);
$cell->addText("BILL TO",["bold"=>true],["spaceAfter" => 0, "spaceBefore"=>0]);
foreach ($this->billto as $b) {
	$cell->addText($b,[],["spaceAfter" => 0, "spaceBefore"=>0]);
}

// (E) SHIP TO
$cell = $table->addCell(1667);
$cell->addText("SHIP TO",["bold"=>true],["spaceAfter" => 0, "spaceBefore"=>0]);
foreach ($this->shipto as $s) {
	$cell->addText($s,[],["spaceAfter" => 0, "spaceBefore"=>0]);
}

// (F) INVOICE INFO
$cell = $table->addCell(1666);
foreach ($this->head as $i) {
	$textrun = $cell->addTextRun(["spaceAfter" => 0, "spaceBefore"=>0]);
	$textrun->addText($i[0].": ",["bold"=>true]);
	$textrun->addText($i[1]);
}

// (G) ITEMS
$section->addText(" ",[],["spaceBefore"=>500]);
$style = [
	"borderBottomSize" => 18, "borderBottomColor" => "000000",
	"borderTopSize" => 18, "borderTopColor" => "000000"
];
$table = $section->addTable($tableStyle);
$table->addRow();
$cell = $table->addCell(2000, $style);
$cell->addText("Item",["bold"=>true]);
$cell = $table->addCell(1000, $style);
$cell->addText("Quantity",["bold"=>true]);
$cell = $table->addCell(1000, $style);
$cell->addText("Unit Price",["bold"=>true]);
$cell = $table->addCell(1000, $style);
$cell->addText("Amount",["bold"=>true]);
$style = ["borderBottomSize" => 10, "borderBottomColor" => "EEEEEE"];
foreach ($this->items as $i) {
	$table->addRow();
	$cell = $table->addCell(2000, $style);
	$cell->addText($i[0]);
	if ($i[1]!="") { $cell->addText($i[1],["size"=>9,"color"=>"999999"]); }
	$cell = $table->addCell(1000, $style);
	$cell->addText($i[2]);
	$cell = $table->addCell(1000, $style);
	$cell->addText($i[3]);
	$cell = $table->addCell(1000, $style);
	$cell->addText($i[4]);
}

// (H) TOTALS
$style = ["borderBottomSize" => 10, "borderBottomColor" => "EEEEEE", "bgcolor"=>"FAFAFA"];
if (count($this->totals)>0) { foreach ($this->totals as $t) {
	$table->addRow();
	$cell = $table->addCell(4000, array_merge($style, ["gridSpan" => 3]));
	$cell->addText($t[0], ["bold"=>true]);
	$cell = $table->addCell(1000, $style);
	$cell->addText($t[1], ["bold"=>true]);
}}

// (I) NOTES
if (count($this->notes)>0) {
	$section->addText(" ");
	$style = ["bgcolor"=>"FAFAFA"];
	$table = $section->addTable($tableStyle);
	$cell = $table->addRow()->addCell(5000, $style);
	foreach ($this->notes as $n) {
		$cell->addText($n);
	}
}
