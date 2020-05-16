# cc-test-perf

## Requirements

- Git
- Docker
- Docker Compose

## Instructions

1. Clone this repository on your local machine : 

```console
$ git clone https://github.com/quentinhermiteau/cc-test-perf.git
```

2. Install the dependencies

```console
$ docker-compose run --rm composer install
```

3. Run the Docker Compose services

```console
$ docker-compose up --detach php mariadb nginx
```

4. Go to the website

```console
$ chrome http://localhost
```
