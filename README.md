# yet another dockerized NextCloud
_Based on Alpine_
[![Run Status](https://api.shippable.com/projects/5921cc0abba6190600a365a2/badge?branch=master)](https://app.shippable.com/github/martingabelmann/docker-nextcloud)
[![](https://images.microbadger.com/badges/image/martingabelmann/nextcloud.svg)](https://microbadger.com/images/martingabelmann/nextcloud "Get your own image badge on microbadger.com")
[![](https://images.microbadger.com/badges/version/martingabelmann/nextcloud.svg)](https://microbadger.com/images/martingabelmann/nextcloud "Get your own version badge on microbadger.com")

---

#### Features
 - Full nextcloud instance
 - OneClick/Run installation
 - Enforced ssl encryption 
 - small alpine base image
 - php7 (including many modules)
 - auto configuration via environment vars

#### Testing
A minimal working nextcloud instance can be run with

```
docker run --name=nctest -d -p 443:443 -p 80:80 martingabelmann/nextcloud
```

#### Installation
It is highly recommended to use nextcloud with SSL. The default Apache setting of this container forces the browser to use ``https://``. There are certificates build in the image for testing but in production  one should use trused ones.

### Volumes
The a mapping of persistent host directories and docker volumes could look like:
  * /srv/docker/nextcloud/
    * db -> /var/lib/mysql
    * ssl -> /etc/ssl/apache2
    * data -> /var/lib/nextcloud/data
    * apps -> /var/www/localhost/htdocs/apps2
where ``/srv/docker/nextcloud/ssl`` stores a server.{key,pem} file.

### Environment vars
see the head of the dockerfile.

### Template configs
**All** files locatet at ``/tpl`` are copied to the filesystems root ``/`` relative to ``/tpl/``. 
For instance the preexisting file ``/tpl/etc/apache2/conf.d/httpd-vhosts.conf`` is copied to ``/etc/apache2/conf.d/httpd-vhosts.conf``. All strings that follow the pattern ``{NC|DB_*}`` will be substituted by the value of the environment variable ``$NC|DB_*``.
You can mount your own config into ``/tpl`` and use your own environment variables with ``docker -e``.

#### Nextcloud cli

NextCloud offers the possibility to do administrative tasks via the command line interface `occ`. Just try it
```
docker exec -ti --user apache nc occ help
```


#### Upgrades 
### NextCloud
The used Nextcloud instance is updated frequently due to the automated build (linked to alpine). Thus updates are performed by pulling the newest image, moving the running container and starting a new one. Since the apps arent effected they will be upgraded by the webinterface on the next visit or via the command line. 
  
I recommend to upgrade via `occ`:
```
docker exec --user apache nc occ upgrade
```

Sometimes it happens that a upgrade fails and breaks your NextCloud webinterface because a app isnt compatible (or so). Then you have to disable the app with 
```
docker exec --user apache nc occ app:disable APPNAME
```
you may ask which apps are broken. Find out by observing `/var/www/localhost/htdocs/data/nextcloud.log``. Check a specific app with

```
docker exec --user apache nc app:check APPNAME
``` 
for compatiblity. If it fails, install the newest/compatible version by copying into `/var/www/localhost/htdocs/apps2/` (e.g. pulling from github). Afterwards try to enable it
```
docker exec --user apache nc app:enable APPNAME
```
If everything was successful you should be able to visit the webinterface again.
  
