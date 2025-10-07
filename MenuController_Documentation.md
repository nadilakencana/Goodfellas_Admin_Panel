# MenuController Documentation

## Overview
The `MenuController` manages all aspects of menu items in the Goodfellas POS system, including menu creation, editing, variants management, ingredient handling, and stock management integration.

## Dependencies
- `Illuminate\Http\Request`
- `App\Models\Menu`
- `App\Models\Kategori`
- `App\Models\SubKategori`
- `App\Models\VarianMenu`
- `App\Models\BahanBaku`
- `App\Models\MenuResep`
- `App\Models\GroupModifier`
- `App\Models\DetailOrder`
- `Sentinel`
- `Illuminate\Support\Facades\Http`

## Class Structure
```php
class MenuController extends Controller
```

## Menu Management Methods

### 1. indexMenu()
**Purpose**: Display all active menu items with pagination and filtering.

**Authentication**: Requires Sentinel authentication

**Query Logic**:
```php
$menu = Menu::where('custom', false)
    ->where('delete_menu', 0)
    ->orderBy('id', 'DESC')
    ->get();
```

**Returns**:
- Authenticated: Menu index view (`Menu.index`)
- Unauthenticated: Redirect to login

**Data Passed to View**:
- `$menu` - Collection of active menu items (non-custom, non-deleted)

---

### 2. createMenu()
**Purpose**: Display form for creating new menu items.

**Authentication**: Requires Sentinel authentication

**Returns**:
- Authenticated: Menu creation form (`Menu.create`)
- Unauthenticated: Redirect to login

**Data Passed to View**:
- `$kat` - All categories
- `$sub_kat` - All subcategories
- `$additional` - All group modifiers
- `$bahan_baku` - All ingredients

---

### 3. PushCreate(Request $request)
**Purpose**: Process and save new menu item with all related data.

**Parameters**:
- `$request->nama_menu` (string, required) - Menu name
- `$request->slug` (string, required) - URL slug
- `$request->deskripsi` (string, required) - Menu description
- `$request->harga` (numeric, required) - Menu price
- `$request->id_kategori` (integer, required) - Category ID
- `$request->id_sub_kategori` (integer, required) - Subcategory ID
- `$request->promo` (boolean, required) - Promotion status
- `$request->image` (file, optional) - Menu image
- `$request->id_group_modifier` (integer, optional) - Modifier group ID
- `$request->stok` (integer, optional) - Stock quantity
- `$request->stok_minimun` (integer, optional) - Minimum stock level
- `$request->tipe_stok` (string, optional) - Stock type
- `$request->id_bahan_baku` (integer, optional) - Ingredient ID
- `$request->active` (boolean, optional) - Active status
- `$request->variasi` (array, optional) - Menu variants

**Validation Rules**:
```php
$request->validate([
    'nama_menu'=> 'required',
    'slug' => 'required',
    'deskripsi' => 'required',
    'harga' => 'required',
    'id_kategori' => 'required',
    'id_sub_kategori' => 'required',
    'promo' => 'required',
    'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
]);
```

**Process Flow**:
1. **Create Base Menu**:
   ```php
   $menu = Menu::create($request->all());
   $menu->custom = false;
   $menu->active = $request->active ?? 0;
   ```

2. **Handle Image Upload**:
   ```php
   if($file = $request->hasFile('image')){
       $file = $request->file('image');
       $fileName = $file->getClientOriginalName();
       $destination = public_path().'/asset/assets/image/menu/';
       if(!file_exists($destination)){
           mkdir($destination, 0777, true);
       }
       $file->move($destination, $fileName);
       $menu->image = $fileName;
   }
   ```

3. **Handle Recipe Creation** (for Foods with ingredient-based stock):
   ```php
   if($menu->kategori->kategori_nama === 'Foods'){
       if($menu->tipe_stok === 'Stok Bahan Baku'){
           $menu_resep = new MenuResep();
           $menu_resep->id_menu = $menu->id;
           $menu_resep->id_bahan_baku = $request->id_bahan_baku;
           $menu_resep->save();
       }
   }
   ```

4. **Handle Variants Creation**:
   ```php
   if($request->has('variasi')){
       foreach($request->variasi as $variasi){
           $var = new VarianMenu();
           $var->id_menu = $menu->id;
           $var->nama = $variasi['nama'];
           $var->harga = $variasi['harga'];
           $var->active = $variasi['active'];
           $var->save();
       }
   }
   ```

**Returns**:
- Success: Redirect to menu index with success message
- Failure: Redirect back with error message

---

### 4. editMenu($id)
**Purpose**: Display form for editing existing menu item.

**Parameters**:
- `$id` (encrypted string) - Encrypted menu ID

**Process**:
1. Decrypt menu ID
2. Find menu with all related data
3. Load categories, subcategories, variants, modifiers, and ingredients

**Returns**:
- Authenticated: Edit form view (`Menu.edit`)
- Unauthenticated: Redirect to login

