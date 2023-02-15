<!-- (A) HEADER -->
<table id="company"><tr>
	<td>
		<img src="<?=$this->company[0]?>">
		<div id="co-info"><?php
			for ($i=2; $i<count($this->company); $i++) { echo "{$this->company[$i]}<br>"; }
		?>
	  </div>
	</td>
	<td id="co-right">
		<div id="bigi">INVOICE</div>
		<div id="invinfo"><?php
			foreach ($this->head as $i) { echo "<strong>{$i[0]}</strong> {$i[1]}<br>"; }
	  ?></div>
	</td>
</tr></table>

<!-- (B) BILL TO & SHIP TO -->
<table id="billship"><tr>
	<td>
		<strong>BILL TO</strong><br>
		<?php foreach ($this->billto as $b) { echo "$b<br>"; } ?>
	</td>
	<td>
		<strong>SHIP TO</strong><br>
		<?php foreach ($this->shipto as $s) { echo "$s<br>"; } ?>
  </td>
</tr></table>

<!-- (C) ITEMS & TOTALS -->
<table id="items">
	<tr><th>Item</th><th>Quantity</th><th>Unit Price</th><th>Amount</th></tr>
	<?php
	foreach ($this->items as $i) {
		echo "<tr><td><div>{$i[0]}</div>";
		if ($i[1]!="") { echo "<small class='idesc'>$i[1]</small>"; }
		echo "</td><td>{$i[2]}</td><td>{$i[3]}</td><td>{$i[4]}</td></tr>";
	}
	if (count($this->totals)>0) { foreach ($this->totals as $t) {
		echo "<tr class='ttl'><td class='right' colspan='3'>{$t[0]}</td><td>{$t[1]}</td></tr>";
	}}
	?>
</table>

<!-- (D) NOTES -->
<?php if (count($this->notes)>0) { ?>
<div id="notes"><?php
	foreach ($this->notes as $n) { echo "$n<br>"; }
?></div>
<?php } ?>