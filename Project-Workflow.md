# Egg Inventory MVC - Workflow Documentation

This document describes all workflows in the Egg Inventory application, including user workflows, development workflows, and system architecture workflows.

---

## 1. User Workflows

### 1.1 Viewing All Eggs (Index Workflow)

**User Goal**: See all eggs in inventory

**Flow**:
1. User navigates to `http://localhost:8000/eggs` or `http://localhost:8000/`
2. Browser sends GET request to `/eggs`
3. Router matches route to `EggController@index`
4. Controller calls `$this->egg->all()` to fetch all eggs from database
5. QueryBuilder executes `SELECT * FROM eggs`
6. Results returned as array of associative arrays
7. Controller renders `eggs/index` view with egg data
8. View iterates over eggs and displays them in a list
9. Each egg shows: Type, Quantity, Date, and action links (View, Edit, Delete)
10. User sees the inventory list

**Key Files**:
- Route: `routes/web.php` - `GET /eggs` → `EggController@index`
- Controller: `app/Controllers/EggController.php` - `index()` method
- View: `app/Views/eggs/index.php`
- Model: `app/Models/Egg.php` - `all()` method

---

### 1.2 Viewing Single Egg Details (Show Workflow)

**User Goal**: See detailed information about a specific egg

**Flow**:
1. User clicks "View" link on an egg in the index page
2. Browser navigates to `/eggs/show?id=1`
3. Router matches route to `EggController@show`
4. Controller extracts `id` from query parameter: `$request->input('id')`
5. Controller calls `$this->egg->find($id)` to fetch the egg
6. QueryBuilder executes `SELECT * FROM eggs WHERE id = :id`
7. Single egg record returned as associative array (or null if not found)
8. Controller renders `eggs/show` view with egg data
9. User sees detailed egg information with Edit and Delete options

**Key Files**:
- Route: `routes/web.php` - `GET /eggs/show` → `EggController@show`
- Controller: `app/Controllers/EggController.php` - `show()` method
- View: `app/Views/eggs/show.php`
- Model: `app/Models/Egg.php` - `find()` method

---

### 1.3 Creating an Egg (Create Workflow - Two Steps)

**User Goal**: Add a new egg type to inventory

#### Step 1: Display Create Form (GET /eggs/create)

**Flow**:
1. User clicks "Add Egg" button or navigates to `/eggs/create`
2. Browser sends GET request to `/eggs/create`
3. Router matches to `EggController@create`
4. Controller checks if request method is POST
5. Since it's GET, controller skips processing and renders form
6. Controller calls `$this->view->render('eggs/create')`
7. Blank form displayed to user with fields: Type, Quantity
8. User fills in the form and clicks Submit

**Key Files**:
- Route: `routes/web.php` - `GET /eggs/create` → `EggController@create`
- View: `app/Views/eggs/create.php` - HTML form

#### Step 2: Process Form Submission (POST /eggs/create)

**Flow**:
1. User submits form with data: `type="Rhode Island Red"`, `quantity="24"`
2. Browser sends POST request to `/eggs/create`
3. Router matches to `EggController@create` (same route, different method)
4. Controller checks if request method is POST - **YES**
5. Controller extracts form data:
   - `$type = trim($request->input('type'))`
   - `$quantity = trim($request->input('quantity'))`
6. **Validation occurs**:
   - Check if type is empty → error
   - Check if quantity is numeric and positive → error
7. If validation fails:
   - Errors array is populated
   - Form is re-rendered with error messages
   - User sees form again with errors
8. If validation passes:
   - Controller calls `$this->egg->create(['type' => $type, 'quantity' => $quantity])`
   - Model validates data again
   - QueryBuilder executes `INSERT INTO eggs (type, quantity) VALUES (:type, :quantity)`
   - Redirect to `/eggs` (POST-Redirect-GET pattern)
9. User sees updated inventory with new egg added

**Key Files**:
- Route: `routes/web.php` - `POST /eggs/create` → `EggController@create`
- Controller: `app/Controllers/EggController.php` - `create()` method
- View: `app/Views/eggs/create.php` - HTML form and error display
- Model: `app/Models/Egg.php` - `create()` method

---

### 1.4 Editing an Egg (Edit Workflow - Two Steps)

**User Goal**: Update egg information

#### Step 1: Display Edit Form (GET /eggs/edit?id=1)

**Flow**:
1. User clicks "Edit" link on an egg
2. Browser navigates to `/eggs/edit?id=1`
3. Router matches to `EggController@edit`
4. Controller checks if request method is POST
5. Since it's GET, controller skips processing
6. Controller extracts ID: `$id = (int)$request->input('id')`
7. Controller calls `$this->egg->find($id)` to load current data
8. QueryBuilder executes `SELECT * FROM eggs WHERE id = :id`
9. Egg data is passed to view: `eggs/edit` with pre-filled form
10. User sees form with current type and quantity values

**Key Files**:
- Route: `routes/web.php` - `GET /eggs/edit` → `EggController@edit`
- View: `app/Views/eggs/edit.php` - Pre-filled form

#### Step 2: Process Edit Submission (POST /eggs/edit?id=1)

**Flow**:
1. User modifies form fields and clicks Update
2. Browser sends POST request to `/eggs/edit?id=1`
3. Router matches to `EggController@edit`
4. Controller checks if request method is POST - **YES**
5. Controller extracts ID and new data:
   - `$id = (int)$request->input('id')`
   - `$type = $request->input('type')`
   - `$quantity = $request->input('quantity')`
6. Controller calls `$this->egg->update($id, ['type' => $type, 'quantity' => $quantity])`
7. QueryBuilder executes `UPDATE eggs SET type = :type, quantity = :quantity WHERE id = :id`
8. Redirect to `/eggs`
9. User sees updated inventory

**Key Files**:
- Route: `routes/web.php` - `POST /eggs/edit` → `EggController@edit`
- Controller: `app/Controllers/EggController.php` - `edit()` method
- View: `app/Views/eggs/edit.php`
- Model: `app/Models/Egg.php` - `update()` method

---

### 1.5 Deleting an Egg (Delete Workflow)

**User Goal**: Remove an egg from inventory

**Flow**:
1. User clicks "Delete" link on an egg
2. Browser navigates to `/eggs/delete?id=1`
3. Router matches route to `EggController@delete`
4. Controller extracts ID: `$id = (int)$request->input('id')`
5. Controller calls `$this->egg->delete($id)`
6. QueryBuilder executes `DELETE FROM eggs WHERE id = :id`
7. Redirect to `/eggs`
8. User sees updated inventory with egg removed

**Key Files**:
- Route: `routes/web.php` - `GET /eggs/delete` → `EggController@delete`
- Controller: `app/Controllers/EggController.php` - `delete()` method
- Model: `app/Models/Egg.php` - `delete()` method

---

## 2. Request/Response Workflow (HTTP Lifecycle)

This describes how a single HTTP request flows through the entire application:

### Request Flow Diagram
```
Browser Request
        ↓
public/index.php
        ↓
Load Autoloader (vendor/autoload.php)
        ↓
Create Request Object
Create Response Object
Create Router Object
        ↓
Load Routes (routes/web.php)
        ↓
Instantiate Application with Router, Request, Response
        ↓
Application::run()
        ↓
Process Middleware (if any)
        ↓
Router::resolve($request) - Match URL to Route
        ↓
Extract Controller & Method from Route
        ↓
Instantiate Controller
        ↓
Call Controller Method with Request
        ↓
Controller processes logic:

Get data from Model
Render View
Set Response
        ↓
View rendered to output buffer
        ↓
Response sent to browser
```