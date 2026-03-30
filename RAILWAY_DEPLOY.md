# Railway Deploy Notes

Set these Railway variables:

```env
APP_NAME=TrashDeal
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.up.railway.app

APP_KEY=base64:GENERATE_THIS_WITH_PHP_ARTISAN_KEY_GENERATE_SHOW

DB_CONNECTION=mysql
DB_HOST=YOUR_RAILWAY_MYSQL_HOST
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=YOUR_RAILWAY_MYSQL_PASSWORD

SESSION_DRIVER=cookie
CACHE_STORE=file
QUEUE_CONNECTION=sync
LOG_CHANNEL=stack
LOG_LEVEL=error
```

Startup is handled by:

- [Procfile](/C:/xampp/htdocs/trashdeal/Procfile)
- [railway-start.sh](/C:/xampp/htdocs/trashdeal/tools/railway-start.sh)
- [railway.json](/C:/xampp/htdocs/trashdeal/railway.json)

Run this one time after deploy succeeds:

```sh
sh tools/railway-init.sh
```
