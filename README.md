<p  align="center"><a  href="https://laravel.com"  target="_blank"><img  src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg"  width="400"  alt="Laravel Logo"></a></p>

<p  align="center">

<a  href="https://github.com/laravel/framework/actions"><img  src="https://github.com/laravel/framework/workflows/tests/badge.svg"  alt="Build Status"></a>

<a  href="https://packagist.org/packages/laravel/framework"><img  src="https://img.shields.io/packagist/dt/laravel/framework"  alt="Total Downloads"></a>

<a  href="https://packagist.org/packages/laravel/framework"><img  src="https://img.shields.io/packagist/v/laravel/framework"  alt="Latest Stable Version"></a>

<a  href="https://packagist.org/packages/laravel/framework"><img  src="https://img.shields.io/packagist/l/laravel/framework"  alt="License"></a>

</p>

  

## Simple E-commerce project based on DDD software architecture

  

This is a simple e-commerce project that follows the principles of Domain-Driven Design (DDD) software architecture. It includes essential functionalities for managing inventory, processing payments, and generating invoice reports.

**- Inventory management solution**

**- Payment and verification**

**- Generate invoice reports**

**- Sending notification**

## Database design

  

Here is a brief illustration of the database design

**products**

- id
- title
- price

**inventories**

- product_id
- quantity

**invoices**

- id
- user_id
- created_at
- status (paid, pending, failed),
- total_price
- total_items
- address

**invoice_items**

- id
- invoice_id
- product_id
- quantity

**payments**

- user_id
- ipg_type (paypal, visacard, etc)
- created_at
- invoice_id
- ipg_info (A JSON to keep additional data about thee payment such as reference code)

**users**

- id
- email (username)
- password

### Relations and important notes

- product and inventory has one to one relationship for the sake of scalability and separation of concerns.

- invoice and payment has one to many relationship because an invoice may be canceled in first attempt and after that it becomes paid one. So i decided to keep track failed payments for an invoice as well.

- invoice and invoice_items has one to many relationship.
- 
- user and invoice has one to many relationship
- user and payments has one to many relationship

- **Note:** we could access payments of a user by joining the users, invoices and payments so i decided to apply technical redundancy by adding user_id to payments table to prevent that low-performance joining.

- **Note**: **total_price** and **total_items** fields are also a good technical redundancy as well to prevent huge aggregate and join queries to only get total price and total items of an invoice.

## Installation

~~~

git clone https://github.com/MjavadBakhshi/DDD-based-e-commerce.git

cd DDD-based-e-commerce

composer install

php artisan migrate
~~~

## Running application

I have written 8 tests which cover validation of all features functionality. In addition these feature test are self-describing so by reviewing the test cases you can easily follow what is going on and how you can interact with the endpoints.

### You can only need to run :
~~~
php artisan test
~~~

### Contact
if  you are interested in this code-base feel free to contact me by
sending email to mjavad.bakhshi@gmail.com