**Data Passed to View**:
- `$menu` - Menu model instance
- `$kat` - All categories
- `$sub_kat` - All subcategories
- `$variasi` - Menu variants
- `$additional` - Group modifiers
- `$bahan_baku` - All ingredients

---

### 5. updateMenu(Request $request, $id)
**Purpose**: Update existing menu item with all related data.

**Parameters**: Same as `PushCreate` plus encrypted menu ID

**Validation**: Same as `PushCreate`

**Process Flow**:
1. **Update Base Menu Data**:
   ```php
   $menu = Menu::findOrFail($dec);
   $menu->nama_menu = $request->nama_menu;
   $menu->slug = $request->slug;
   // ... other fields
   ```

2. **Handle Stock Type Changes**:
   ```php
   if($request->tipe_stok === 'Stok Bahan Baku'){
       $menu->stok = 0;
       $menu->stok_minimum = 1;
   } else {
       $menu->stok = $request->stok;
       $menu->stok_minimum = $request->stok_minimun;
   }
   ```

3. **Update Recipe Relationship**:
   ```php
   if($menu->kategori->kategori_nama === 'Foods'){
       if($menu->tipe_stok === 'Stok Bahan Baku'){
           $menu_resep = MenuResep::where('id_menu', $menu->id)->first();
           if(!$menu_resep){
               $menu_resep = new MenuResep();
           }
           $menu_resep->id_menu = $menu->id;
           $menu_resep->id_bahan_baku = $request->id_bahan_baku;
           $menu_resep->save();
       }
   }
   ```

4. **Handle Variant Updates**:
   ```php
   if($request->has('variasi')){
       foreach($request->variasi as $variasi){
           if(array_key_exists('id', $variasi)){
               // Update existing variant
               if($variasi['nama'] === 'Delete'){
                   $var->delete();
               } else {
                   // Update variant data
               }
           } else {
               // Create new variant
           }
       }
   }
   ```

**Returns**:
- Success: Redirect to menu index with success message
- Failure: Redirect back with error message

---

### 6. deleteMenu(Request $request, $id)
**Purpose**: Soft delete menu item and related variants.

**Parameters**:
- `$id` (encrypted string) - Encrypted menu ID

**Process**:
1. Decrypt menu ID
2. Set `delete_menu = 1` (soft delete)
3. Soft delete all related variants

**Soft Delete Logic**:
```php
$menu->delete_menu = 1;
$menu->save();

$varian = VarianMenu::where('id_menu', $menu->id)->get();
foreach($varian as $var){
    $varUp = VarianMenu::where('id', $var->id)->first();
    $varUp->deleted = 1;
    $varUp->save();
}
```

**Returns**: Redirect back with success/error message

## Ingredient Management Methods

### 7. bahanBaku()
**Purpose**: Display all ingredients (raw materials).

**Authentication**: Requires Sentinel authentication

**Returns**:
- Authenticated: Ingredient list view (`Menu.bahanBaku`)
- Unauthenticated: Redirect to login

**Data Passed to View**:
- `$bahan_baku` - Collection of all BahanBaku models

---

### 8. createBahanBaku()
**Purpose**: Display form for creating new ingredient.

**Returns**: Ingredient creation form (`Menu.createBahanbaku`)

---

### 9. pushCreateBahanBaku(Request $request)
**Purpose**: Save new ingredient to database.

**Parameters**:
- `$request->nama_bahan` (string, required) - Ingredient name
- `$request->stok_porsi` (integer, required) - Stock portions
- `$request->stok_minimum` (integer, required) - Minimum stock level

**Validation**:
```php
$request->validate([
    'nama_bahan' => 'required',
    'stok_porsi' => 'required',
    'stok_minimum' => 'required',
]);
```

**Process**:
```php
$bahan_baku = new BahanBaku();
$bahan_baku->nama_bahan = $request->nama_bahan;
$bahan_baku->stok_porsi = $request->stok_porsi;
$bahan_baku->stok_minimum = $request->stok_minimum;
$bahan_baku->save();
```

**Returns**:
- Success: Redirect to ingredient list with success message
- Failure: Redirect back with error message

---

### 10. editBahanBaku($id)
**Purpose**: Display form for editing ingredient.

**Parameters**:
- `$id` (encrypted string) - Encrypted ingredient ID

**Returns**: Edit form view (`Menu.editBahanbaku`)

---

### 11. updateBahanBaku(Request $request, $id)
**Purpose**: Update existing ingredient.

**Parameters**: Same as `pushCreateBahanBaku` plus encrypted ID

**Process**: Similar to create but updates existing record

---

### 12. deleteBahanBaku(Request $request, $id)
**Purpose**: Hard delete ingredient from database.

**Parameters**:
- `$id` (encrypted string) - Encrypted ingredient ID

**Process**: Direct deletion using Eloquent `delete()` method

## Advanced Features

### 1. Stock Management Integration
**Two Stock Types**:
1. **Regular Stock**: Direct menu item stock tracking
2. **Ingredient-based Stock**: Stock calculated from raw materials

