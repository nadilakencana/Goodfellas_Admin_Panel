# AuthController Documentation

## Overview
The `AuthController` is responsible for handling all authentication-related operations, user management, admin management, and access level control in the Goodfellas Admin Panel system.

## Dependencies
- `Illuminate\Http\Request`
- `App\Models\Admin`
- `App\Models\User`
- `App\Models\Level`
- `Sentinel` (Cartalyst Sentinel)
- `Illuminate\Support\Facades\Hash`
- `Session`

## Class Structure
```php
class AuthController extends Controller
```

## Methods Documentation

### Authentication Methods

#### 1. login()
**Purpose**: Display the login form view.

**Parameters**: None

**Returns**: View (`Auth.login`)

**Usage**:
```php
Route::get('/login', [AuthController::class, 'login'])->name('login');
```

---

#### 2. pushlogin(Request $request)
**Purpose**: Process user login authentication using Sentinel.

**Parameters**:
- `$request->email` (string, required) - User email
- `$request->password` (string, required) - User password
- `$request->remember` (boolean, optional) - Remember me option

**Process Flow**:
1. Find admin by email
2. Create credentials array with email, password, and admin ID
3. Authenticate using Sentinel
4. Handle "remember me" functionality
5. Redirect to dashboard on success

**Returns**:
- Success: Redirect to dashboard
- Failure: Redirect back with error message

**Error Handling**:
- "Password salah" - Wrong password
- "Akun tidak terdaftar" - Account not registered

**Code Example**:
```php
$credentials = [
    'email' => $request->email,
    'password' => $request->password,
    'id' => $admin->id
];

if (Sentinel::authenticate($credentials)) {
    if($request->remember){
        Sentinel::loginAndRemember($user);
    } else {
        Sentinel::login($user);
    }
    return redirect()->route('Dashboard');
}
```

---

#### 3. logOut()
**Purpose**: Handle user logout and session cleanup.

**Parameters**: None

**Process**:
1. Logout user using Sentinel
2. Flush all session data
3. Redirect to login page

**Returns**: Redirect to login route

---

### User Management Methods

#### 4. DataUser()
**Purpose**: Display all users in the system.

**Authentication**: Requires Sentinel authentication

**Returns**: 
- Authenticated: View with user data (`DataUser.dataUser`)
- Unauthenticated: Redirect to login

**Data Passed to View**:
- `$user` - Collection of all User models

---

### Admin Management Methods

#### 5. DataAdmin()
**Purpose**: Display all admin users.

**Authentication**: Requires Sentinel authentication

**Returns**:
- Authenticated: View with admin data (`dataAdmin.dataAdmin`)
- Unauthenticated: Redirect to login

**Data Passed to View**:
- `$admin` - Collection of all Admin models

---

#### 6. editDataAdmin($id)
**Purpose**: Display form to edit admin user data.

**Parameters**:
- `$id` (encrypted string) - Encrypted admin ID

**Process**:
1. Decrypt the admin ID
2. Find admin by decrypted ID
3. Get all available levels

**Returns**:
- Authenticated: Edit form view (`dataAdmin.updateData`)
- Unauthenticated: Redirect to login

**Data Passed to View**:
- `$admin` - Admin model instance
- `$level` - Collection of all Level models

---

#### 7. udpdateDataAdmin(Request $request, $id)
**Purpose**: Update admin user data.

**Parameters**:
- `$request->nama` (string, required) - Admin name
- `$request->email` (string, required) - Admin email
- `$request->id_level` (integer, required) - Level ID
- `$id` (encrypted string) - Encrypted admin ID

**Validation Rules**:
```php
$request->validate([
    'nama'=> 'required',
    'email'=> 'required',
    'id_level' => 'required'
]);
```

**Process**:
1. Decrypt admin ID
2. Find admin by ID
3. Update admin data
4. Save changes

**Returns**:
- Success: Redirect to dataAdmin with success message
- Failure: Redirect back with error message

---

#### 8. deleteDataAdmin($id)
**Purpose**: Delete admin user from system.

**Parameters**:
- `$id` (encrypted string) - Encrypted admin ID

**Process**:
1. Decrypt admin ID
2. Find admin by ID
3. Delete admin record

**Returns**: Redirect back with success message

**Security Note**: Hard delete operation - permanently removes admin from database.

---

#### 9. ResetPassword(Request $request)
**Purpose**: Reset admin user password.

**Parameters**:
- `$request->email` (string, required) - Admin email
- `$request->password` (string, required) - New password

**Validation**:
```php
$this->validate($request, ['email'=> 'required']);
$this->validate($request,['password' => 'required']);
```

**Process**:
1. Find admin by email using LIKE operator
2. Hash new password using bcrypt
3. Update admin password
4. Save changes

