<?php
 
class Item extends Model {
	const title = "Items Page";
	public function title() {
		return $this::title;
	}
}