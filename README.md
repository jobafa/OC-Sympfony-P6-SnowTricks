# OC-Sympfony-P6-SnowTricks
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/07ac88bedcaf4d7aad92ec4eb8752733)](https://www.codacy.com/gh/jobafa/OC-Sympfony-P6-SnowTricks/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=jobafa/OC-Sympfony-P6-SnowTricks&amp;utm_campaign=Badge_Grade)
OpenClassrooms Projet 6 : parcours développeur d'application PHP/Symfony

SnowTricks

PROJET 6

OpenClassrooms : "Développeur d'application PHP/Symfony"



Description:

SnowTricks is a community website for snowboarders.

Non connected users have access to  Tricks list and trick details pages.
Conneccted users can comment tricks, add or edit tricks and delete their own tricks. They also can modify their Password.

Symfony 5.4 

Installation

1 - Git clone the project

    https://github.com/jobafa/OC-Sympfony-P6-SnowTricks.git

2 - Install Dependencies

    php bin/console composer install

3 - Create database

a) In the .env file : 
    - Modify the DATABASE_URL  with your database configuration:

            DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name

    - Configure MAILER_DSN for Symfony mailer
        
b) Create database:
            php bin/console doctrine:database:create
        
c) Create database Tables:

            php bin/console make:migration
        
        
4 - Configure MAILER_DSN of Symfony mailer in .env file

5 - In your editor, Open the terminal get to the project directory and type :

php bin/console server:run

OR 

symfony server:start

OR

symfony serve

Go to the displayed URL 

Your are on the Snowtricks web site, Create an account, validate your account from your sent mail Link, 
Sign in and start adding Snowtricks

Enjoy

Enjoy
