## How to use

Clone the repo

```bash
symfony console doctrine:schema:create
symfony console doctrine:fixture:load --purge-with-truncate
symfony serve
```

The webpage is available at `https://localhost:8000/`.

Login using the credentials:

```
Username: PersonA
Password: secret
```

PersonA can approve only orders where the value is less than 10,000.

Clicking "Approve Order #1" will display a success message.

Clicking "Approve Order #2" will display a failure message.

## Running Tests

```bash
symfony console doctrine:schema:create --env=test
symfony console doctrine:fixture:load --purge-with-truncate --env=test
bin/phpunit
```