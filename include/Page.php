<?

class Page
{
	public function pageSlug() { return "blank"; }
	public function pageFlags() { return array('navigation'); }
	public function pageTitle() { return "Blank Page"; }
	public function pageContents() { return "<h1>Blank Page</h1><p>This page is blank</p>"; }
	public function pageStyle() { return ""; }
	public function pageScript() { return ""; }
}

?>