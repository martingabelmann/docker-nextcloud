# yet another dockerized NextCloud
_Based on Alpine_

---

#### Features
 - Full nextcloud instance
 - OneClick/Run installation
 - Enforced ssl encryption 
 - small alpine base image
 - php7 (including many modules)
 - auto configuration via environment vars

#### Installation
Get the image:
```
docker pull martingabelmann/nextcloud
```

It is highly recommended to use nextcloud with SSL. The default Apache setting of this container forces the browser to use ``https://``. There are certificates build in the image for testing but in production you`ll have to use your own:

Assuming you are owning (trusted) ssl-certificates at 
 - ``/srv/docker/nextcloud/ssl/server.key`` and 
 - ``/srv/docker/nextcloud/ssl/server.crt``,
 
which belong to the domain ``example.org``,

choose a good database- and adminpassword, then type:
  
```
docker run --name=nc -d -p 443:443 -p 80:80 \
  -e DB_PASS=changemepls -e NC_ADMINPASS=changemepls \
  -e NC_DOMAIN=example.org -e NC_EMAIL=admin@example.org \
  -v /srv/docker/nextcloud/:/nextcloud/ martingabelmann/nextcloud
```

This will mount and use the certificates. Your {data,config,additional apps} are stored on your host at ``/srv/docker/nextcloud/{data,config,apps}`` and the postgres database at ``/srv/docker/nextcloud/sql``. 


Check ``docker logs nc`` to verify that everything is done. Then point your browser to ``https://example.org/``. On the first vistit/install Nextcloud will do some configurations and directly login into to the admin panel.

##### Persistent configs
**All** files locatet at ``/tpl`` are copied to the filesystems root ``/`` relative to ``/tpl/``. 
For instance the preexisting file ``/tpl/etc/apache2/conf.d/httpd-vhosts.conf`` is copied to ``/etc/apache2/conf.d/httpd-vhosts.conf``.
Simultaneously the installation uses the tool ``envsubst`` to replace all bash variables with variables passed with the ``-e`` option. 
For php files this means, that you cannot simply write ``$phpvariable='"$NC_DOMAIN"';``, since the ``$phpvarvariable`` would be substituted too (with nothing if its not defined). 
There is an exported variable ``${D}`` containing the dollar sign:  ``${D}phpvariable='"$NC_DOMAIN"';`` will lead to the desired result (e.g. ``$phpvariable='example.org';``).

You can mount your own config into ``/tpl`` and use your own environment variables with ``-e``.  
  
_Exception:_ the configs under ``/tpl/var/www/localhost/htdocs/config`` are only for new installs. For existing NextCloud installations the files from ``/nextcloud/config`` are used.

#### Testing
A minimal working nextcloud instance can be run with

```
docker run --name=nctest -d -p 44300:443 -p 8000:80 martingabelmann/nextcloud
```
Then point your browser to ``https://localhost:44300``. The container will use the build-in certificates, so be carefully, dont use this in public networks/production!

Debuginformations can be viewed with
```docker logs nc```
or from inside the container (``docker exec -ti nc``) under ``/var/log/`` about apache or mysql.


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
  
