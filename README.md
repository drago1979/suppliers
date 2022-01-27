# SUPPLIERS

## About the app
Tiny demo app.  
API, RESTful, endpoints, csv -> DB, csv download <-DB.  
- Suppliers (index, update, delete).  
- Products (index, update, delete, filter by supplier, load CSV, download CSV w/ suppliers).  


## Version requirements
- PHP – 8.0.13
- DB - 10.4.22-MariaDB
- Laravel – 8.77.1

## Installation

1. Pull in the project using the following link:
```
https://github.com/drago1979/contacts.git

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
