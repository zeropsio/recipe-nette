# Zerops x Nette

[Nette QuickStart](https://github.com/nette-examples/quickstart) is a very simple web application based on Nette Framework.
[Zerops](https://zerops.io) recipe for Nette QuickStart includes session and cache stored in Redis, and PostgreSQL DB with [Nextras migrations](https://nextras.org/migrations/).

![nette](https://raw.githubusercontent.com/zeropsio/recipe-shared-assets/main/covers/svg/cover-nette.svg)

<br/>

## Deploy on Zerops
You can either click the deploy button to deploy directly on Zerops, or manually copy the [import yaml](https://github.com/zeropsio/recipe-nette/blob/main/zerops-project-import.yml) to the import dialog in the Zerops app.

[![Deploy on Zerops](https://raw.githubusercontent.com/zeropsio/recipe-shared-assets/main/deploy-button/green/deploy-button.svg)](https://app.zerops.io/recipe/nette)

<br/>

## Recipe features

- Nette running on a load balanced **Zerops PHP + Nginx** service
- Zerops **PostgreSQL 16** service as database
- Zerops KeyDB (**Redis**) service for session and cache
- Setup for Nextras **database migrations** with automatic admin user creation
- Logs set up to use **syslog** and accessible through Zerops GUI
- Utilization of Zerops built-in **environment variables** system
- [AdminerEvo](https://www.adminerevo.org) for **quick database management** tool

<br/>

## Production vs. development

Base of the recipe is ready for production, the difference comes down to:

- Use highly available version of the PostgreSQL database (change `mode` from `NON_HA` to `HA` in recipe YAML, `db` service section)
- Use at least two containers for Nette service to achieve high reliability and resilience (add `minContainers: 2` in recipe YAML, `app` service section)
- Disable public access to Adminer or remove it altogether (remove service `adminer` from recipe YAML)
- Set `APP_ENV` to `prod` in `envSecrets`, `app` section of import YAML

<br/>

## Changes made over the default installation

If you want to modify your existing Nette app to efficiently run on Zerops, these are the general steps we took:

- Add [zerops.yml](https://github.com/zeropsio/recipe-nette/blob/main/zerops.yml) to your repository, our example includes idempotent migrations, caching, and optimized build process
- Add `$configurator->addDynamicParameters(['env' => getenv()]);` to your [./app/Bootstrap.php](https://github.com/zeropsio/recipe-nette/blob/main/app/Bootstrap.php:22) file to use env variables in your neon configuration files
- Set debug mode according to `APP_ENV` env variable in your [./app/Bootstrap.php](https://github.com/zeropsio/recipe-nette/blob/main/app/Bootstrap.php:27) file
- Add [nette/redis](https://github.com/nette/redis) to your composer.json to store sessions in Redis
    - configure it according to our [./config/common.neon](https://github.com/zeropsio/recipe-nette/blob/main/config/common.neon#L44) file
- Add [nette/monolog](https://github.com/nette/monolog) to your composer.json to log into the syslog
    - utilize the following handler: `Monolog\Handler\SyslogHandler(app)` (see our [./config/common.neon](https://github.com/MichalSalon/recipe-nette/blob/main/config/common.neon#L38))
- Add [nextras/migrations](https://github.com/nextras/migrations/) to your composer.json to utilize automated DB migrations
  - create `./migrations` folder structure similar to this repo (we moved content of `database.sql` into it)
  - SQLite (`./data/blog.sqlite`) was replaced by PostgreSQL database container

<br/>
<br/>

## Additional info

Admin login for this recipe is `admin` with automatically generated password,
which can be found under `ADMIN_PASSWORD` name in Zerops Environment variables section of `app` service.


Need help setting your project up? Join [Zerops Discord community](https://discord.com/invite/WDvCZ54).
