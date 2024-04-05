* PHP 8.2.12, MySql

---------------------------------------------
*GET
    user by id
         .id user 

    list user

    totalizer
        .id user

    ranking
        .str_date (yyyy-mm-dd)
        .end_date (yyyy-mm-dd)

    Coffee Type
---------------------------------------------
*POST
    users - create

    drinkCounter - add drink
        .id user
        .id coffee

    Login - token generator
        .email
        .password
----------------------------------------------
*PUT
    Edit user
        .name
        .password
----------------------------------------------
*DELETE
    Delete user
        .id user
