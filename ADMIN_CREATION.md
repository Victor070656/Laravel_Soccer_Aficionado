# Create Admin Account via Tinker

To create an administrator account for the Soccer Aficionado platform, you can use Laravel Tinker. This is useful for initial setup or when you need to grant admin access to a specific user.

### Tinker Command

Run the following command in your terminal:

```bash
php artisan tinker
```

Then paste the following PHP code:

```php
// Define admin details
$email = 'admin@socceraficionado.com';
$password = 'password'; // Change this to a secure password
$name = 'System Admin';

// 1. Ensure the admin role exists
$adminRole = \App\Models\Role::firstOrCreate(
    ['slug' => 'admin'],
    ['name' => 'Admin', 'description' => 'Full platform administrator']
);

// 2. Create or find the user
$user = \App\Models\User::firstOrCreate(
    ['email' => $email],
    [
        'name' => $name,
        'password' => \Illuminate\Support\Facades\Hash::make($password),
        'email_verified_at' => now(),
    ]
);

// 3. Attach the admin role if not already attached
if (!$user->roles()->where('slug', 'admin')->exists()) {
    $user->roles()->attach($adminRole);
}

echo "Admin account created/verified: {$user->email}\n";
```

### Verification

You can verify the user has admin rights by running:

```php
\App\Models\User::where('email', 'admin@socceraficionado.com')->first()->isAdmin();
// Should return true
```
