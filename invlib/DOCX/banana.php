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
for ($i=2;$i<count($this->company);$i++) {
	$cell->addText($this->company[$i],
		["color" => "888888","size" => "9"],
		["spaceAfter" => 0]
	);
}

// (C) INVOICE INFO
$cell = $table->addCell(2500);
$cell->addText("SALES INVOICE",
	["color"=>"#d31f1f", "bold"=>true, "size"=>20],
	["spaceAfter" => 10, "spaceBefore"=>0, "alignment" => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]
);
foreach ($this->head as $i) {
	$textrun = $cell->addTextRun(["spaceAfter" => 0, "spaceBefore"=>0, "alignment" => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
	$textrun->addText($i[0].": ",["bold"=>true]);
	$textrun->addText($i[1]);
}
$section->addText(" ",[],["spaceBefore"=>200]);

// (D) BILL TO
$table = $section->addTable($tableStyle);
$style = ["bgColor"=>"fff93b"];
$cell = $table->addRow()->addCell(2500,$style);
$cell->addText("BILL TO",["bold"=>true],["spaceAfter" => 0, "spaceBefore"=>0]);
foreach ($this->billto as $b) {
	$cell->addText($b,[],["spaceAfter" => 0, "spaceBefore"=>0]);
}

// (E) SHIP TO
$cell = $table->addCell(2500,$style);
$cell->addText("SHIP TO",["bold"=>true],["spaceAfter" => 0, "spaceBefore"=>0]);
foreach ($this->shipto as $s) {
	$cell->addText($s,[],["spaceAfter" => 0, "spaceBefore"=>0]);
}

// (F) ITEMS
$section->addText(" ",[],["spaceBefore"=>200]);
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
$style = ["borderBottomSize" => 10, "borderBottomColor" => "f3f3b9", "bgcolor"=>"#ffffe7"];
foreach ($this->items as $i) {
	$table->addRow();
	$cell = $table->addCell(2000, $style);
	$cell->addText($i[0]);
	if ($i[1]!="") { $cell->addText($i[1],["size"=>9,"color"=>"757e39"]); }
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
	$cell = $table->addCell(4000, array_merge($style, ["gridSpan" => 3]));
	$cell->addText($t[0], ["color"=>"#c30d0d", "bold"=>true]);
	$cell = $table->addCell(1000, $style);
	$cell->addText($t[1], ["color"=>"#c30d0d", "bold"=>true]);
}}

// (H) NOTES
if (count($this->notes)>0) {
	$section->addText(" ");
	$table = $section->addTable($tableStyle);
	$cell = $table->addRow()->addCell(5000);
	foreach ($this->notes as $n) {
		$cell->addText($n,[],["spaceAfter" => 0, "spaceBefore"=>0]);
	}
}
