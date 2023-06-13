# Autotune Remaps

This is a Wordpress plugin which uses AngularJS on the front end and Wordpress's REST API system to create a CRM for Autotune NI.

Customers can request remaps, check their figures before and after remaps, admins can manage remaps and create various type of records against a customers account.
Remaps are managed in a queue and notifications are emailed out to customers at every stage of the process.

## Testing locally

Firstly make sure you have docker and docker-compose installed.

1. Run `docker compose up -d`

2. You will be able to access a local version of Wordpress with the plugin enabled at:

- http://localhost:4070
- **Username:** Garry_Autotune
- **Password:** Aut0tun32020_

For testing, customer accounts can be logged into on a separate browser / incognito mode:

`blake_747 / Password1`

To see/request user remaps: 

`http://localhost:4070/my-account/`

Any changes you make to the source code will automatically update after a refresh.

When finished testing, run:
```docker compose down```

**Note:** The /wp-json/ API doesnt work with default Permalinks.

## PhpMyAdmin

To access phpmyadmin for inside the docker container, visit

```http://localhost:8181```

- **Username:** wordpress
- **Password:** wordpress