**Stock Type Logic**:
```php
if($menu->tipe_stok === 'Stok Bahan Baku'){
    // Use ingredient stock for calculation
    $stok = $menu->bahanBaku->stok_porsi;
} else {
    // Use direct menu stock
    $stok = $menu->stok;
}
```

### 2. Variant Management System
**Dynamic Variant Handling**:
- Create multiple variants per menu item
- Each variant has independent pricing
- Active/inactive status per variant
- Bulk variant operations (create, update, delete)

**Variant Data Structure**:
```php
$variasi = [
    'nama' => 'Small/Medium/Large',
    'harga' => 'Additional price',
    'active' => 'Status flag'
];
```

### 3. Image Upload System
**File Handling Features**:
- Automatic directory creation
- Original filename preservation
- Multiple image format support (jpeg, png, jpg, gif, svg)
- File validation and security checks

**Upload Path**: `/public/asset/assets/image/menu/`

### 4. Recipe Management
**Ingredient-Menu Relationship**:
- Links menu items to raw ingredients
- Enables ingredient-based stock calculation
- Supports complex recipe structures
- Automatic recipe creation for Foods category

### 5. Modifier Integration
**Group Modifier System**:
- Associates menu items with modifier groups
- Enables additional options (size, extras, etc.)
- Flexible pricing for modifiers
- Active/inactive modifier control

## Data Relationships

### Menu Model Relationships
```php
// Menu belongs to Category
$menu->kategori

// Menu belongs to SubCategory  
$menu->subKategori

// Menu has many Variants
$menu->varian

// Menu belongs to GroupModifier
$menu->groupModifier

// Menu belongs to BahanBaku (ingredient)
$menu->bahanBaku

// Menu has one MenuResep (recipe)
$menu->resep
```

### Complex Queries
**Menu with All Relations**:
```php
$menu = Menu::with([
    'kategori',
    'subKategori', 
    'varian',
    'groupModifier',
    'bahanBaku',
    'resep'
])->find($id);
```

## Security Features

### 1. Authentication
- All methods require Sentinel authentication
- Automatic redirect to login for unauthenticated users

### 2. Input Validation
- Comprehensive validation rules for all inputs
- File type validation for image uploads
- Required field validation
- Data type validation

### 3. ID Encryption
- All IDs encrypted in URLs using Laravel's encrypt/decrypt
- Prevents ID enumeration attacks
- Secure parameter passing

### 4. File Security
- Image file type validation
- Directory traversal prevention
- Secure file upload handling

## Error Handling

### 1. Validation Errors
```php
$request->validate([
    'nama_menu'=> 'required',
    'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
]);
```

### 2. Database Errors
- Model not found handling
- Foreign key constraint validation
- Transaction rollback on failures

### 3. File Upload Errors
- Directory creation error handling
- File permission error handling
- Storage space validation

## Performance Optimization

### 1. Query Optimization
- Eager loading for relationships
- Selective field loading where appropriate
- Proper indexing on foreign keys

### 2. Image Optimization
- File size validation
- Image format optimization
- Efficient file storage structure

### 3. Caching Strategy
- Model caching for frequently accessed data
- Query result caching for complex operations
- File system caching for images

## Usage Examples

### 1. Create Menu with Variants
```php
POST /menu/create
{
    "nama_menu": "Coffee",
    "slug": "coffee",
    "deskripsi": "Premium coffee",
    "harga": 25000,
    "id_kategori": 1,
    "id_sub_kategori": 1,
    "promo": false,
    "variasi": [
        {"nama": "Small", "harga": 0, "active": 1},
        {"nama": "Large", "harga": 5000, "active": 1}
    ]
}
```

### 2. Update Menu Stock Type
```php
PUT /menu/update/{encrypted_id}
{
    "tipe_stok": "Stok Bahan Baku",
    "id_bahan_baku": 1
}
```

### 3. Create Ingredient
```php
POST /ingredient/create
{
    "nama_bahan": "Coffee Beans",
    "stok_porsi": 100,
    "stok_minimum": 10
}
```

## Integration Points

### 1. POS System Integration
- Real-time stock checking during order processing
- Automatic stock deduction on sales
- Stock restoration on refunds

### 2. Inventory Management
- Ingredient-based stock calculations
- Minimum stock level alerts
- Stock movement tracking

### 3. Reporting Integration
- Menu performance analytics
- Stock level reporting
- Sales by menu item analysis

## Troubleshooting

### Common Issues
1. **Image Upload Fails**: Check directory permissions and file size limits
2. **Stock Calculation Errors**: Verify ingredient relationships and stock types
3. **Variant Issues**: Check variant data structure and active status
4. **Category Errors**: Ensure proper category-subcategory relationships

### Debug Steps
1. Check file upload permissions and paths
2. Verify database relationships and foreign keys
3. Validate stock calculation logic
4. Test variant creation and update processes
5. Monitor image file handling and storage