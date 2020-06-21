![build](https://api.travis-ci.org/hochdyl/slamquiz.svg?branch=master)

Atedi
=========

# Download
Open your CMD and copy this line : 

git clone https://github.com/hochdyl/slamstarter.git
# Install
When downlaod is complete, open your CMD copy this line :

cd slamquiz
```
composer install

(Composer is free to download at this link : https://getcomposer.org/download/)

If you've done it right, you should get this index page :

![index](https://raw.githubusercontent.com/hochdyl/slamquiz/master/assets/screenshot_home.jpg)

# Create database
Start a DBMS like Wamp server (free to download at this link : http://www.wampserver.com/)

Then configure your database with .env file
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name

Replace db_user with : root

Replace db_password with : (let it blank)

Replace db_name with : atedi

Finalyn go in the slamquiz directory with cmd and execute this line
php bin/console doctrine:database:create



# Load database
There are some default user that you can load into a database. Open your CMD and copy this line :
php bin/console doctrine:migrations:migrate

php bin/console doctrine:fixtures:load


# Run
Once you've installed everything, execute this line in the atedi directory :
php bin/console server:run


# Sign in
Next, you will have to connect with the user account. It is setup by default but you can remove it if wanted.

First, click on "Sign in"

![sign in](https://raw.githubusercontent.com/hochdyl/slamquiz/master/assets/sign.png)

Then type the following id to connect as a user :
* Id : user@gmail.com

* Password : user

![sign in](https://raw.githubusercontent.com/hochdyl/slamquiz/master/assets/user.png)

There are 2 others account configure by default :
* Id : admin@gmail.com

* Password : admin

* Id : superadmin@gmail.com

* Password : superadmin
```
