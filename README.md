<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Credy-Yii2</h1>
    <br>
</p>

  Simple app for submitting form data, and posting it as a JSONx to an external API endpoint,
  (based on [Yii 2](https://www.yiiframework.com/))


### Installing for local use:

PHP , Docker and Composer are needed.

~~~
git clone git@github.com:randelpalu/credy-yii2.git my-project 
cd my-project
cp .env.example .env
docker-compose up
~~~

Containers should be running now.

~~~
# get the <CONTAINER_ID> of a PHP container
docker ps

# access the container
docker exec -it <CONTAINER_ID> /bin/bash
composer install

# exit the container
~~~

App should be available now with a browser, on localhost:8000 (or whatever port you are using in .env)

### Starting and stopping:

~~~
docker-compose up
docker-compose down
~~~


### Running tests:


    vendor/bin/codecept run

### Notes:
If there are some permission issues:
~~~
git config core.fileMode false
sudo chmod 777 -R *
~~~
Running tests on local. Error, even though php gd extension is installed =>
"...Either GD PHP extension with FreeType support or ImageMagick PHP extension with PNG support is required."
Check if FreeType is available:
~~~
php -i | grep FreeType

# if using phpbrew on local, this can be made available like this:
phpbrew install 8.2.15 +gd=freetype

~~~
