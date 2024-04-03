# SweetProcess API PHP Wrapper

This PHP script demonstrates how to use the SweetProcess API to interact with various endpoints and perform operations such as retrieving procedures, task instances, users, and managing invitations and team memberships.

## Prerequisites

- PHP installed on your system
- SweetProcess API token

## Installation

1. Clone the repository or download the script files.
2. Include the `SweetProcessAPI.php` file in your project.

## Usage

1. Create an instance of the `SweetProcessAPI` class with your API token:

```php
require_once 'SweetProcessAPI.php';

$token = 'YOUR_API_TOKEN';
$api = new SweetProcessAPI($token);
```

2. Use the available methods to interact with the SweetProcess API.

### Get Procedures

Retrieve a list of procedures with filters:

```php
$filters = [
    'team_id' => 200000,
    'search' => 'quarterly',
    'tag' => 'accounts,tax',
    'ordering' => '-rank'
];
$procedures = $api->getProcedures($filters);
```

### Get Task Instances

Retrieve a list of task instances with filters:

```php
$filters = [
    'completed' => 'false',
    'due__gte' => '2020-01-01T00:00:00Z',
    'due__lte' => '2020-02-01T00:00:00Z'
];
$taskInstances = $api->getTaskInstances($filters);
```

### Get Users

Retrieve a list of users with filters:

```php
$filters = [
    'team_id' => 200000,
    'status' => 'email_verified'
];
$users = $api->getUsers($filters);
```

### Invite a New User

Invite a new user to the account:

```php
$userData = [
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'is_super_manager' => 0
];
$newUser = $api->inviteUser($userData);
```

### Update User Information

Update a user's information:

```php
$userId = '123456';
$updateData = [
    'is_billing_admin' => true
];
$updatedUser = $api->updateUser($userId, $updateData);
```

### Remove a User

Remove a user from the account:

```php
$userId = '123456';
$success = $api->deleteUser($userId);
```

### Invite a User to a Team

Invite a user to a team:

```php
$invitationData = [
    'send_mail' => false,
    'content_type' => 'team',
    'permission' => 'view',
    'object_id' => 118482,
    'to_user_id' => 'https://www.sweetprocess.com/api/v1/users/32010/'
];
$invitation = $api->inviteToTeam($invitationData);
```

### Remove a User from a Team

Remove a user from a team:

```php
$teamUserId = '4059787';
$success = $api->removeFromTeam($teamUserId);
```

## Contributing

Contributions are welcome! Please open an issue or submit a pull request on the GitHub repository.

## License

This project is licensed under the [Insert License Name].
