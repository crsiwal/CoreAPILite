# Photo Album System API Tests

This directory contains the test suite for the Photo Album System API. The tests are designed to verify the functionality of all API endpoints and ensure the system works as expected.

## Prerequisites

Before running the tests, ensure you have:

1. PHP 7.4 or higher installed
2. Composer installed
3. The API server running locally
4. All required dependencies installed

## Installation

1. Install PHPUnit:
```bash
composer require --dev phpunit/phpunit
```

2. Install other dependencies:
```bash
composer install
```

## Running the Tests

To run all tests:

```bash
php tests/run_tests.php
```

## Test Structure

The test suite includes the following test categories:

1. **User Management Tests**
   - User registration
   - User login
   - Profile management

2. **Album Management Tests**
   - Album creation
   - Album listing
   - Album updates

3. **Photo Management Tests**
   - Photo upload
   - Photo listing
   - Photo updates

4. **Tag System Tests**
   - Tag creation
   - Tagging photos
   - Tag management

5. **Person Recognition Tests**
   - Person creation
   - Tagging persons in photos
   - Person management

6. **Like System Tests**
   - Liking photos
   - Unlike photos
   - Like counting

7. **Options Management Tests**
   - Setting options
   - Retrieving options
   - Option updates

8. **Activity Logging Tests**
   - Log creation
   - Log retrieval
   - Recent logs

## Test Results

The test runner will display results in the following format:

```
Starting API Tests...

✓ testUserRegistration - PASSED
✓ testUserLogin - PASSED
✓ testCreateAlbum - PASSED
...

Test Summary:
=============
Total Tests: 20
Passed: 20
Failed: 0
Success Rate: 100%

```

### Understanding Test Results

- **✓** indicates a passed test
- **✗** indicates a failed test
- Each test result includes:
  - Test name
  - Status (PASSED/FAILED)
  - Error message (if failed)

### Failed Tests

If any tests fail, the test runner will display:
1. The name of the failed test
2. The error message
3. A summary of all failed tests at the end

## Troubleshooting

If you encounter issues:

1. **API Connection Issues**
   - Ensure the API server is running
   - Verify the base URL in `ApiTest.php` is correct
   - Check network connectivity

2. **Authentication Issues**
   - Verify the authentication token is being generated correctly
   - Check if the token is being passed in the headers
   - Ensure the user credentials are valid

3. **Test Failures**
   - Check the error messages for specific issues
   - Verify the test data is valid
   - Ensure all required fields are provided

## Best Practices

1. Run tests before deploying changes
2. Add new tests for new features
3. Update existing tests when API changes
4. Keep test data clean and isolated
5. Use meaningful test names
6. Document any test-specific requirements

## Adding New Tests

To add a new test:

1. Open `ApiTest.php`
2. Add a new public method starting with "test"
3. Implement the test logic
4. Add assertions to verify the results
5. Run the test suite to verify

Example:
```php
public function testNewFeature()
{
    $data = [
        // Test data
    ];

    $response = $this->makeRequest('POST', '/api/new-feature', $data, true);
    $this->assertEquals(200, $response['status']);
    $this->assertEquals('success', $response['data']['status']);
}
```

## Continuous Integration

The test suite can be integrated into your CI/CD pipeline:

1. Add the test command to your CI configuration
2. Set up environment variables if needed
3. Configure test reporting
4. Set up notifications for test failures

## Support

For issues or questions about the test suite:
1. Check the documentation
2. Review the test code
3. Contact the development team 