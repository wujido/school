Tasks maked in school
=============
Some creations of programming hours in the 4th school year

###Installation 
The best way is via composer:
```cmd
composer create-project wujido/school
```
Or you can just download as zip.

Then you must create database and run migration from *bin/db.sql*, and configure your conection in 
*app/config/config.neon* or create *config.local.neon*. 

There you have template for your conection:
```neon
database:
	dsn: 'mysql:host=127.0.0.1;dbname=name_of_your_database'
	user: root
	password: 
```


### Used technology
- Php framework [Nette](https://github.com/nette/nette) 
-  Nette addons
    - Bootstrap 4 forms - [GitHub](https://github.com/czubehead/bootstrap-4-forms)
- [Sass](https://sass-lang.com/) for styling
- Nice icons [Font awesome](https://fontawesome.com/)
- Database administration in one PHP file [Adminer](https://www.adminer.org/en/)
