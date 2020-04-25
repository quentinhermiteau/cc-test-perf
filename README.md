# SnowTricks

SnowTricks is a website built for Snowboard passionates.
Visiitors can discover tricks and users interactions and they can also have private acces to contribute by creating a member account.

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/c01632f854234b8ab04afdc57544dd32)](https://www.codacy.com/app/moezovic/snow-tricks?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=moezovic/snow-tricks&amp;utm_campaign=Badge_Grade)

## Built with

This project was built with :

Framework :  

* Symfony 4.2.8

ORM : 

* Doctrine

Testing :

* PHPUnit

Templating : 

* Twig

Dependancy Management:

* Composer

CSS & JS

* Bootstrap

* JQuery

## Installation

1. Clone this repository on your local machine : 

```
git clone https://gitlab.com/Lewisroy/cc-tests-de-perf.git
```

2. install [composer](https://getcomposer.org/doc/00-intro.md)

3. composer install

4. Change the files .env : Edit database parameter with yours 

5. Install the database

```
php bin/console doctrine:database:create
```

5. Create database schema

```
php bin/console doctrine:migration:migrate
```

5. Load the fixtures using this command:

```
php bin/console doctrine:fixtures:load
```

Problèmes rencontrés avec le groupe 2 :

Certaines personnes avaient les migrations qui ne marchaient pas, elles les ont supprimées et relancer.

Le composer install n'a pas marché pour certains, ils ont fait composer update juste après
