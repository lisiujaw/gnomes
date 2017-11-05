# Gnome manager
Manage your virtual gnomes.

## Instalation
1. git clone :)
2. Run ```composer install```
3. Copy .env.example file to .env and modify it
4. Run ```php artisan key:generate```
5. Run ```php artisan migrate --seed```
6. You\`r ready to manage gnomes! Have fun!

## Requirements
* PHP7.1
* MySQL
* Installed PHP GD Library

## Test data
Login/Pass : devel/devel

## REST API

### Authorization
Basic (use user credentials)

### Methods
* GET /api/user (Show logged user data)
* GET /api/gnomes (List gnomes)
* GET /api/gnomes/:id (Get gnome data)
* PUT /api/gnomes (Create new gnome)
* PATCH /api/gnomes/:id (Update existing gnome)
* DELETE /api/gnomes/:id (Deletes gnome)

### Sample request to create or update gnome
```
{
  "name":"My new gnome",
  "age":25,
  "strength":66,
  "avatar":"data:image/jpeg;base64,/9j/4AAQSkZJRg..."
}
```

### Create response
```
{
    "status": true,
    "gnome": {
        "name": "My new gnome",
        "strength": 66,
        "age": 25,
        "avatar_file": "b57effc5f247b7562af5c80628bc99220f4864af.jpg",
        "id": 54
    }
}
```

### Headers
* Authorization : Basic ZGV2ZWw6ZGV2ZWw= (devel/devel)
* Content-Type : application/json
