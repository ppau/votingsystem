<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
require_once('simpletest/autorun.php');
class AllTests extends TestSuite {
    function AllTests() {
        $this->TestSuite('All tests');
        $this->addFile('/Users/admin/Sites/test/finitefield.php');
		$this->addFile('/Users/admin/Sites/test/ellipticCurve.php');
		$this->addFile('/Users/admin/Sites/test/SHA.php');
		$this->addFile('/Users/admin/Sites/test/base64.php');
    }
}
?>