**Returns**:
- Success: Redirect to dataAdmin with success message
- Failure: Redirect back with error message

**Security Features**:
- Password hashing with bcrypt
- Email-based user identification

---

### Level Management Methods

#### 10. levelLog()
**Purpose**: Display all user access levels.

**Authentication**: Requires Sentinel authentication

**Returns**:
- Authenticated: View with level data (`LevelLog.dataLevel`)
- Unauthenticated: Redirect to login

**Data Passed to View**:
- `$levelLog` - Collection of all Level models

---

#### 11. createLevel(Request $request)
**Purpose**: Create new user access level.

**Parameters**:
- `$request->level` (string, required) - Level name

**Validation**:
```php
$request->validate([
    'level'=> 'required'
]);
```

**Process**:
1. Create new Level using mass assignment
2. Save level to database

**Returns**:
- Success: Redirect to LevelLog with success message
- Failure: Redirect back with error message

---

#### 12. UpdateLevel(Request $request, $id)
**Purpose**: Update existing user access level.

**Parameters**:
- `$request->level` (string, required) - Updated level name
- `$id` (integer) - Level ID

**Validation**:
```php
$request->validate([
    'level'=> 'required'
]);
```

**Process**:
1. Find level by ID
2. Update level name
3. Save changes

**Returns**:
- Success: Redirect to LevelLog with success message
- Failure: Redirect back with error message

---

#### 13. DeteletLevel($id)
**Purpose**: Delete user access level.

**Parameters**:
- `$id` (encrypted string) - Encrypted level ID

**Process**:
1. Decrypt level ID
2. Find level by ID
3. Delete level record

**Returns**:
- Success: Redirect to LevelLog with success message
- Failure: Redirect back with error message

## Security Features

### Authentication Security
- **Sentinel Integration**: Uses Cartalyst Sentinel for robust authentication
- **Password Hashing**: All passwords encrypted using bcrypt
- **Session Management**: Proper session handling and cleanup
- **Remember Me**: Secure persistent login functionality

### Data Protection
- **ID Encryption**: All IDs encrypted in URLs to prevent enumeration attacks
- **Input Validation**: Comprehensive validation on all inputs
- **SQL Injection Prevention**: Uses Eloquent ORM for database operations
- **Access Control**: Authentication required for all sensitive operations

### Authorization Levels
- **Role-based Access**: Level system for different user permissions
- **Admin Management**: Separate admin user management
- **User Segregation**: Clear separation between users and admins

## Error Handling

### Authentication Errors
- Invalid credentials handling
- Account not found scenarios
- Session timeout management

### Validation Errors
- Required field validation
- Email format validation
- Unique constraint handling

### Database Errors
- Model not found exceptions
- Constraint violation handling
- Transaction rollback scenarios

## Usage Examples

### Login Process
```php
// POST /login
{
    "email": "admin@goodfellas.id",
    "password": "password123",
    "remember": true
}
```

### Create Admin
```php
// POST /admin/create
{
    "nama": "John Doe",
    "email": "john@goodfellas.id",
    "id_level": 1
}
```

### Reset Password
```php
// POST /admin/reset-password
{
    "email": "admin@goodfellas.id",
    "password": "newpassword123"
}
```

## Database Schema Requirements

### Admin Table
- `id` (Primary Key)
- `nama` (String)
- `email` (String, Unique)
- `password` (String, Hashed)
- `id_level` (Foreign Key to levels table)
- `created_at`, `updated_at` (Timestamps)

### User Table
- `id` (Primary Key)
- User-specific fields
- `created_at`, `updated_at` (Timestamps)

### Level Table
- `id` (Primary Key)
- `level` (String)
- `created_at`, `updated_at` (Timestamps)

## Configuration Requirements

### Sentinel Configuration
```php
// config/cartalyst.sentinel.php
'users' => [
    'model' => 'App\Models\Admin',
],
```

### Route Configuration
```php
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'pushlogin']);
Route::get('/logout', [AuthController::class, 'logOut'])->name('logout');
```

## Best Practices

### Security Best Practices
1. Always validate and sanitize input data
2. Use encrypted IDs in URLs
3. Implement proper session management
4. Hash passwords before storage
5. Use HTTPS for authentication endpoints

### Code Best Practices
1. Consistent error handling patterns
2. Proper validation rules
3. Clear method naming conventions
4. Comprehensive documentation
5. Separation of concerns

## Troubleshooting

### Common Issues
1. **Login Fails**: Check email/password combination and database connection
2. **Session Issues**: Verify session configuration and storage
3. **Permission Denied**: Check user level and authentication status
4. **Encryption Errors**: Verify Laravel APP_KEY is set correctly

### Debug Steps
1. Check Sentinel configuration
2. Verify database connections
3. Review session storage settings
4. Validate encryption key setup
5. Check route definitions and middleware