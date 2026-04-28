# MVC-Framework-Project






# Expected Project Structure
my-mvc-framework/ 
|-- app/                  # Application layer (MVP) 
|   |-- Controllers/ 
|   |-- Models/ 
|   |-- Views/ 
|   `-- Middleware/ 
|-- core/                 # Framework layer 
|   |-- Http/ 
|   |   |-- Request.php 
|   |   |-- Response.php 
|   |   `-- Router.php 
|   |-- Database/ 
|   |   |-- Connection.php 
|   |   |-- QueryBuilder.php 
|   |   `-- Model.php 
|   |-- View/ 
|   |   `-- Engine.php 
|   |-- Container/ 
|   |   `-- Container.php     # DI container 
|   `-- Application.php 
|-- config/ 
|   |-- app.php 
|   `-- database.php 
|-- public/               # Web server document root 
|   `-- index.php         # Front controller (only file with require) 
|-- routes/ 
|   `-- web.php 
|-- composer.json         # PSR-4 autoload configuration 
`-- README.md

