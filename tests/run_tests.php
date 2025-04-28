<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/ApiTest.php';

use Tests\ApiTest;

class TestRunner
{
    private $results = [];
    private $totalTests = 0;
    private $passedTests = 0;
    private $failedTests = 0;

    public function run()
    {
        echo "Starting API Tests...\n\n";

        $testClass = new ApiTest();
        $methods = get_class_methods($testClass);

        foreach ($methods as $method) {
            if (strpos($method, 'test') === 0) {
                $this->totalTests++;
                echo "Running {$method}... ";
                
                try {
                    $testClass->$method();
                    $this->results[$method] = ['status' => 'passed', 'error' => null];
                    $this->passedTests++;
                    echo "✓ Passed\n";
                } catch (\Exception $e) {
                    $this->results[$method] = ['status' => 'failed', 'error' => $e->getMessage()];
                    $this->failedTests++;
                    echo "✗ Failed\n";
                }
            }
        }

        $this->displaySummary();
    }

    private function displaySummary()
    {
        echo "\nTest Summary:\n";
        echo "Total Tests: {$this->totalTests}\n";
        echo "Passed: {$this->passedTests}\n";
        echo "Failed: {$this->failedTests}\n";
        echo "Success Rate: " . round(($this->passedTests / $this->totalTests) * 100, 2) . "%\n\n";

        if ($this->failedTests > 0) {
            echo "Failed Tests:\n";
            foreach ($this->results as $test => $result) {
                if ($result['status'] === 'failed') {
                    echo "- {$test}: {$result['error']}\n";
                }
            }
        }
    }
}

// Run the tests
$runner = new TestRunner();
$runner->run(); 