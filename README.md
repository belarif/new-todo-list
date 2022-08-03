## Installing the project
### 1.  Local project copy
Click on the "code" button, then on the HTTPS section which displays the following url :
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

### 2.  Installing dependencies
                composer install

### 3.  Creation of the database
3.1 Create your database locally   
3.2 Modify the .env file to adapt access to your SGBD  
3.3 Create your database schema:
####
                php bin/console doctrine:migrations:migrate

### 4.  Loading fixtures
                php bin/console doctrine:fixtures:load

### 5.  Installation of public resources
5.1 Install yarn: `npm install --global yarn`   
5.2 Install encore: `yarn install`  
5.3 Upload public files: `yarn build`

### 6.  Run application
                php -S localhost:8000 -t public/

Homepage : http://localhost:8000/

### 7.  Tests

#### 7.1    Generation level of code coverage
                vendor/bin/phpunit --coverage-html public/test-coverage

#### 7.2    visualization of code coverage on browser
                http://localhost:8000/test-coverage/index.html

