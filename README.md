# skypet

##To Setup.
Ensure that supervisor is installed. 
```
sudo apt install supervisor
sudo service supervisor restart
sudo systemctl enable supervisor
```
Add horizon to the supervisor configuation
```$xslt

sudo nano /etc/supervisor/conf.d/skypet.conf
```

Add these lines into the nano editor

```$xslt
[program:laravel_horizon]
process_name=%(program_name)s_%(process_num)02d
command=php home/vagrant/code/skypet/artisan horizon
autostart=true
autorestart=true
redirect_stderr=true
user=www-data
stdout_logfile=home/vagrant/code/skypet/storage/horizon.log
```

Reread, update and check if horizon is running 

```
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl
  ```

To view the incoming jobs go to /horizon

## To start the application


```
php artisan start:skypet
```

input any journal ISSN (i.e. 0891-6640)


