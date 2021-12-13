# Step by Step to Run This Project in Local Host or Docker
This is example of inventory web application. In this application, stock management is carried out automatically by using triggers from the database `before insert`, `before update` and `after delete`.<br/><br/>
This project is created from ``Laravel Framework`` and ``Vue.js`` as a frontend <br/>
In this project i am use port `8080` to run laravel server
## Prerequisites
- Php version 7.4 or highers
- MySQL or Maria DB latest version
- Node JS version 14 or highers

## <u>How to Run in Local Host</u>

- Import the database. The `.sql` file is under `docker-config/mysql/db` directory
- Open your terminal or command line in current project directory and install all dependencies with ``composer install`` to laravel and ``npm install`` to node modules
- After the installation complete, compile laravel mixin with `npm run dev` or `npm run prod`. Or when you want to automatic compile every time there is a code change, you can use `npm run watch` 
- Open `.env` file and change the database configuration  
```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=inventory_sample
    DB_USERNAME=root
    DB_PASSWORD=
```
- And then serve your application with `php artisan serve --port=8080`
- Finish, you can now access your web application at [http://localhost:8080](http://localhost:8080)
  


## <u>How to Deploy and Run in Docker</u>
### Prerequisites
- Docker Desktop or Docker CLI

### Step
- Open your terminal or command line in current project directory, and run the command `docker-compose up`
- After the installation complete, open `.env` file, and change the database configuration like that :
```
    DB_CONNECTION=mysql
    DB_HOST=database
    DB_PORT=3306
    DB_DATABASE=inventory_sample
    DB_USERNAME=root
    DB_PASSWORD=12345
```
- Finish, you can now access your web application at [http://localhost:8080](http://localhost:8080)
  
<i>To reset mariadb docker databases and inject a new one, you can use command `docker-compose down -v` </i>