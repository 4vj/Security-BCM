<?php
use PHPUnit\Framework\TestCase;

// Include the file you want to test
require_once __DIR__ . "/../intranet/klantenservice/index.php";

class UnitTest extends TestCase
{
    // Test case to check if the search function retrieves user information correctly
    public function testSearchFunction(): void
    {
        $_POST['klantid'] = 2082720328;
        $_POST['type'] = 'Elektriciteit'; 

        function getuserinfo($uid)
        {
            // Simulate a user info array
            return array(2082720328, 'Aylin', 'Adusei-Poku', 'aylin@example.com', 11776);
        }

        // Mocking the getadresinfo function
        function getadresinfo($aid)
        {
            // Simulate an address info array
            return array(10, 'Empe', 'Brummen', 'Gelderland', 'Oost-Nederland', 'Goudestein', '30', '1203LS');
        }

        // Mocking the getmetertelwerkid and getmeterstanden functions
        function getmetertelwerkid($aid, $type)
        {
            // Simulate a meter telwerk ID
            return array(1);
        }

        function getmeterstanden($metertelwerkid)
        {
            // Simulate meter stand array
            return array(array(1, 1, '2876', '2016-01-08', '22:40:59'));
        }

        // Start output buffering to capture HTML output
        ob_start();

        // Call the search function to execute the test
        search();

        // Get the content of the output buffer
        $output = ob_get_clean();

        // Assertions
        $this->assertStringContainsString('<td>Aylin</td>', $output); // Check if user's first name is displayed
        $this->assertStringContainsString('<td>Empe</td>', $output); // Check if city is displayed
        $this->assertStringContainsString('<th scope="col">Meterstand (<?echo $type?>)</th>', $output); // Check if meter type is displayed
        $this->assertStringContainsString('<td>2876</td>', $output); // Check if meter stand is displayed
    }
}
?>
