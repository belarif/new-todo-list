## Installing the project
### 1.  PHP and Composer
1.1 PHP-version >= 8.1  
1.2 Composer-version >= 2.2

### 2.  Local project copy
Click on the "code" button at the top, then on the HTTPS section which displays the following url :
####
                https://github.com/belarif/new-todo-list.git 

copy this url to install the project locally.

If you are using the WampServer64, from your terminal, position yourself on the c:/wamp64/www path as follows :
####
                cd c:/wamp64/www
if you use server other than WampServer64, position yourself on the path that will allow the execution of the application.

On the same path, type the following command to clone the project :
####
                git clone https://github.com/belarif/new-todo-list.git

After executing the command, the project will be copied to the 'www' directory

### 3.  Installing dependencies
                composer install

### 4.  Creation of the database
4.1 To adapt access to your SGBD, in the .env file configure the variable DATABASE_URL  
4.2 Create your database

                php bin/console doctrine:database:create
3.3 Create your database schema:

                php bin/console doctrine:migrations:migrate
### 5.  Loading fixtures
                php bin/console doctrine:fixtures:load

### 6.  Installation of public resources
6.1 Install yarn: `npm install --global yarn`   
6.2 Install encore: `yarn install`  
6.3 Upload public files: `yarn build`

### 7.  Run application
                php -S localhost:8000 -t public/
Homepage : http://localhost:8000/

### 8.  Tests
#### 8.1    Generation level of code coverage
                vendor/bin/phpunit --coverage-html public/test-coverage
#### 8.2    visualization of code coverage on browser
                http://localhost:8000/test-coverage/index.html

