# SUPPLIERS

## About the app
Tiny demo app.  
User registration, authentication, two types of users (admin/member), admin can upload resources (books) file (csv, xml, xls, xlsx); API endpoints with Laravel Sanctum API tokens, view all resources with filtering, view single resource.  
- Books.  
- Authors.  
- Publishers.  


## Version requirements
- PHP – 8.0.13
- DB - 10.4.22-MariaDB
- Laravel – 8.77.1

## Installation

1. Pull in the project using the following link:
```
https://github.com/drago1979/suppliers.git

```
2. Create .env file with valid data (DB credentials etc.).  
3. In your terminal (working folder) run
```
composer install
```  

```
npm install
```


```
npm run prod
```


```
php artisan key:generate
```


```
php artisan migrate
```

```
php artisan loadsupplierproducts
```
